<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Note;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::query()->first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_url' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'footer_text' => 'nullable|string|max:500',
            'site_icon' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            // mail
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:none,ssl,tls,starttls,null',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        $settings = AdminSetting::query()->firstOrCreate([]);

        if ($request->hasFile('site_icon')) {
            $path = $request->file('site_icon')->store('app', 'public');
            $settings->site_icon_path = $path;
        }
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('app', 'public');
            $settings->favicon_path = $path;
        }

        $settings->site_name = $data['site_name'] ?? $settings->site_name;
        $settings->site_url = $data['site_url'] ?? $settings->site_url;
        $settings->contact_email = $data['contact_email'] ?? $settings->contact_email;
        $settings->footer_text = $data['footer_text'] ?? $settings->footer_text;
        // Save SMTP values
        $settings->mail_host = $data['mail_host'] ?? $settings->mail_host;
        $settings->mail_port = $data['mail_port'] ?? $settings->mail_port;
        $settings->mail_username = $data['mail_username'] ?? $settings->mail_username;
        if (!empty($data['mail_password'])) { // avoid overwriting with empty
            $settings->mail_password = $data['mail_password'];
        }
        $enc = $data['mail_encryption'] ?? $settings->mail_encryption;
        $settings->mail_encryption = ($enc === 'none' || $enc === 'null') ? null : $enc;
        $settings->mail_from_address = $data['mail_from_address'] ?? $settings->mail_from_address;
        $settings->mail_from_name = $data['mail_from_name'] ?? $settings->mail_from_name;

        $settings->save();

        $this->applyMailConfig($settings);

        return redirect()->route('settings.index')->with('status', 'Settings updated successfully');
    }

    protected function applyMailConfig(AdminSetting $s): void
    {
        if (!$s) return;
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $s->mail_host ?: env('MAIL_HOST'),
            'mail.mailers.smtp.port' => $s->mail_port ?: env('MAIL_PORT'),
            'mail.mailers.smtp.username' => $s->mail_username ?: env('MAIL_USERNAME'),
            'mail.mailers.smtp.password' => $s->mail_password ?: env('MAIL_PASSWORD'),
            'mail.mailers.smtp.encryption' => $s->mail_encryption ?: env('MAIL_ENCRYPTION'),
            'mail.from.address' => $s->mail_from_address ?: env('MAIL_FROM_ADDRESS'),
            'mail.from.name' => $s->mail_from_name ?: env('MAIL_FROM_NAME', 'wazaelimu'),
        ]);
    }

    public function testMail(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array(strtolower($user->role ?? ''), ['admin','super_admin','super administrator','superadministrator'])) {
            abort(403);
        }
        $data = $request->validate([
            'to' => 'required|email',
        ]);
        $settings = AdminSetting::query()->first();
        if ($settings) { $this->applyMailConfig($settings); }

        try {
            Mail::raw('Test email from wazaelimu Admin Settings (SMTP).', function($m) use ($data, $settings) {
                $m->to($data['to'])->subject('Wazaelimu SMTP Test');
                if ($settings && $settings->mail_from_address) {
                    $m->from($settings->mail_from_address, $settings->mail_from_name ?: 'wazaelimu');
                }
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['mail' => 'Send failed: '.$e->getMessage()]);
        }
        return back()->with('status', 'Test email sent to '.$data['to']);
    }

    public function backup()
    {
        $user = auth()->user();
        if (!$user || !in_array(strtolower($user->role ?? ''), ['super_admin', 'super administrator', 'superadministrator'])) {
            abort(403, 'Only super administrators can download backups.');
        }
        $zip = new ZipArchive();
        $ts = now()->format('Ymd_His');
        $dir = storage_path('app/tmp');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $zipPath = $dir . "/wazaelimu_documents_{$ts}.zip";

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors(['Could not create ZIP archive']);
        }

        // Optional: include DB dump if mysqldump is available
        try {
            $dumpPath = $dir . "/db_{$ts}.sql";
            $conn = config('database.default');
            $cfg = config("database.connections.$conn");
            if (($cfg['driver'] ?? '') === 'mysql') {
                $host = $cfg['host'] ?? '127.0.0.1';
                $port = $cfg['port'] ?? 3306;
                $db = $cfg['database'] ?? '';
                $usern = $cfg['username'] ?? '';
                $pass = $cfg['password'] ?? '';
                $cmd = "mysqldump --host={$host} --port={$port} --user={$usern} --password={$pass} --routines --events --single-transaction --quick {$db} > \"{$dumpPath}\"";
                // On Windows, run through powershell
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $cmd = 'powershell -NoProfile -Command ' . escapeshellarg($cmd);
                }
                @exec($cmd, $out, $code);
                if (file_exists($dumpPath) && filesize($dumpPath) > 0) {
                    $zip->addFile($dumpPath, 'database.sql');
                } else {
                    @unlink($dumpPath);
                }
            }
        } catch (\Throwable $e) {
            // ignore dump failures silently
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
                    // try to keep extension
                    $ext = pathinfo($local, PATHINFO_EXTENSION);
                    if ($ext && !str_ends_with($name, ".{$ext}")) { $name .= ".{$ext}"; }
                    $zip->addFile($local, $name);
                }
            }
        });

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
