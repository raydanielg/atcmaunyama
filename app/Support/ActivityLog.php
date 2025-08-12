<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityLog
{
    public static function log(string $action, ?string $description = null, ?int $causerId = null): void
    {
        if (!Schema::hasTable('activity_logs')) {
            return;
        }

        DB::table('activity_logs')->insert([
            'action' => $action,
            'description' => $description,
            'causer_id' => $causerId,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
