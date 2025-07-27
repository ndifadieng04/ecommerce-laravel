<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Électronique',
                'description' => 'Tous les produits électroniques et gadgets',
                'slug' => 'electronique',
                'is_active' => true,
            ],
            [
                'name' => 'Vêtements',
                'description' => 'Mode et accessoires vestimentaires',
                'slug' => 'vetements',
                'is_active' => true,
            ],
            [
                'name' => 'Livres',
                'description' => 'Livres, magazines et publications',
                'slug' => 'livres',
                'is_active' => true,
            ],
            [
                'name' => 'Sport',
                'description' => 'Équipements et vêtements de sport',
                'slug' => 'sport',
                'is_active' => true,
            ],
            [
                'name' => 'Maison & Jardin',
                'description' => 'Décoration et aménagement',
                'slug' => 'maison-jardin',
                'is_active' => true,
            ],
            [
                'name' => 'Beauté',
                'description' => 'Cosmétiques et produits de beauté',
                'slug' => 'beaute',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
} 