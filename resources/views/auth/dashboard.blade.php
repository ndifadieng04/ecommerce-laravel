@extends('layouts.app')

@section('title', 'Tableau de bord - E-commerce')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Menu
                    </h5>
                </div>
                <div class="card-body">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('dashboard') }}" style="color: #667eea; border-radius: 10px; margin-bottom: 0.5rem;">
                            <i class="fas fa-home me-2"></i>Tableau de bord
                        </a>
                        <a class="nav-link" href="{{ route('profile') }}" style="color: #6c757d; border-radius: 10px; margin-bottom: 0.5rem;">
                            <i class="fas fa-user-edit me-2"></i>Mon profil
                        </a>
                        <a class="nav-link" href="{{ route('change-password') }}" style="color: #6c757d; border-radius: 10px; margin-bottom: 0.5rem;">
                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                        </a>
                        <a class="nav-link" href="{{ route('products.index') }}" style="color: #6c757d; border-radius: 10px; margin-bottom: 0.5rem;">
                            <i class="fas fa-store me-2"></i>Boutique
                        </a>
                        <a class="nav-link" href="{{ route('orders.index') }}" style="color: #6c757d; border-radius: 10px; margin-bottom: 0.5rem;">
                            <i class="fas fa-shopping-bag me-2"></i>Mes commandes
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                    </h2>
                </div>
                <div class="card-body">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-0">{{ $user->orders_count ?? 0 }}</h4>
                                            <p class="mb-0">Commandes</p>
                                        </div>
                                        <i class="fas fa-shopping-bag fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-0">{{ $user->formatted_total_spent ?? '0,00 €' }}</h4>
                                            <p class="mb-0">Total dépensé</p>
                                        </div>
                                        <i class="fas fa-euro-sign fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-0">{{ $user->email_verified_at ? 'Oui' : 'Non' }}</h4>
                                            <p class="mb-0">Email vérifié</p>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations du profil -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user me-2"></i>Informations personnelles</h5>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p><strong>Nom :</strong> {{ $user->name }}</p>
                                    <p><strong>Email :</strong> {{ $user->email }}</p>
                                    <p><strong>Téléphone :</strong> {{ $user->phone ?: 'Non renseigné' }}</p>
                                    <p><strong>Genre :</strong> {{ $user->gender_label ?? 'Non renseigné' }}</p>
                                    <p><strong>Date de naissance :</strong> {{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'Non renseignée' }}</p>
                                    <p><strong>Newsletter :</strong> {{ $user->isNewsletterSubscribed() ? 'Abonné' : 'Non abonné' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-map-marker-alt me-2"></i>Adresses</h5>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p><strong>Adresse de livraison :</strong></p>
                                    <p class="text-muted">{{ $user->shipping_address ?: 'Non renseignée' }}</p>
                                    <p><strong>Adresse de facturation :</strong></p>
                                    <p class="text-muted">{{ $user->billing_address ?: 'Non renseignée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section d'accueil -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Bienvenue sur votre tableau de bord !</h5>
                                <p class="text-muted">Commencez vos achats pour voir vos statistiques ici</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                    <i class="fas fa-store me-2"></i>Aller à la boutique
                                </a>
                                <a href="{{ route('orders.index') }}">Mes commandes</a>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
    <i class="fa fa-list"></i> Mes commandes
</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 