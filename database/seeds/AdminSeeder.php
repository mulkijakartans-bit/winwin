<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat admin user
        User::firstOrCreate(
            ['email' => 'admin@winwinmakeup.com'],
            [
                'name' => 'Admin WINWIN Makeup',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created!');
        $this->command->info('Email: admin@winwinmakeup.com');
        $this->command->info('Password: admin123');
    }
}
