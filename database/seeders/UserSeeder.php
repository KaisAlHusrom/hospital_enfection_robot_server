<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('name', 'admin')->delete();
        User::updateOrCreate(['name' => 'admin', 'email' => 'admin@test.com', 'password' => 'admin']);
        User::where('name', 'admin')->first()->assignRole(Role::Admin->value);
    }
}
