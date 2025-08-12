<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Guard if table not ready yet
        if (!Schema::hasTable('login_logs')) {
            return;
        }

        $request = request();
        DB::table('login_logs')->insert([
            'email' => $event->user->email ?? 'unknown',
            'ip_address' => optional($request)->ip(),
            'user_agent' => optional($request)->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
