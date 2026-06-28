<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\Temple;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = session('locale', config('app.locale'));

        // DB-backed site settings (cached for the request), with sensible
        // fallbacks so the site never breaks on a fresh install.
        $temple = cache()->remember('temple.primary', 60, fn () => Temple::where('is_primary', true)->first());

        $setting = fn (string $key, $default = null) => Setting::get($key, $default);

        $decode = function (string $key, array $default) use ($setting) {
            $raw = $setting($key);

            return $raw ? json_decode($raw, true) ?? $default : $default;
        };

        $defaultStats = [
            ['value' => '5L+', 'label' => 'Devotees Served', 'icon' => 'users'],
            ['value' => '100+', 'label' => 'Years of Legacy', 'icon' => 'clock'],
            ['value' => '500+', 'label' => 'Daily Annaseva', 'icon' => 'soup'],
            ['value' => '24/7', 'label' => 'Live Darshan', 'icon' => 'wifi'],
        ];

        $defaultFacilities = [
            ['icon' => 'bed', 'title' => 'Dharamshala', 'desc' => 'Comfortable accommodation for pilgrims with AC and non-AC rooms.', 'image' => '/storage/facilities/dharamshala.jpg'],
            ['icon' => 'home', 'title' => 'Vishram Gruh', 'desc' => 'A peaceful rest house for devotees seeking a quiet retreat.', 'image' => '/storage/about/temple.jpg'],
            ['icon' => 'soup', 'title' => 'Annashetra', 'desc' => 'Free wholesome meals (anna seva) served daily to all devotees.', 'image' => '/storage/facilities/anna.jpg'],
            ['icon' => 'flame', 'title' => 'Havan Khand', 'desc' => 'Dedicated space for havan, yagna and sacred fire rituals.', 'image' => '/storage/facilities/havan.jpg'],
            ['icon' => 'cross', 'title' => 'Free Medical Services', 'desc' => 'Periodic health camps providing free check-ups and medicines.', 'image' => '/storage/facilities/medical.jpg'],
            ['icon' => 'trees', 'title' => 'Environment Initiatives', 'desc' => 'Tree plantation and green drives around the temple grounds.', 'image' => '/storage/facilities/tree.jpg'],
        ];

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locale' => $locale,
            'nav' => __('nav'),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'siteSettings' => [
                'name' => $temple?->name ?? config('app.name'),
                'tagline' => $setting('site_tagline', env('SITE_TAGLINE', '|| Jay Mataji ||')),
                'phone' => $temple?->phone ?? env('SITE_PHONE', '+91 0000000000'),
                'email' => $temple?->email ?? env('SITE_EMAIL', 'contact@talajatemple.org'),
                'address' => $temple?->address ?? env('SITE_ADDRESS', 'Talaja, Gujarat'),
                'logo' => $temple?->logo_path,
            ],
            'homeStats' => $decode('home_stats', $defaultStats),
            'facilities' => $decode('facilities', $defaultFacilities),
        ];
    }
}
