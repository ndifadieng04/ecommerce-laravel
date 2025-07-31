@extends('layouts.app')

@section('title', 'Changer le mot de passe - E-commerce')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                        </h2>
                        <a href="{{ route('dashboard') }}" class="btn btn-light" style="border-radius: 25px;">
                            <i class="fas fa-arrow-left me-2"></i>Retour
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

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="{{ route('password.change') }}">
                                @csrf

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" placeholder="Mot de passe actuel" required>
                                    <label for="current_password">
                                        <i class="fas fa-lock me-2"></i>Mot de passe actuel
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Nouveau mot de passe" required>
                                    <label for="password">
                                        <i class="fas fa-key me-2"></i>Nouveau mot de passe
                                    </label>
                                    <div class="password-strength mt-2" id="password-strength"></div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirmer le nouveau mot de passe" required>
                                    <label for="password_confirmation">
                                        <i class="fas fa-key me-2"></i>Confirmer le nouveau mot de passe
                                    </label>
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Exigences du mot de passe :</h6>
                                    <ul class="mb-0">
                                        <li>Au moins 8 caractères</li>
                                        <li>Au moins une lettre majuscule</li>
                                        <li>Au moins une lettre minuscule</li>
                                        <li>Au moins un chiffre</li>
                                        <li>Au moins un caractère spécial</li>
                                    </ul>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px;">
                                        <i class="fas fa-save me-2"></i>Changer le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.password-strength {
    font-size: 0.875rem;
}
.strength-weak { color: #dc3545; }
.strength-medium { color: #ffc107; }
.strength-strong { color: #198754; }
</style>

<script>
// Validation de la force du mot de passe
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    
    let strength = 0;
    let feedback = '';
    
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    switch(strength) {
        case 0:
        case 1:
            feedback = '<span class="strength-weak">Mot de passe très faible</span>';
            break;
        case 2:
            feedback = '<span class="strength-weak">Mot de passe faible</span>';
            break;
        case 3:
            feedback = '<span class="strength-medium">Mot de passe moyen</span>';
            break;
        case 4:
            feedback = '<span class="strength-strong">Mot de passe fort</span>';
            break;
        case 5:
            feedback = '<span class="strength-strong">Mot de passe très fort</span>';
            break;
    }
    
    strengthDiv.innerHTML = feedback;
});

// Validation de la confirmation du mot de passe
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (confirmation && password !== confirmation) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection 