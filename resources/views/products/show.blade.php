@extends('layouts.app')

@section('title', $product->name . ' - E-Shop')

@section('content')
<!-- Fil d'Ariane -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('products.index') }}" class="text-decoration-none">
                <i class="fas fa-home me-1"></i>Accueil
            </a>
        </li>
        @if($product->category)
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-decoration-none">
                    {{ $product->category->name }}
                </a>
            </li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Images du produit -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <img src="{{ $product->image_url }}" 
                     class="img-fluid w-100" 
                     style="max-height: 500px; object-fit: cover;"
                     alt="{{ $product->name }}">
            </div>
        </div>
    </div>
    
    <!-- Informations du produit -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- En-tête du produit -->
                <div class="mb-4">
                    <h1 class="h2 fw-bold text-dark mb-2">{{ $product->name }}</h1>
                    
                    @if($product->category)
                        <span class="badge bg-primary mb-3">
                            <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                        </span>
                    @endif
                    
                    <!-- Prix -->
                    <div class="mb-3">
                        <span class="display-6 text-primary fw-bold">
                            {{ number_format($product->price, 2, ',', ' ') }} €
                        </span>
                    </div>
                    
                    <!-- Disponibilité -->
                    <div class="mb-4">
                        @if($product->stock > 0)
                            <div class="d-flex align-items-center text-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <span class="fw-semibold">En stock</span>
                                <span class="ms-2 text-muted">({{ $product->stock }} disponible(s))</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center text-danger">
                                <i class="fas fa-times-circle me-2"></i>
                                <span class="fw-semibold">Rupture de stock</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Description -->
                @if($product->description)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Description
                        </h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                @endif
                
                <!-- Actions -->
                <div class="mb-4">
                    @if($product->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mb-3">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label fw-semibold">
                                        <i class="fas fa-sort-numeric-up me-1"></i>Quantité
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="quantity" 
                                           name="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->stock }}"
                                           required>
                                </div>
                                <div class="col-md-8 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                        <i class="fas fa-cart-plus me-2"></i>Ajouter au panier
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Ce produit est actuellement indisponible.
                        </div>
                    @endif
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="border-top pt-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-muted">
                                <i class="fas fa-shipping-fast fa-2x mb-2"></i>
                                <div class="small">Livraison rapide</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted">
                                <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                <div class="small">Garantie</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted">
                                <i class="fas fa-undo fa-2x mb-2"></i>
                                <div class="small">Retour facile</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Produits similaires -->
@if($product->category && $product->category->products->where('id', '!=', $product->id)->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">
                <i class="fas fa-thumbs-up me-2" style="color: #667eea;"></i>Produits similaires
            </h3>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach($product->category->products->where('id', '!=', $product->id)->take(4) as $similarProduct)
                    <div class="col">
                        <div class="card product-card h-100 shadow-sm">
                            <div class="position-relative">
                                @if($similarProduct->image)
                                    <img src="{{ asset('storage/' . $similarProduct->image) }}" 
                                         class="card-img-top product-image" 
                                         alt="{{ $similarProduct->name }}">
                                @else
                                    <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                @endif
                                
                                @if($similarProduct->stock > 0)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success">En stock</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">
                                    <a href="{{ route('products.show', $similarProduct) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ $similarProduct->name }}
                                    </a>
                                </h6>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="h6 mb-0" style="color: #667eea;">
                                            {{ number_format($similarProduct->price, 2, ',', ' ') }} €
                                        </span>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <a href="{{ route('products.show', $similarProduct) }}" 
                                           class="btn btn-outline-primary btn-sm" style="border-color: #667eea; color: #667eea;">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Bouton retour -->
<div class="row mt-4">
    <div class="col-12">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour au catalogue
        </a>
    </div>
</div>
@endsection