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
        
        // Debug: afficher le contenu du panier
        \Log::info('Contenu du panier:', $cart);
        
        if (empty($cart)) {
            \Log::info('Panier vide, redirection vers cart.index');
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        
        // Récupérer les données utilisateur si connecté
        $user = auth()->user();
        
        \Log::info('Affichage de la page checkout');
        return view('order.checkout', compact('cart', 'user'));
    }

    public function process(Request $request)
    {
        \Log::info('Méthode process appelée');
        \Log::info('Données reçues:', $request->all());
        
        $cart = session('cart', []);
        \Log::info('Contenu du panier dans process:', $cart);
        
        if (empty($cart)) {
            \Log::info('Panier vide dans process, redirection');
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
                return (float) $item['price'] * $item['quantity'];
            });

            // Création de la commande
            \Log::info('Création de la commande avec total: ' . $total);
            $order = Order::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => 'pending',
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['address'] . ', ' . $validated['city'] . ' ' . $validated['postal_code'],
                'billing_address' => $validated['address'] . ', ' . $validated['city'] . ' ' . $validated['postal_code'],
                'shipping_method' => 'standard',
                'shipping_cost' => 0, // Livraison gratuite
                'tax_amount' => 0,
                'notes' => null,
            ]);
            \Log::info('Commande créée avec ID: ' . $order->id);

            // Ajout des produits à la commande
            \Log::info('Création des OrderItem pour la commande ' . $order->id);
            foreach ($cart as $item) {
                try {
                                    $unitPrice = (float) $item['price'];
                $totalPrice = $unitPrice * $item['quantity'];
                
                \Log::info('Création OrderItem avec prix: unit_price=' . $unitPrice . ', total_price=' . $totalPrice);
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
                    \Log::info('OrderItem créé avec ID: ' . $orderItem->id);

                    // Mise à jour du stock du produit
                    $product = \App\Models\Product::find($item['id']);
                    if ($product) {
                        $product->decrement('stock', $item['quantity']);
                        \Log::info('Stock mis à jour pour le produit ' . $product->id);
                    }
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la création de OrderItem: ' . $e->getMessage());
                    throw $e;
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
            \Log::error('Erreur lors de la création de commande: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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

    // Gestion des factures pour les clients
    public function downloadInvoice(Order $order)
    {
        // Vérifier que l'utilisateur connecté est propriétaire de la commande
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $invoiceService = new \App\Services\InvoiceService();
        
        return $invoiceService->downloadInvoice($order);
    }

    public function viewInvoice(Order $order)
    {
        // Vérifier que l'utilisateur connecté est propriétaire de la commande
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $invoiceService = new \App\Services\InvoiceService();
        
        return $invoiceService->streamInvoice($order);
    }
}