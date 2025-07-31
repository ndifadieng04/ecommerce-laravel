<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\AuthController;

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



// Routes de commande
Route::get('/commande', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/commande', [OrderController::class, 'process'])->name('order.process');
Route::get('/commande/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    
    // Routes de commandes pour utilisateurs connectés
    Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mes-commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
});
