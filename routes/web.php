<?php

use Illuminate\Support\Facades\Route;

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
