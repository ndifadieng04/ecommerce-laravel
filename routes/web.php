<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'E-commerce API Backend',
        'version' => '1.0.0',
        'documentation' => '/api/documentation',
        'endpoints' => [
            'categories' => '/api/v1/categories',
            'products' => '/api/v1/products'
        ]
    ]);
});

// Route pour la documentation API
Route::get('/api/documentation', function () {
    return view('api-documentation');
});



// Afficher le panier
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');

// Ajouter au panier
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');

// Supprimer un produit du panier
Route::post('/panier/supprimer/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Modifier la quantité
Route::post('/panier/modifier/{product}', [CartController::class, 'update'])->name('cart.update');
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits', [ProductController::class, 'index']); // alias
Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');



Route::get('/commande', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/commande', [OrderController::class, 'process'])->name('order.process');