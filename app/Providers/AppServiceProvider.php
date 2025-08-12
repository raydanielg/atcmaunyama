<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // If Vite is present (legacy), keep prefetch harmless
        if (class_exists(\Illuminate\Support\Facades\Vite::class)) {
            Vite::prefetch(concurrency: 3);
        }

        // Share site settings globally for blades (logo, phone, names, etc.)
        $settings = null;
        try {
            if (Schema::hasTable('admin_settings')) {
                $row = DB::table('admin_settings')->first();
                if ($row) {
                    // Normalize meta JSON and expose site_phone if present
                    $meta = [];
                    if (!empty($row->meta)) {
                        try { $meta = json_decode($row->meta, true) ?: []; } catch (\Throwable $e) { $meta = []; }
                    }
                    $row->site_phone = $row->site_phone ?? ($meta['site_phone'] ?? null);
                }
                $settings = $row;
            }
        } catch (\Throwable $e) {
            // ignore
        }
        View::share('siteSettings', $settings);
    }
}
