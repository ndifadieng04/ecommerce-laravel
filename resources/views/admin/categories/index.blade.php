@extends('layouts.admin')

@section('title', 'Gestion des catégories - Administration')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags me-2"></i>
            Gestion des catégories
        </h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nouvelle catégorie
        </a>
    </div>

    <!-- Statistiques des catégories -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total catégories
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Catégories avec produits
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->where('products_count', '>', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Catégories vides
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->where('products_count', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Produits total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->sum('products_count') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des catégories -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Liste des catégories
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Produits</th>
                            <th>Créée le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="align-middle">
                                <strong>{{ $category->name }}</strong>
                            </td>
                            <td class="align-middle">
                                <code>{{ $category->slug }}</code>
                            </td>
                            <td class="align-middle">
                                @if($category->description)
                                    {{ Str::limit($category->description, 50) }}
                                @else
                                    <span class="text-muted">Aucune description</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @if($category->products_count > 0)
                                    <span class="badge bg-success">{{ $category->products_count }}</span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <small class="text-muted">{{ $category->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->products_count }})" 
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucune catégorie trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Formulaire de suppression caché -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deleteCategory(categoryId, categoryName, productsCount) {
    let message = 'Êtes-vous sûr de vouloir supprimer la catégorie "' + categoryName + '" ?';
    
    if (productsCount > 0) {
        message = 'ATTENTION : La catégorie "' + categoryName + '" contient ' + productsCount + ' produit(s). ' +
                 'Impossible de supprimer une catégorie qui contient des produits. ' +
                 'Veuillez d\'abord déplacer ou supprimer tous les produits de cette catégorie.';
        alert(message);
        return;
    }
    
    if (confirm(message)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/categories/${categoryId}`;
        form.submit();
    }
}
</script>
@endpush
@endsection 