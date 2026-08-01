<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get credentials from environment or use safe defaults
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD', 'ChangeThisPassword123!');
        $authorEmail = env('AUTHOR_EMAIL', 'author@example.com');
        $authorPassword = env('AUTHOR_PASSWORD', 'ChangeThisPassword123!');
        $userEmail = env('USER_EMAIL', 'user@example.com');
        $userPassword = env('USER_PASSWORD', 'ChangeThisPassword123!');

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'is_author' => true,
                'role' => 'admin',
                'bio' => 'Administrator and lead author of Nazaara Circle.',
                'website' => 'https://nazaaracircle.com',
            ]
        );

        // Create author user
        $author = User::firstOrCreate(
            ['email' => $authorEmail],
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'password' => Hash::make($authorPassword),
                'email_verified_at' => now(),
                'is_author' => true,
                'role' => 'author',
                'bio' => 'Entertainment journalist and film critic. Love sharing reviews and analysis of movies, TV shows, and pop culture.',
                'website' => 'https://johndoe.dev',
                'twitter' => '@johndoe',
                'github' => 'johndoe',
            ]
        );

        // Create regular user
        $user = User::firstOrCreate(
            ['email' => $userEmail],
            [
                'name' => 'Jane Smith',
                'username' => 'janesmith',
                'password' => Hash::make($userPassword),
                'email_verified_at' => now(),
                'is_author' => false,
                'role' => 'user',
            ]
        );

        // Create Dr Tool admin user
        $drTool = User::firstOrCreate(
            ['email' => 'drtoolofficial@gmail.com'],
            [
                'name' => 'Dr Tool',
                'username' => 'drtool',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_author' => true,
                'role' => 'admin',
                'bio' => 'Administrator account for Dr Tool.',
            ]
        );

        $this->command->info('✅ Users seeded successfully!');
        $this->command->warn('⚠️  Please change default passwords after first login!');
        $this->command->info("Admin: {$adminEmail}");
        $this->command->info("Author: {$authorEmail}");
        $this->command->info("User: {$userEmail}");
    }
}

