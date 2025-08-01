<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Nom du produit au moment de la commande
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2); // Prix unitaire au moment de la commande
            $table->decimal('total_price', 10, 2); // Prix total pour cet article
            $table->text('product_options')->nullable(); // Options choisies (taille, couleur, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
}; 