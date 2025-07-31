<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier !');
    }

    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function remove(Product $product)
{
    $cart = session()->get('cart', []);
    unset($cart[$product->id]);
    session(['cart' => $cart]);
    return redirect()->route('cart.index')->with('success', 'Produit supprimé du panier.');
}
public function update(Request $request, Product $product)
{
    $cart = session()->get('cart', []);
    $quantity = max(1, (int) $request->input('quantity', 1));
    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] = $quantity;
        session(['cart' => $cart]);
    }
    return redirect()->route('cart.index')->with('success', 'Quantité modifiée.');
}
}
