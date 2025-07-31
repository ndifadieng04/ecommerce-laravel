<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            // Électronique
            [
                'name' => 'Smartphone Samsung Galaxy S23',
                'description' => 'Smartphone haut de gamme avec écran 6.1" et appareil photo 50MP',
                'price' => 899.99,
                'stock' => 25,
                'category_id' => $categories->where('slug', 'electronique')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Ordinateur portable Dell Inspiron',
                'description' => 'Ordinateur portable 15.6" avec processeur Intel i5 et 8GB RAM',
                'price' => 699.99,
                'stock' => 15,
                'category_id' => $categories->where('slug', 'electronique')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Casque Bluetooth Sony WH-1000XM4',
                'description' => 'Casque sans fil avec réduction de bruit active',
                'price' => 349.99,
                'stock' => 30,
                'category_id' => $categories->where('slug', 'electronique')->first()->id,
                'is_active' => true,
            ],

            // Vêtements
            [
                'name' => 'T-shirt en coton bio',
                'description' => 'T-shirt confortable en coton biologique, plusieurs couleurs disponibles',
                'price' => 24.99,
                'stock' => 100,
                'category_id' => $categories->where('slug', 'vetements')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Jean slim fit',
                'description' => 'Jean moderne avec coupe slim, matière stretch confortable',
                'price' => 79.99,
                'stock' => 50,
                'category_id' => $categories->where('slug', 'vetements')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Veste en cuir',
                'description' => 'Veste en cuir véritable, style intemporel',
                'price' => 199.99,
                'stock' => 20,
                'category_id' => $categories->where('slug', 'vetements')->first()->id,
                'is_active' => true,
            ],

            // Livres
            [
                'name' => 'Le Petit Prince',
                'description' => 'Édition collector du célèbre roman de Saint-Exupéry',
                'price' => 15.99,
                'stock' => 75,
                'category_id' => $categories->where('slug', 'livres')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Harry Potter à l\'école des sorciers',
                'description' => 'Premier tome de la série Harry Potter',
                'price' => 12.99,
                'stock' => 60,
                'category_id' => $categories->where('slug', 'livres')->first()->id,
                'is_active' => true,
            ],

            // Sport
            [
                'name' => 'Ballon de football professionnel',
                'description' => 'Ballon de football taille 5, qualité professionnelle',
                'price' => 89.99,
                'stock' => 40,
                'category_id' => $categories->where('slug', 'sport')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tapis de yoga premium',
                'description' => 'Tapis de yoga antidérapant, épaisseur 5mm',
                'price' => 45.99,
                'stock' => 35,
                'category_id' => $categories->where('slug', 'sport')->first()->id,
                'is_active' => true,
            ],

            // Maison & Jardin
            [
                'name' => 'Lampe de table design',
                'description' => 'Lampe de table moderne avec abat-jour en tissu',
                'price' => 129.99,
                'stock' => 25,
                'category_id' => $categories->where('slug', 'maison-jardin')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Coussin décoratif',
                'description' => 'Coussin décoratif 40x40cm, plusieurs motifs disponibles',
                'price' => 29.99,
                'stock' => 80,
                'category_id' => $categories->where('slug', 'maison-jardin')->first()->id,
                'is_active' => true,
            ],

            // Beauté
            [
                'name' => 'Crème hydratante visage',
                'description' => 'Crème hydratante 24h pour tous types de peau',
                'price' => 34.99,
                'stock' => 45,
                'category_id' => $categories->where('slug', 'beaute')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Rouge à lèvres longue tenue',
                'description' => 'Rouge à lèvres mat, tenue jusqu\'à 8 heures',
                'price' => 19.99,
                'stock' => 70,
                'category_id' => $categories->where('slug', 'beaute')->first()->id,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            // Génère le slug à partir du nom
            $slug = Str::slug($productData['name']);

            Product::firstOrCreate(
                ['slug' => $slug], // Critère d’unicité
                array_merge($productData, ['slug' => $slug])
            );
        }
    }
} 