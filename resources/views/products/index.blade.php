@extends('layouts.app')

@section('title', 'Catalogue de produits - E-Shop')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold mb-3" style="color: #667eea;">
            <i class="fas fa-box me-2"></i>Catalogue de produits
        </h1>
        
        <!-- Filtres et recherche -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control search-form" 
                                   name="q" 
                                   placeholder="Rechercher un produit..." 
                                   value="{{ request('q') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <select name="category" class="form-select category-filter">
        <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        @if(request('category') == $category->id) selected @endif>
                                    {{ $category->name }}
            </option>
        @endforeach
    </select>
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                    </div>
</form>
            </div>
        </div>
    </div>
</div>

<!-- Résultats de recherche -->
@if(request('q') || request('category'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                @if(request('q') && request('category'))
                    Recherche pour "{{ request('q') }}" dans la catégorie "{{ $categories->find(request('category'))->name }}"
                @elseif(request('q'))
                    Recherche pour "{{ request('q') }}"
                @elseif(request('category'))
                    Filtrage par catégorie "{{ $categories->find(request('category'))->name }}"
            @endif
                <a href="{{ route('products.index') }}" class="float-end">
                    <i class="fas fa-times me-1"></i>Effacer les filtres
                </a>
            </div>
        </div>
    </div>
@endif

<!-- Grille de produits -->
@if($products->count() > 0)
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @foreach($products as $product)
            <div class="col">
                <div class="card product-card h-100 shadow-sm">
                    <!-- Image du produit -->
                    <div class="position-relative">
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top product-image" 
                             alt="{{ $product->name }}">
                        
                        <!-- Badge de disponibilité -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($product->stock > 0)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>En stock
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Rupture
                                </span>
                            @endif
                        </div>
                        
                        <!-- Badge de catégorie -->
                        @if($product->category)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-primary">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <!-- Nom du produit -->
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $product) }}" 
                               class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>
                        
                        <!-- Description courte -->
                        @if($product->description)
                            <p class="card-text text-muted small">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                        @endif
                        
                        <!-- Prix -->
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 mb-0" style="color: #667eea;">
                                    {{ number_format($product->price, 2, ',', ' ') }} €
                                </span>
                                @if($product->stock > 0)
                                    <small class="text-muted">
                                        <i class="fas fa-box me-1"></i>{{ $product->stock }} disponible(s)
                                    </small>
                                @endif
                            </div>
                            
                            <!-- Boutons d'action -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-outline-primary" style="border-color: #667eea; color: #667eea;">
                                    <i class="fas fa-eye me-1"></i>Voir détails
                                </a>
                                
                                @if($product->stock > 0)
                                    <form method="POST" action="{{ route('cart.add', $product) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                            <i class="fas fa-cart-plus me-1"></i>Ajouter au panier
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-ban me-1"></i>Indisponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endforeach
</div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
{{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
    
@else
    <!-- Aucun produit trouvé -->
    <div class="row">
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">Aucun produit trouvé</h3>
                <p class="text-muted">
                    @if(request('q') || request('category'))
                        Aucun produit ne correspond à votre recherche.
                        <a href="{{ route('products.index') }}" class="text-decoration-none">
                            Voir tous les produits
                        </a>
                    @else
                        Aucun produit n'est disponible pour le moment.
                    @endif
                </p>
            </div>
        </div>
    </div>
@endif

<!-- Statistiques -->
@if($products->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Affichage de {{ $products->firstItem() ?? 0 }} à {{ $products->lastItem() ?? 0 }} 
                        sur {{ $products->total() }} produit(s)
                    </small>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection