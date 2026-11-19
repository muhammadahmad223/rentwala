<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'RentWala Admin', 'email' => 'admin@rentwala.test', 'password' => 'password123', 'role' => 'super_admin']);
    }
}
