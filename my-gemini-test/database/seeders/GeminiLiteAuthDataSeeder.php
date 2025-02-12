<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeminiLiteAuthDataSeeder extends Seeder
{
    public function run()
    {
        DB::table('gemini_lite_roles')->insert([
            [
                'name' => 'premium_user',
                'description' => 'Usuario con acceso premium a Gemini',
                'daily_request_limit' => 1000,
                'monthly_request_limit' => 30000,
                'daily_token_limit' => 1000000,
                'monthly_token_limit' => 30000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'limited_user',
                'description' => 'Usuario con acceso limitado a Gemini',
                'daily_request_limit' => 100,
                'monthly_request_limit' => 3000,
                'daily_token_limit' => 300000,
                'monthly_token_limit' => 9000000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

