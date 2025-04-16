<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
            DB::table('users')->insert([
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'email' => 'admin@admin.com',
                    'password' => Hash::make('12345678'),
                    'password_confirmation' => Hash::make('12345678'), // Tambahkan kolom ini
                    'is_administrator' => true,
                ]
            ]);
    }

}
