@extends('layouts.app')

@section('title', 'Confirmation de commande - E-commerce')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-4x"></i>
                    </div>
                    <h2 class="mb-0">Commande confirmée !</h2>
                    <p class="mb-0 mt-2">Merci pour votre commande</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Informations de la commande -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations de commande</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Numéro de commande :</strong><br>
                                        <span class="badge bg-primary">{{ $order->order_number }}</span>
                                    </p>
                                    <p><strong>Date :</strong><br>
                                        {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                    <p><strong>Statut :</strong><br>
                                        <span class="badge bg-{{ $order->status == 'en attente' ? 'warning' : 'info' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Mode de paiement :</strong><br>
                                        @if($order->payment_method == 'online')
                                            <i class="fas fa-credit-card me-1"></i>Paiement en ligne
                                        @else
                                            <i class="fas fa-money-bill-wave me-1"></i>Paiement à la livraison
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informations client</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nom :</strong><br>{{ $order->name }}</p>
                                    <p><strong>Email :</strong><br>{{ $order->email }}</p>
                                    @if($order->phone)
                                        <p><strong>Téléphone :</strong><br>{{ $order->phone }}</p>
                                    @endif
                                    <p><strong>Adresse :</strong><br>
                                        {{ $order->shipping_address }}<br>
                                        {{ $order->postal_code }} {{ $order->city }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produits commandés -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Produits commandés</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Produit</th>
                                            <th class="border-0 text-center">Quantité</th>
                                            <th class="border-0 text-center">Prix unitaire</th>
                                            <th class="border-0 text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                                 class="rounded me-3" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                                 alt="{{ $item->product_name }}">
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                            @if($item->product)
                                                                <small class="text-muted">{{ $item->product->category->name ?? 'Sans catégorie' }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ number_format($item->unit_price, 2, ',', ' ') }} €
                                                </td>
                                                <td class="align-middle text-center fw-bold">
                                                    {{ number_format($item->total_price, 2, ',', ' ') }} €
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé des coûts -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Résumé des coûts</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Sous-total :</span>
                                        <span class="fw-semibold">{{ number_format($order->items->sum('total_price'), 2, ',', ' ') }} €</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Livraison :</span>
                                        <span class="text-success">Gratuite</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-0">
                                        <span class="h5 mb-0">Total :</span>
                                        <span class="h5 mb-0 fw-bold" style="color: #667eea;">{{ number_format($order->total_amount, 2, ',', ' ') }} €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prochaines étapes -->
                    <div class="alert alert-info mt-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Prochaines étapes :</h6>
                        <ul class="mb-0">
                            @if($order->payment_method == 'online')
                                <li>Vous recevrez un email de confirmation dans les prochaines minutes</li>
                                <li>Le paiement sera traité automatiquement</li>
                            @else
                                <li>Vous recevrez un email de confirmation dans les prochaines minutes</li>
                                <li>Préparez le montant exact pour le paiement à la livraison</li>
                            @endif
                            <li>Votre commande sera expédiée sous 2-3 jours ouvrés</li>
                            <li>Vous recevrez un email avec le numéro de suivi</li>
                        </ul>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary" style="border-radius: 25px;">
                            <i class="fas fa-shopping-bag me-2"></i>Continuer les achats
                        </a>
                        <div class="d-flex gap-2">
                            @auth
                                <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn btn-success" style="border-radius: 25px;">
                                    <i class="fas fa-download me-2"></i>Télécharger facture
                                </a>
                                <a href="{{ route('orders.invoice.view', $order->id) }}" class="btn btn-info" target="_blank" style="border-radius: 25px;">
                                    <i class="fas fa-eye me-2"></i>Voir facture
                                </a>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                    <i class="fas fa-eye me-2"></i>Voir les détails
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 