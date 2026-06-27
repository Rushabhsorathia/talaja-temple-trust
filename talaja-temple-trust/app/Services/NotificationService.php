<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendTemplate(string $code, string $recipient, array $vars = []): NotificationLog
    {
        $template = NotificationTemplate::where('code', $code)->where('is_active', true)->first();

        $content = $template
            ? $this->interpolate($template->localizedBody(), $vars)
            : $code;

        $channel = $template?->channel ?? 'sms';

        return match ($channel) {
            'email' => $this->sendEmail($recipient, $template?->subject ?? config('app.name'), $content, $template?->id),
            'whatsapp' => $this->sendWhatsApp($recipient, $content, $template?->id),
            default => $this->sendSms($recipient, $content, $template?->id),
        };
    }

    public function sendSms(string $mobile, string $message, ?int $templateId = null): NotificationLog
    {
        $apiKey = env('MSG91_AUTH_KEY');
        $sender = env('MSG91_SENDER_ID', 'TALAJA');

        if (filled($apiKey)) {
            // TODO: real MSG91 HTTP call (M5-T2 production).
        }

        Log::info("[SMS] To {$mobile}: {$message}");

        return NotificationLog::create([
            'channel' => 'sms', 'recipient' => $mobile, 'content' => $message,
            'status' => 'sent', 'template_id' => $templateId, 'sent_at' => now(),
        ]);
    }

    public function sendEmail(string $email, string $subject, string $body, ?int $templateId = null): NotificationLog
    {
        try {
            Mail::raw($body, function ($m) use ($email, $subject) {
                $m->to($email)->subject($subject);
            });
            $status = 'sent';
        } catch (\Throwable $e) {
            Log::error('[Email] '.$e->getMessage());
            $status = 'failed';
        }

        return NotificationLog::create([
            'channel' => 'email', 'recipient' => $email, 'content' => $body,
            'status' => $status, 'template_id' => $templateId, 'sent_at' => now(),
        ]);
    }

    public function sendWhatsApp(string $mobile, string $message, ?int $templateId = null): NotificationLog
    {
        $token = env('WHATSAPP_ACCESS_TOKEN');
        if (! $token) {
            Log::info("[WhatsApp] To {$mobile}: {$message} (not configured)");
        }

        return NotificationLog::create([
            'channel' => 'whatsapp', 'recipient' => $mobile, 'content' => $message,
            'status' => $token ? 'sent' : 'queued', 'template_id' => $templateId, 'sent_at' => now(),
        ]);
    }

    protected function interpolate(string $template, array $vars): string
    {
        return preg_replace_callback('/\{(\w+)\}/', fn ($m) => $vars[$m[1]] ?? $m[0], $template);
    }
}
