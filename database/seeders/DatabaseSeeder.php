<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\RegionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed regions first
        $this->call(RegionSeeder::class);

        // Sample users for roles: user, admin, super_admin
        User::updateOrCreate(
            ['email' => 'admin@elms-atc.tld'],
            [
                'name' => 'ELMS-ATC Admin',
                'role' => 'admin',
                'password' => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@elms-atc.tld'],
            [
                'name' => 'ELMS-ATC User',
                'role' => 'user',
                'password' => Hash::make('User@12345'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'super@elms-atc.tld'],
            [
                'name' => 'ELMS-ATC Super Admin',
                'role' => 'super_admin',
                'password' => Hash::make('Super@12345'),
                'email_verified_at' => now(),
            ]
        );
    }
}
