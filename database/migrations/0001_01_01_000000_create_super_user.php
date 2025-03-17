<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (empty(env('SUPER_ADMIN_EMAIL')) || empty(env('SUPER_ADMIN_PASSWORD'))) {
            throw new \Exception('Required environment variables SUPER_ADMIN_EMAIL and SUPER_ADMIN_PASSWORD must be set');
        }

        if (!Schema::hasTable('users')) {
            throw new \Exception('Users table does not exist - please run base migrations first');
        }

        if (Schema::hasTable('users')) {
            // Check if user doesn't already exist to avoid duplicates
            if (!DB::table('users')->where('email', env('SUPER_ADMIN_EMAIL'))->exists()) {
                DB::table('users')->insert([
                    'name' => 'Super Admin User',
                    'email' => env('SUPER_ADMIN_EMAIL'),
                    'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Optional: Log or throw an exception if table doesn't exist
            Log::warning('Users table does not exist - skipping initial user insertion');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            // Delete the super user
            DB::table('users')->where('email', env('SUPER_ADMIN_EMAIL'))->delete();
        }
    }
};
