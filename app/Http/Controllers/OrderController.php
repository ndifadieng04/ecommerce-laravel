<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    
    public function checkout()
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
    }
    return view('order.checkout', compact('cart'));
}

public function process(Request $request)
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'email' => 'required|email',
        'payment' => 'required|in:online,cod',
    ]);

    DB::beginTransaction();
    try {
        // Création de la commande
        $order = \App\Models\Order::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => $validated['name'],
            'address' => $validated['address'],
            'email' => $validated['email'],
            'payment_method' => $validated['payment'],
            'status' => 'en attente',
            'total' => collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            }),
        ]);

        // Ajout des produits à la commande
        foreach ($cart as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Vider le panier
        session()->forget('cart');

        DB::commit();
        return redirect()->route('products.index')->with('success', 'Commande passée avec succès !');
    } catch (\Exception $e) {
        DB::rollBack();
        // Affiche l'erreur pour le debug :
        return back()->with('error', $e->getMessage());
    }
}
}