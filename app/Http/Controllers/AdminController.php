<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        // Statistiques générales
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalCustomers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();

        // Commandes récentes
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        // Produits les plus vendus
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Statistiques par statut
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Statistiques mensuelles pour le graphique
        $monthlyStats = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as orders_count'),
            DB::raw('SUM(total_amount) as revenue')
        )
        ->where('status', '!=', 'cancelled')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'topProducts',
            'ordersByStatus',
            'monthlyStats'
        ));
    }

    public function statistics()
    {
        // Statistiques mensuelles
        $monthlyStats = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as orders_count'),
            DB::raw('SUM(total_amount) as revenue')
        )
        ->where('status', '!=', 'cancelled')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        return view('admin.statistics', compact('monthlyStats'));
    }

    // Gestion des commandes
    public function orders(Request $request)
    {
        $query = Order::with('items')->latest();

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function orderShow($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function orderUpdate(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($request->has('status')) {
            $order->status = $request->status;
            
            // Mettre à jour les timestamps selon le statut
            if ($request->status === 'shipped' && !$order->shipped_at) {
                $order->shipped_at = now();
            } elseif ($request->status === 'delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
            }
        }

        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
            
            if ($request->payment_status === 'paid' && !$order->paid_at) {
                $order->paid_at = now();
            }
        }

        $order->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Commande mise à jour avec succès');
    }

    // Gestion des clients
    public function customers()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function customerShow($id)
    {
        $customer = User::with('orders.items')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function customerUpdate(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $customer->update($request->only(['name', 'email']));

        return redirect()->back()->with('success', 'Client mis à jour avec succès');
    }

    // Gestion des produits
    public function products()
    {
        $products = Product::with('category')
            ->withCount('orderItems')
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function productCreate()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès');
    }

    public function productEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès');
    }

    public function productDestroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès');
    }

    // Gestion des catégories
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('admin.categories.create');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée avec succès');
    }

    public function categoryEdit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour avec succès');
    }

    public function categoryDestroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Vérifier si la catégorie a des produits
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée avec succès');
    }

    // Gestion des factures
    public function downloadInvoice($id)
    {
        $order = Order::findOrFail($id);
        
        $invoiceService = new \App\Services\InvoiceService();
        
        return $invoiceService->downloadInvoice($order);
    }

    public function viewInvoice($id)
    {
        $order = Order::findOrFail($id);
        
        $invoiceService = new \App\Services\InvoiceService();
        
        return $invoiceService->streamInvoice($order);
    }
}
