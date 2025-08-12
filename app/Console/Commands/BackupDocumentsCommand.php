<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\Note;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupDocumentsCommand extends Command
{
    protected $signature = 'wazaelimu:backup-documents {--upload : Upload to S3 if configured}';
    protected $description = 'Create ZIP backup of Notes and Materials; optionally upload to S3 and prune backups older than 7 days';

    public function handle(): int
    {
        $this->info('Starting backup...');
        $zip = new ZipArchive();
        $ts = now()->format('Ymd_His');
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) { @mkdir($tmpDir, 0775, true); }
        $zipPath = $tmpDir . "/wazaelimu_documents_{$ts}.zip";

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('Could not create ZIP');
            return self::FAILURE;
        }

        // Optional DB dump
        try {
            $dumpPath = $tmpDir . "/db_{$ts}.sql";
            $conn = config('database.default');
            $cfg = config("database.connections.$conn");
            if (($cfg['driver'] ?? '') === 'mysql') {
                $host = $cfg['host'] ?? '127.0.0.1';
                $port = $cfg['port'] ?? 3306;
                $db = $cfg['database'] ?? '';
                $usern = $cfg['username'] ?? '';
                $pass = $cfg['password'] ?? '';
                $cmd = "mysqldump --host={$host} --port={$port} --user={$usern} --password={$pass} --routines --events --single-transaction --quick {$db} > \"{$dumpPath}\"";
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $cmd = 'powershell -NoProfile -Command ' . escapeshellarg($cmd);
                }
                @exec($cmd, $out, $code);
                if (file_exists($dumpPath) && filesize($dumpPath) > 0) {
                    $zip->addFile($dumpPath, 'database.sql');
                } else { @unlink($dumpPath); }
            }
        } catch (\Throwable $e) {
            Log::warning('DB dump failed: '.$e->getMessage());
        }

        // Notes
        Note::query()->chunk(200, function ($chunk) use ($zip) {
            foreach ($chunk as $n) {
                if ($n->file_path && Storage::exists($n->file_path)) {
                    $local = Storage::path($n->file_path);
                    $name = 'notes/' . ($n->original_name ?: (basename($n->file_path)));
                    $zip->addFile($local, $name);
                }
            }
        });

        // Materials
        Material::query()->chunk(200, function ($chunk) use ($zip) {
            foreach ($chunk as $m) {
                if ($m->path && Storage::exists($m->path)) {
                    $local = Storage::path($m->path);
                    $name = 'materials/' . ($m->title ? preg_replace('/[^A-Za-z0-9._-]+/', '_', $m->title) : basename($m->path));
                    $ext = pathinfo($local, PATHINFO_EXTENSION);
                    if ($ext && !str_ends_with($name, ".{$ext}")) { $name .= ".{$ext}"; }
                    $zip->addFile($local, $name);
                }
            }
        });

        $zip->close();
        $this->info('ZIP created: '.$zipPath);

        if ($this->option('upload')) {
            $disk = config('filesystems.cloud', 's3');
            try {
                $s3 = Storage::disk($disk);
                $remote = 'backups/wazaelimu_'.$ts.'.zip';
                $s3->put($remote, fopen($zipPath, 'r'), ['visibility' => 'private']);
                $this->info('Uploaded to '.$disk.': '.$remote);

                // prune >7 days
                $this->pruneOldBackups($s3, 'backups/', now()->subDays(7));
            } catch (\Throwable $e) {
                $this->error('Upload failed: '.$e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    protected function pruneOldBackups($disk, string $prefix, $threshold): void
    {
        try {
            $files = $disk->files($prefix);
            foreach ($files as $file) {
                $mtime = $disk->lastModified($file);
                if ($mtime && \Carbon\Carbon::createFromTimestamp($mtime)->lt($threshold)) {
                    $disk->delete($file);
                    $this->line('Pruned: '.$file);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Prune failed: '.$e->getMessage());
        }
    }
}
