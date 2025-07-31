<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
            'name' => 'Admin User',
            'password' => Hash::make('password123'),
            'phone' => '0123456789',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'shipping_address' => '123 Rue de la Paix, 75001 Paris',
            'billing_address' => '123 Rue de la Paix, 75001 Paris',
            'newsletter_subscription' => true,
            'email_verified_at' => now(),
        ]
    );

        // Créer un utilisateur client
        User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
            'name' => 'John Doe',
            'password' => Hash::make('password123'),
            'phone' => '0987654321',
            'birth_date' => '1985-05-15',
            'gender' => 'male',
            'shipping_address' => '456 Avenue des Champs, 75008 Paris',
            'billing_address' => '456 Avenue des Champs, 75008 Paris',
            'newsletter_subscription' => false,
            'email_verified_at' => now(),
        ]
    );

        // Créer un utilisateur client
        User::firstOrCreate(
             ['email' => 'jane@example.com'],
             [
            'name' => 'Jane Smith',
            'password' => Hash::make('password123'),
            'phone' => '0555666777',
            'birth_date' => '1992-08-20',
            'gender' => 'female',
            'shipping_address' => '789 Boulevard Saint-Germain, 75006 Paris',
            'billing_address' => '789 Boulevard Saint-Germain, 75006 Paris',
            'newsletter_subscription' => true,
            'email_verified_at' => now(),
        ]
    );

        // Créer des utilisateurs supplémentaires
        User::factory(10)->create();
    }
} 