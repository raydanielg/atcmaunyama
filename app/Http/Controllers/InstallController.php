<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;

class InstallController extends Controller
{
    public function index(Request $request)
    {
        $already = env('APP_INSTALLED', false);
        if ($already) {
            return redirect('/');
        }
        $checks = $this->systemChecks();
        $allOk = collect($checks['requirements'])->every(fn($ok) => $ok === true)
            && collect($checks['permissions'])->every(fn($ok) => $ok === true);
        return view('install.index', [
            'checks' => $checks,
            'allOk' => $allOk,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'app_url' => 'nullable|url',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        // Re-check environment before installation
        $checks = $this->systemChecks();
        $allOk = collect($checks['requirements'])->every(fn($ok) => $ok === true)
            && collect($checks['permissions'])->every(fn($ok) => $ok === true);
        if (!$allOk) {
            return back()->withErrors(['preflight' => 'Server environment does not meet requirements. Fix the red items and try again.'])->withInput();
        }

        // Ensure .env exists (copy from example if missing)
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            $example = base_path('.env.example');
            if (File::exists($example)) {
                File::copy($example, $envPath);
            } else {
                File::put($envPath, "APP_NAME=Laravel\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_KEY=\n");
            }
        }

        // Ensure APP_KEY
        if (empty(config('app.key'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }

        // Configure DB at runtime
        Config::set('database.default', 'mysql');
        Config::set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => $validated['db_host'],
            'port' => $validated['db_port'],
            'database' => $validated['db_database'],
            'username' => $validated['db_username'],
            'password' => $validated['db_password'] ?? '',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);

        try {
            // Test connection
            DB::purge('mysql');
            DB::reconnect('mysql');
            DB::connection()->getPdo();
        } catch (Exception $e) {
            return back()->withErrors(['db' => 'Failed to connect to MySQL: ' . $e->getMessage()])->withInput();
        }

        // Run migrations and seeders
        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        } catch (Exception $e) {
            return back()->withErrors(['migrate' => 'Migration/Seed failed: ' . $e->getMessage()])->withInput();
        }

        // Create admin user
        try {
            $admin = User::firstOrCreate(
                ['email' => $validated['admin_email']],
                [
                    'name' => $validated['admin_name'],
                    'password' => Hash::make($validated['admin_password']),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
        } catch (Exception $e) {
            return back()->withErrors(['admin' => 'Failed to create admin user: ' . $e->getMessage()])->withInput();
        }

        // Persist to .env
        try {
            $this->writeEnv([
                'APP_URL' => $validated['app_url'] ?? env('APP_URL', 'http://127.0.0.1:8000'),
                'APP_INSTALLED' => 'true',
                'DB_CONNECTION' => 'mysql',
                'DB_HOST' => $validated['db_host'],
                'DB_PORT' => $validated['db_port'],
                'DB_DATABASE' => $validated['db_database'],
                'DB_USERNAME' => $validated['db_username'],
                'DB_PASSWORD' => $validated['db_password'] ?? '',
                'SESSION_DRIVER' => env('SESSION_DRIVER', 'database'),
            ]);
        } catch (Exception $e) {
            // Not fatal for running site, but warn
            return back()->withErrors(['env' => 'Installed, but failed to write .env: ' . $e->getMessage()])->withInput();
        }

        // Cache config and routes for performance
        try {
            Artisan::call('optimize:clear');
        } catch (Exception $e) {
            // ignore
        }

        // Try storage symlink (public/storage)
        try {
            Artisan::call('storage:link');
        } catch (Exception $e) { /* ignore on restricted hosts */ }

        // Create installation lock file
        try {
            $lockDir = storage_path('framework');
            if (!File::exists($lockDir)) { File::makeDirectory($lockDir, 0755, true); }
            File::put($lockDir . DIRECTORY_SEPARATOR . 'app_installed', now()->toDateTimeString());
        } catch (Exception $e) {
            // ignore if cannot write; APP_INSTALLED still set in .env
        }

        // List tables (MySQL)
        $tables = collect(DB::select('SHOW TABLES'))
            ->map(function ($row) { return array_values((array)$row)[0]; })
            ->values()
            ->all();

        return view('install.success', [
            'adminEmail' => $admin->email,
            'tables' => $tables,
        ]);
    }

    private function writeEnv(array $pairs)
    {
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            throw new FileNotFoundException('.env file not found');
        }
        $env = File::get($envPath);

        foreach ($pairs as $key => $value) {
            $escaped = $this->envEscape($value);
            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$escaped}", $env);
            } else {
                $env .= "\n{$key}={$escaped}";
            }
        }

        File::put($envPath, $env);
    }

    private function envEscape($value)
    {
        $value = (string)$value;
        if (Str::contains($value, [' ', '#', '"'])) {
            // wrap in quotes and escape quotes
            $value = '"' . str_replace('"', '\\"', $value) . '"';
        }
        return $value;
    }

    private function systemChecks(): array
    {
        $requirements = [
            'php_version' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'ext_pdo' => extension_loaded('pdo'),
            'ext_pdo_mysql' => extension_loaded('pdo_mysql'),
            'ext_mbstring' => extension_loaded('mbstring'),
            'ext_tokenizer' => extension_loaded('tokenizer'),
            'ext_xml' => extension_loaded('xml'),
            'ext_curl' => extension_loaded('curl'),
            'ext_openssl' => extension_loaded('openssl'),
            'ext_ctype' => extension_loaded('ctype'),
            'ext_json' => extension_loaded('json'),
            'ext_bcmath' => extension_loaded('bcmath'),
            'ext_fileinfo' => extension_loaded('fileinfo'),
            'ext_intl' => extension_loaded('intl'),
        ];

        $paths = [
            'storage' => is_writable(storage_path()),
            'storage_logs' => is_writable(storage_path('logs')) || @mkdir(storage_path('logs'), 0755, true),
            'storage_framework' => is_writable(storage_path('framework')) || @mkdir(storage_path('framework'), 0755, true),
            'bootstrap_cache' => is_writable(base_path('bootstrap/cache')),
            'env_writable' => file_exists(base_path('.env')) ? is_writable(base_path('.env')) : is_writable(base_path()),
        ];

        return [
            'requirements' => $requirements,
            'permissions' => $paths,
        ];
    }
}
