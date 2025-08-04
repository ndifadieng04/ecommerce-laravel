@extends('layouts.admin')

@section('title', 'Modifier la catégorie - Administration')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>
            Modifier la catégorie
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
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la catégorie *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required 
                                   placeholder="Ex: Électronique, Vêtements, Livres...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Le nom sera affiché aux clients et utilisé pour organiser les produits.</div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (optionnel)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug', $category->slug) }}" 
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
                                      placeholder="Description optionnelle de la catégorie...">{{ old('description', $category->description) }}</textarea>
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
                                Mettre à jour
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
                        Informations de la catégorie
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Créée le :</strong><br>
                        <span class="text-muted">{{ $category->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Dernière modification :</strong><br>
                        <span class="text-muted">{{ $category->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Nombre de produits :</strong><br>
                        <span class="badge bg-secondary">{{ $category->products_count ?? 0 }}</span>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Conseils</h6>
                        <ul class="mb-0">
                            <li>Modifiez le nom avec précaution si des produits y sont associés</li>
                            <li>Le slug peut être modifié pour améliorer les URLs</li>
                            <li>Une description claire aide les clients</li>
                        </ul>
                    </div>

                    @if(($category->products_count ?? 0) > 0)
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Attention</h6>
                        <p class="mb-0">Cette catégorie contient {{ $category->products_count }} produit(s). 
                        La modification du nom peut affecter l'affichage des produits.</p>
                    </div>
                    @endif
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