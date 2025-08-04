@extends('layouts.admin')

@section('title', 'Nouvelle catégorie - Administration')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2"></i>
            Nouvelle catégorie
        </h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>
                        Informations de la catégorie
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la catégorie *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Ex: Électronique, Vêtements, Livres...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Le nom sera affiché aux clients et utilisé pour organiser les produits.</div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (optionnel)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug') }}" 
                                   placeholder="Ex: electronique, vetements, livres...">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Laissez vide pour générer automatiquement à partir du nom. Utilisé dans les URLs.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Description optionnelle de la catégorie...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Description optionnelle pour expliquer le contenu de cette catégorie.</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Créer la catégorie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Conseils</h6>
                        <ul class="mb-0">
                            <li>Choisissez un nom clair et descriptif</li>
                            <li>Utilisez des catégories logiques pour organiser vos produits</li>
                            <li>Le slug sera généré automatiquement si laissé vide</li>
                            <li>Une description aide les clients à comprendre la catégorie</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                        <ul class="mb-0">
                            <li>Une fois créée, la catégorie pourra être utilisée pour classer les produits</li>
                            <li>Vous pourrez modifier le nom et la description plus tard</li>
                            <li>Une catégorie ne peut être supprimée que si elle ne contient aucun produit</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Génération automatique du slug
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slugField = document.getElementById('slug');
    
    if (slugField.value === '') {
        slugField.value = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }
});
</script>
@endpush
@endsection 