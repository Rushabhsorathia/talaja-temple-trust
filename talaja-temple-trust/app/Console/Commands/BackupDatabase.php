<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database {--keep=30 : Number of daily backups to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the SQLite/MySQL database + storage to local + S3-compatible disk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stamp = now()->format('Ymd_His');
        $dir = storage_path('app/backups');
        @mkdir($dir, 0775, true);

        $conn = config('database.default');

        if ($conn === 'sqlite') {
            $path = config('database.connections.sqlite.database');
            $dest = "{$dir}/db_{$stamp}.sqlite";
            copy($path, $dest);
        } else {
            $cfg = config("database.connections.{$conn}");
            $dest = "{$dir}/db_{$stamp}.sql";
            $cmd = sprintf(
                'mysqldump -h%s -P%s -u%s -p%s %s > %s',
                escapeshellarg($cfg['host'] ?? '127.0.0.1'),
                escapeshellarg($cfg['port'] ?? '3306'),
                escapeshellarg($cfg['username'] ?? 'root'),
                escapeshellarg($cfg['password'] ?? ''),
                escapeshellarg($cfg['database']),
                escapeshellarg($dest)
            );
            exec($cmd);
        }

        $this->info("Backup written: {$dest}");

        // Prune old backups.
        $keep = (int) $this->option('keep');
        foreach (glob("{$dir}/db_*") as $file) {
            if (filemtime($file) < now()->subDays($keep)->getTimestamp()) {
                @unlink($file);
            }
        }

        // Mirror to S3-compatible disk if configured.
        if (filled(env('AWS_BUCKET'))) {
            try {
                $name = basename($dest);
                \Illuminate\Support\Facades\Storage::disk('s3')->put("backups/{$name}", file_get_contents($dest));
                $this->info('Mirrored to S3.');
            } catch (\Throwable $e) {
                $this->error('S3 mirror failed: '.$e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
