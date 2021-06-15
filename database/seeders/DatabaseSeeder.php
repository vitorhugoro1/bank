<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Domains\Users\Models\User::factory()->create([
            'email' => 'example@example.com'
        ]);
    }
}
