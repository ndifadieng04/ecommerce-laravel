@extends('layouts.app')

@section('title', 'Mon profil - E-commerce')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Mon profil
                        </h2>
                        <a href="{{ route('dashboard') }}" class="btn btn-light" style="border-radius: 25px;">
                            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations personnelles</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" placeholder="Nom complet" 
                                                   value="{{ old('name', $user->name) }}" required>
                                            <label for="name">
                                                <i class="fas fa-user me-2"></i>Nom complet
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" placeholder="Email" 
                                                   value="{{ old('email', $user->email) }}" required>
                                            <label for="email">
                                                <i class="fas fa-envelope me-2"></i>Adresse email
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" placeholder="Téléphone" 
                                                   value="{{ old('phone', $user->phone) }}">
                                            <label for="phone">
                                                <i class="fas fa-phone me-2"></i>Téléphone
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                                   id="birth_date" name="birth_date" 
                                                   value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                                            <label for="birth_date">
                                                <i class="fas fa-calendar me-2"></i>Date de naissance
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender">
                                        <option value="">Sélectionner...</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Homme</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femme</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    <label for="gender">
                                        <i class="fas fa-venus-mars me-2"></i>Genre
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Adresses</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                              id="shipping_address" name="shipping_address" 
                                              placeholder="Adresse de livraison" style="height: 100px">{{ old('shipping_address', $user->shipping_address) }}</textarea>
                                    <label for="shipping_address">
                                        <i class="fas fa-truck me-2"></i>Adresse de livraison
                                    </label>
                                </div>

                                <div class="form-floating">
                                    <textarea class="form-control @error('billing_address') is-invalid @enderror" 
                                              id="billing_address" name="billing_address" 
                                              placeholder="Adresse de facturation" style="height: 100px">{{ old('billing_address', $user->billing_address) }}</textarea>
                                    <label for="billing_address">
                                        <i class="fas fa-file-invoice me-2"></i>Adresse de facturation
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Préférences</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter_subscription" 
                                           name="newsletter_subscription" {{ old('newsletter_subscription', $user->newsletter_subscription) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="newsletter_subscription">
                                        <i class="fas fa-envelope-open me-2"></i>
                                        S'abonner à la newsletter pour recevoir nos offres spéciales
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('change-password') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                <i class="fas fa-key me-2"></i>Changer le mot de passe
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 