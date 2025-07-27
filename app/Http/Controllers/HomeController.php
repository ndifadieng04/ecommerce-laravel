<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $featuredProducts = Product::with('category')->active()->inStock()->latest()->take(8)->get();
        $categories = Category::withCount('products')->active()->take(6)->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }

    /**
     * Display the shop page.
     */
    public function shop(Request $request)
    {
        $query = Product::with('category')->active();

        // Filtrage par catégorie
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrage par prix
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::active()->get();

        return view('shop', compact('products', 'categories'));
    }

    /**
     * Display a specific product.
     */
    public function product($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->active()->firstOrFail();
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }
} 