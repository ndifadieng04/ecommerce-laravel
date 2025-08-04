@extends('layouts.admin')

@section('title', 'Gestion des clients')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des clients</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>

    <!-- Statistiques clients -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total clients</h6>
                            <h3 class="mb-0">{{ number_format($customers->total()) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Clients actifs</h6>
                            <h3 class="mb-0">{{ number_format($customers->where('orders_count', '>', 0)->count()) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Nouveaux ce mois</h6>
                            <h3 class="mb-0">{{ number_format($customers->where('created_at', '>=', now()->startOfMonth())->count()) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">CA total clients</h6>
                            <h3 class="mb-0">{{ number_format($customers->sum('orders_sum_total_amount'), 0, ',', ' ') }} €</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des clients -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Inscrit le</th>
                            <th>Commandes</th>
                            <th>CA total</th>
                            <th>Dernière commande</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $customer->name }}</strong><br>
                                        <small class="text-muted">ID: {{ $customer->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $customer->orders_count }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($customer->orders_sum_total_amount ?? 0, 2, ',', ' ') }} €</strong>
                            </td>
                            <td>
                                @if($customer->orders_count > 0)
                                    {{ $customer->orders->first()->created_at->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Aucune commande</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCustomerModal{{ $customer->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal d'édition -->
                        <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier le client</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" name="name" class="form-control" 
                                                       value="{{ $customer->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" 
                                                       value="{{ $customer->email }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun client trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customers->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $customers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 