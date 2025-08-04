@extends('layouts.admin')

@section('title', 'Statistiques - Administration')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line me-2"></i>
            Statistiques détaillées
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour au tableau de bord
        </a>
    </div>

    <!-- Statistiques mensuelles -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Évolution des ventes mensuelles
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="row">
        <!-- Graphique des commandes par statut -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>
                        Commandes par statut
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique des revenus par mois -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-euro-sign me-2"></i>
                        Revenus par mois
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des statistiques mensuelles -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table me-2"></i>
                        Détails des statistiques mensuelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Année</th>
                                    <th>Nombre de commandes</th>
                                    <th>Revenus (€)</th>
                                    <th>Moyenne par commande</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyStats as $stat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromDate($stat->year, $stat->month, 1)->format('F') }}</td>
                                    <td>{{ $stat->year }}</td>
                                    <td class="text-center">{{ $stat->orders_count }}</td>
                                    <td class="text-end">{{ number_format($stat->revenue, 2, ',', ' ') }} €</td>
                                    <td class="text-end">{{ $stat->orders_count > 0 ? number_format($stat->revenue / $stat->orders_count, 2, ',', ' ') : '0,00' }} €</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune donnée disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques
    const monthlyData = @json($monthlyStats);
    
    // Préparation des données pour le graphique mensuel
    const labels = monthlyData.map(item => {
        const date = new Date(item.year, item.month - 1);
        return date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
    }).reverse();
    
    const ordersData = monthlyData.map(item => item.orders_count).reverse();
    const revenueData = monthlyData.map(item => item.revenue).reverse();

    // Graphique des ventes mensuelles
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nombre de commandes',
                data: ordersData,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                yAxisID: 'y'
            }, {
                label: 'Revenus (€)',
                data: revenueData,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre de commandes'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenus (€)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Graphique des revenus par mois
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenus (€)',
                data: revenueData,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenus (€)'
                    }
                }
            }
        }
    });

    // Graphique des commandes par statut (données fictives pour l'exemple)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Expédiée', 'Livrée', 'Annulée'],
            datasets: [{
                data: [30, 25, 35, 10],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
@endsection 