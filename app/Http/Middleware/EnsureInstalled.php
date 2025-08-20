<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureInstalled
{
    public function handle(Request $request, Closure $next)
    {
        // If installer is disabled via env, bypass all installer checks
        if (!env('INSTALLER_ENABLED', false)) {
            return $next($request);
        }
        $installed = (bool) env('APP_INSTALLED', false);
        // Also honor a lock file to be robust on hosting environments
        if (!$installed && file_exists(storage_path('framework/app_installed'))) {
            $installed = true;
        }
        // Allow installer routes and health checks regardless
        if (!$installed) {
            if (
                $request->is('install*') ||
                $request->path() === 'up' ||
                $request->is('storage/*') ||
                $request->is('build/*') ||
                $request->is('assets/*') ||
                $request->is('vendor/*')
            ) {
                return $next($request);
            }
            return redirect()->to('/install');
        }

        // If already installed, block installer pages
        if ($installed && $request->is('install*')) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}

