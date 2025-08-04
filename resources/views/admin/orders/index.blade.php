@extends('layouts.admin')

@section('title', 'Gestion des commandes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des commandes</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En cours</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Paiement</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Tous les paiements</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->name ?? 'Client non connecté' }}</strong><br>
                                    <small class="text-muted">{{ $order->email }}</small>
                                </div>
                            </td>
                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <strong>{{ number_format($order->total_amount, 2, ',', ' ') }} €</strong>
                            </td>
                            <td>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning">En attente</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info">En cours</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge bg-primary">Expédiée</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">Livrée</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Annulée</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($order->payment_status)
                                    @case('pending')
                                        <span class="badge bg-warning">En attente</span>
                                        @break
                                    @case('paid')
                                        <span class="badge bg-success">Payé</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Échoué</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'processing')">
                                            <i class="fas fa-play"></i> Marquer en cours
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'shipped')">
                                            <i class="fas fa-shipping-fast"></i> Marquer expédiée
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'delivered')">
                                            <i class="fas fa-check"></i> Marquer livrée
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="updateStatus({{ $order->id }}, 'cancelled')">
                                            <i class="fas fa-times"></i> Annuler
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune commande trouvée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateStatus(orderId, status) {
    if (confirm('Êtes-vous sûr de vouloir modifier le statut de cette commande ?')) {
        fetch(`/admin/orders/${orderId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la mise à jour du statut');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la mise à jour du statut');
        });
    }
}
</script>
@endsection 