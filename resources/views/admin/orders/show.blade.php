@extends('layouts.admin')

@section('title', 'Détails de la commande')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Commande #{{ $order->order_number }}</h1>
            <p class="text-muted mb-0">Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.invoice.download', $order->id) }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>
                Télécharger facture
            </a>
            <a href="{{ route('admin.orders.invoice.view', $order->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-eye me-2"></i>
                Voir facture
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart"></i> Détails de la commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Statut de la commande</h6>
                            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="mb-3">
                                @csrf
                                @method('PUT')
                                <div class="d-flex gap-2">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En cours</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h6>Statut du paiement</h6>
                            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="d-flex gap-2">
                                    <select name="payment_status" class="form-select">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Échoué</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits commandés -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box"></i> Produits commandés
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-center">Prix unitaire</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product)
                                                    <br><small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                                    <td class="text-center fw-bold">{{ number_format($item->total_price, 2, ',', ' ') }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations client et résumé -->
        <div class="col-lg-4">
            <!-- Informations client -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Informations client
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom</label>
                        <p class="mb-0">{{ $order->name ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="mb-0">{{ $order->email ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Téléphone</label>
                        <p class="mb-0">{{ $order->phone ?? 'Non renseigné' }}</p>
                    </div>
                    @if($order->user)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Client enregistré</label>
                        <p class="mb-0">{{ $order->user->name }} (ID: {{ $order->user->id }})</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Adresse de livraison -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt"></i> Adresse de livraison
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Résumé financier -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator"></i> Résumé financier
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total :</span>
                        <span>{{ number_format($order->items->sum('total_price'), 2, ',', ' ') }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Livraison :</span>
                        <span>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>TVA :</span>
                        <span>{{ number_format($order->tax_amount, 2, ',', ' ') }} €</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <span class="h5 mb-0">Total :</span>
                        <span class="h5 mb-0 fw-bold text-primary">{{ number_format($order->total_amount, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des modifications -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history"></i> Historique des modifications
            </h5>
        </div>
        <div class="card-body">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <h6>Commande créée</h6>
                        <p class="text-muted mb-0">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                @if($order->paid_at)
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6>Paiement reçu</h6>
                        <p class="text-muted mb-0">{{ $order->paid_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                @endif
                @if($order->shipped_at)
                <div class="timeline-item">
                    <div class="timeline-marker bg-info"></div>
                    <div class="timeline-content">
                        <h6>Commande expédiée</h6>
                        <p class="text-muted mb-0">{{ $order->shipped_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                @endif
                @if($order->delivered_at)
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6>Commande livrée</h6>
                        <p class="text-muted mb-0">{{ $order->delivered_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}
</style>
@endsection 