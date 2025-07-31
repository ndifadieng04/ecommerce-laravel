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
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment' => 'required|in:online,cod',
        ]);

        DB::beginTransaction();
        try {
            // Calcul du total
            $total = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            // Création de la commande
            $order = Order::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'payment_method' => $validated['payment'],
                'status' => $validated['payment'] == 'online' ? 'en attente de paiement' : 'en attente',
                'total' => $total,
                'subtotal' => $total,
                'shipping_cost' => 0, // Livraison gratuite
                'notes' => null,
            ]);

            // Ajout des produits à la commande
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                // Mise à jour du stock du produit
                $product = \App\Models\Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Si l'utilisateur est connecté, mettre à jour ses statistiques
            if (auth()->check()) {
                $user = auth()->user();
                $user->increment('orders_count');
                $user->increment('total_spent', $total);
                $user->update(['last_order_at' => now()]);
            }

            // Vider le panier
            session()->forget('cart');

            DB::commit();

            // Message de succès selon le mode de paiement
            $message = $validated['payment'] == 'online' 
                ? 'Commande passée avec succès ! Vous recevrez un email de confirmation.'
                : 'Commande passée avec succès ! Paiement à la livraison.';

            return redirect()->route('order.confirmation', $order->id)
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du traitement de votre commande. Veuillez réessayer.');
        }
    }

   public function confirmation($orderId)
{
    $order = Order::with('items.product')->findOrFail($orderId);
    
    // Vérifier que l'utilisateur peut voir cette commande
    if (auth()->check() && $order->user_id !== auth()->id()) {
        abort(403);
    }
    
    return view('order.confirmation', compact('order'));
}

public function index()
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $orders = Order::where('user_id', auth()->id())
        ->with('items')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('order.index', compact('orders'));
}

public function show($orderId)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $order = Order::with('items.product')->findOrFail($orderId);
    
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    return view('order.show', compact('order'));
}
}