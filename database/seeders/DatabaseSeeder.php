<?php

namespace Database\Seeders;

use App\Models\User;
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

        User::create([
            'nama' => 'Ananda Bayu',
            'email' => 'bayu@email.com',
            'npp' => '12345',
            'npp_supervisor' => '11111',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'nama' => 'Supervisor',
            'email' => 'spv@email.com',
            'npp' => '11111',
            'npp_supervisor' => null,
            'password' => bcrypt('password'),
        ]);
    }
}
