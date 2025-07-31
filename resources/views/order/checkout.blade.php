@extends('layouts.app')

@section('title', 'Passer la commande - E-commerce')

@section('content')
<div class="container">
    <div class="row">
        <!-- Formulaire de commande -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Finaliser votre commande
                    </h2>
                </div>
                
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
        @endforeach
    </ul>
                        </div>
                    @endif

    <form method="POST" action="{{ route('order.process') }}">
        @csrf
                        
                        <!-- Informations personnelles -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations personnelles</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" 
                                                   placeholder="Nom complet" 
                                                   value="{{ old('name', auth()->user()->name ?? '') }}" 
                                                   required>
                                            <label for="name">
                                                <i class="fas fa-user me-2"></i>Nom complet
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" 
                                                   placeholder="Email" 
                                                   value="{{ old('email', auth()->user()->email ?? '') }}" 
                                                   required>
                                            <label for="email">
                                                <i class="fas fa-envelope me-2"></i>Adresse email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           placeholder="Téléphone" 
                                           value="{{ old('phone', auth()->user()->phone ?? '') }}">
                                    <label for="phone">
                                        <i class="fas fa-phone me-2"></i>Téléphone (optionnel)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Adresse de livraison -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Adresse de livraison</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" 
                                              placeholder="Adresse complète" 
                                              style="height: 100px" 
                                              required>{{ old('address', auth()->user()->shipping_address ?? '') }}</textarea>
                                    <label for="address">
                                        <i class="fas fa-home me-2"></i>Adresse complète de livraison
                                    </label>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" name="city" 
                                                   placeholder="Ville" 
                                                   value="{{ old('city') }}" 
                                                   required>
                                            <label for="city">
                                                <i class="fas fa-city me-2"></i>Ville
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" 
                                                   class="form-control @error('postal_code') is-invalid @enderror" 
                                                   id="postal_code" name="postal_code" 
                                                   placeholder="Code postal" 
                                                   value="{{ old('postal_code') }}" 
                                                   required>
                                            <label for="postal_code">
                                                <i class="fas fa-mail-bulk me-2"></i>Code postal
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mode de paiement -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Mode de paiement</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <select class="form-select @error('payment') is-invalid @enderror" 
                                            id="payment" name="payment" required>
                                        <option value="">Sélectionner un mode de paiement...</option>
                                        <option value="online" {{ old('payment') == 'online' ? 'selected' : '' }}>
                                            💳 Paiement en ligne sécurisé (simulé)
                                        </option>
                                        <option value="cod" {{ old('payment') == 'cod' ? 'selected' : '' }}>
                                            💰 Paiement à la livraison (espèces)
                                        </option>
                                    </select>
                                    <label for="payment">
                                        <i class="fas fa-credit-card me-2"></i>Mode de paiement
                                    </label>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Paiement en ligne :</strong> Simulation de paiement sécurisé<br>
                                    <strong>Paiement à la livraison :</strong> Paiement en espèces à la réception
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary" style="border-radius: 25px;">
                                <i class="fas fa-arrow-left me-2"></i>Retour au panier
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                <i class="fas fa-check me-2"></i>Confirmer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Résumé de la commande -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Résumé de votre commande
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Produits -->
        <div class="mb-3">
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             class="rounded me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             alt="{{ $item['name'] }}">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <small class="fw-semibold">{{ $item['name'] }}</small><br>
                                        <small class="text-muted">Qté: {{ $item['quantity'] }}</small>
                                    </div>
                                </div>
                                <span class="fw-semibold">{{ number_format($itemTotal, 2, ',', ' ') }} €</span>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <!-- Totaux -->
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

                    <!-- Informations de sécurité -->
                    <div class="alert alert-light border">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            <strong>Paiement sécurisé</strong>
                        </div>
                        <small class="text-muted">
                            Vos informations sont protégées par un chiffrement SSL
                        </small>
                    </div>

                    <div class="alert alert-light border">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-truck text-primary me-2"></i>
                            <strong>Livraison rapide</strong>
                        </div>
                        <small class="text-muted">
                            Livraison sous 2-3 jours ouvrés
                        </small>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
@endsection