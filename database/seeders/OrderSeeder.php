<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Users or Products not found. Skipping OrderSeeder.');
            return;
        }

        // Créer 10 commandes de test
        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0, // Sera calculé après
                'status' => $this->getRandomStatus(),
                'shipping_address' => $this->generateAddress(),
                'billing_address' => $this->generateAddress(),
                'shipping_method' => $this->getRandomShippingMethod(),
                'shipping_cost' => rand(500, 2000) / 100, // 5€ à 20€
                'tax_amount' => 0, // Sera calculé après
                'notes' => rand(0, 1) ? 'Commande de test' : null,
                'paid_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                'shipped_at' => rand(0, 1) ? now()->subDays(rand(1, 15)) : null,
                'delivered_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
            ]);

            // Ajouter 1 à 4 articles par commande
            $numItems = rand(1, 4);
            $selectedProducts = $products->random($numItems);

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->price;
                $totalPrice = $quantity * $unitPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_options' => null
                ]);
            }

            // Calculer le total de la commande
            $subtotal = $order->items->sum('total_price');
            $taxAmount = $subtotal * 0.20; // 20% de TVA
            $totalAmount = $subtotal + $order->shipping_cost + $taxAmount;

            $order->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount
            ]);
        }

        // Mettre à jour les statistiques des utilisateurs
        foreach ($users as $user) {
            $user->updateOrderStats();
        }

        $this->command->info('Orders seeded successfully!');
    }

    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        $weights = [20, 30, 25, 20, 5]; // Probabilités
        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $statuses[$index];
            }
        }

        return 'pending';
    }

    private function getRandomShippingMethod(): string
    {
        $methods = ['standard', 'express', 'pickup'];
        return $methods[array_rand($methods)];
    }

    private function generateAddress(): string
    {
        $streets = ['Rue de la Paix', 'Avenue des Champs', 'Boulevard Central', 'Place de la République'];
        $cities = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Bordeaux'];
        
        $street = $streets[array_rand($streets)];
        $number = rand(1, 100);
        $city = $cities[array_rand($cities)];
        $postalCode = rand(10000, 99999);
        
        return "$number $street, $postalCode $city, France";
    }
} 