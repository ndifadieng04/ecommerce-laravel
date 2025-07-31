@extends('layouts.app')

@section('title', 'Mon panier - E-Shop')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-primary mb-3">
            <i class="fas fa-shopping-cart me-2"></i>Mon panier
        </h1>
    </div>
</div>

    @if(empty($cart))
    <!-- Panier vide -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted mb-3">Votre panier est vide</h3>
                    <p class="text-muted mb-4">Découvrez nos produits et commencez vos achats !</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                        <i class="fas fa-shopping-bag me-2"></i>Voir les produits
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Contenu du panier -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Produits dans votre panier ({{ count($cart) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Produit</th>
                                    <th class="border-0 text-center">Prix unitaire</th>
                                    <th class="border-0 text-center">Quantité</th>
                                    <th class="border-0 text-center">Total</th>
                                    <th class="border-0 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $item)
                                    @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                                         class="rounded me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;"
                                                         alt="{{ $item['name'] }}">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item['name'] }}</h6>
                                                    <small class="text-muted">ID: {{ $item['id'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="fw-semibold">{{ number_format($item['price'], 2, ',', ' ') }} €</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <form action="{{ route('cart.update', $item['id']) }}" 
                                                  method="POST" 
                                                  class="d-flex align-items-center justify-content-center">
                                                @csrf
                                                <div class="input-group" style="width: 120px;">
                                                    <button type="button" 
                                                            class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                            data-action="decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" 
                                                           class="form-control form-control-sm text-center quantity-input"
                                                           style="border-left: 0; border-right: 0;">
                                                    <button type="button" 
                                                            class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                            data-action="increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm ms-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px;">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="fw-bold" style="color: #667eea;">{{ number_format($itemTotal, 2, ',', ' ') }} €</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <form action="{{ route('cart.remove', $item['id']) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
        @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
    </form>
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Résumé de la commande -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Résumé de la commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total :</span>
                        <span class="fw-semibold">{{ number_format($total, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Livraison :</span>
                        <span class="text-success">Gratuite</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h5 mb-0">Total :</span>
                        <span class="h5 mb-0 fw-bold" style="color: #667eea;">{{ number_format($total, 2, ',', ' ') }} €</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('order.checkout') }}" class="btn btn-success btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                            <i class="fas fa-credit-card me-2"></i>Passer la commande
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary" style="border-color: #667eea; color: #667eea;">
                            <i class="fas fa-plus me-2"></i>Continuer les achats
                        </a>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Paiement sécurisé
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- JavaScript pour les boutons de quantité -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons de quantité
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const action = this.dataset.action;
            let value = parseInt(input.value);
            
            if (action === 'increase') {
                value++;
            } else if (action === 'decrease' && value > 1) {
                value--;
            }
            
            input.value = value;
        });
    });
});
</script>
@endsection