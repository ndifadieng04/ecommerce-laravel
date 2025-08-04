<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SessionController;

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

// Routes pour le basculement de sessions
Route::get('/switch-to-client', [SessionController::class, 'switchToClient'])->name('switch.to.client');
Route::get('/switch-to-admin', [SessionController::class, 'switchToAdmin'])->name('switch.to.admin');
Route::get('/session-selector', [SessionController::class, 'showSessionSelector'])->name('session.selector');

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
    
    // Factures pour les clients
    Route::get('/mes-commandes/{order}/facture', [OrderController::class, 'downloadInvoice'])->name('orders.invoice.download');
    Route::get('/mes-commandes/{order}/facture/voir', [OrderController::class, 'viewInvoice'])->name('orders.invoice.view');
});

// Routes d'authentification admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Routes publiques (connexion)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    });
    
    // Routes protégées (administration)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
        
        // Routes d'administration
        Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('statistics');
        
        // Commandes
        Route::get('/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\AdminController::class, 'orderShow'])->name('orders.show');
        Route::put('/orders/{id}', [App\Http\Controllers\AdminController::class, 'orderUpdate'])->name('orders.update');
        
        // Clients
        Route::get('/customers', [App\Http\Controllers\AdminController::class, 'customers'])->name('customers.index');
        Route::get('/customers/{id}', [App\Http\Controllers\AdminController::class, 'customerShow'])->name('customers.show');
        
        // Produits
        Route::get('/products', [App\Http\Controllers\AdminController::class, 'products'])->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\AdminController::class, 'productCreate'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\AdminController::class, 'productStore'])->name('products.store');
        Route::get('/products/{id}/edit', [App\Http\Controllers\AdminController::class, 'productEdit'])->name('products.edit');
        Route::put('/products/{id}', [App\Http\Controllers\AdminController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{id}', [App\Http\Controllers\AdminController::class, 'productDestroy'])->name('products.destroy');
        
        // Catégories
        Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories.index');
        Route::get('/categories/create', [App\Http\Controllers\AdminController::class, 'categoryCreate'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\AdminController::class, 'categoryStore'])->name('categories.store');
        Route::get('/categories/{id}/edit', [App\Http\Controllers\AdminController::class, 'categoryEdit'])->name('categories.edit');
        Route::put('/categories/{id}', [App\Http\Controllers\AdminController::class, 'categoryUpdate'])->name('categories.update');
        Route::delete('/categories/{id}', [App\Http\Controllers\AdminController::class, 'categoryDestroy'])->name('categories.destroy');
        
        // Factures
        Route::get('/orders/{id}/invoice', [App\Http\Controllers\AdminController::class, 'downloadInvoice'])->name('orders.invoice.download');
        Route::get('/orders/{id}/invoice/view', [App\Http\Controllers\AdminController::class, 'viewInvoice'])->name('orders.invoice.view');
    });
});
