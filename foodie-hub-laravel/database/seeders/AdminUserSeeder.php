<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'fcadmin@gmail.com')->first();

        if (!$adminExists) {
            User::create([
                'name' => 'Food Court Admin',
                'email' => 'fcadmin@gmail.com',
                'password' => Hash::make('fctadmin'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
                'last_login_at' => now(),
            ]);

            $this->command->info('✅ Admin user created successfully!');
            $this->command->info('📧 Email: fcadmin@gmail.com');
            $this->command->info('🔑 Password: fctadmin');
        } else {
            $this->command->warn('⚠️  Admin user already exists!');
        }
    }
}
