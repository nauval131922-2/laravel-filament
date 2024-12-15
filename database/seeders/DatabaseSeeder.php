<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name' => 'Nauval',
            'email' => 'nauval@mail.com',
            'password' => bcrypt('123'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'role' => 'admin',
        ]);
    }
}
