@extends('layouts.app')

@section('title', 'Inscription - E-commerce')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Inscription
                    </h2>
                    <p class="mb-0 mt-2">Créez votre compte</p>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="Nom complet" 
                                   value="{{ old('name') }}" required>
                            <label for="name">
                                <i class="fas fa-user me-2"></i>Nom complet
                            </label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="Email" 
                                   value="{{ old('email') }}" required>
                            <label for="email">
                                <i class="fas fa-envelope me-2"></i>Adresse email
                            </label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Mot de passe" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                            <label for="password_confirmation">
                                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" class="text-decoration-none" style="color: #667eea;">conditions d'utilisation</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Déjà un compte ? 
                            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #667eea;">
                                Se connecter
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 