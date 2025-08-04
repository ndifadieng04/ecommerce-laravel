@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tableau de bord</h1>
        <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <!-- Statistiques générales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Commandes</h6>
                            <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Chiffre d'affaires</h6>
                            <h3 class="mb-0">{{ number_format($totalRevenue, 2) }} €</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Clients</h6>
                            <h3 class="mb-0">{{ number_format($totalCustomers) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Produits</h6>
                            <h3 class="mb-0">{{ number_format($totalProducts) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Commandes récentes -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Commandes récentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->name ?? 'Anonyme' }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} €</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'delivered' ? 'success' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune commande récente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits les plus vendus -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Produits les plus vendus</h5>
                </div>
                <div class="card-body">
                    @forelse($topProducts as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <small class="text-muted">{{ $product->total_sold }} vendus</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $product->total_sold }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">Aucun produit vendu</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des ventes mensuelles -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Évolution des ventes</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyStats->pluck('month')) !!},
        datasets: [{
            label: 'Chiffre d\'affaires (€)',
            data: {!! json_encode($monthlyStats->pluck('revenue')) !!},
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection 