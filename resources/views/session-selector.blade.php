<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection de Session - E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .session-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .session-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .session-body {
            padding: 2rem;
        }
        .session-option {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .session-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
            text-decoration: none;
            color: inherit;
        }
        .session-option.client {
            border-color: #28a745;
        }
        .session-option.client:hover {
            border-color: #28a745;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }
        .session-option.admin {
            border-color: #dc3545;
        }
        .session-option.admin:hover {
            border-color: #dc3545;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
        }
        .back-link {
            color: white;
            text-decoration: none;
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 18px;
        }
        .back-link:hover {
            color: #f8f9fa;
        }
    </style>
</head>
<body>
    <a href="{{ route('products.index') }}" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Retour au site
    </a>

    <div class="session-card">
        <div class="session-header">
            <i class="fas fa-users-cog fa-3x mb-3"></i>
            <h3 class="mb-0">Choisir votre session</h3>
            <p class="mb-0 mt-2">Sélectionnez le type de compte avec lequel vous souhaitez vous connecter</p>
        </div>
        
        <div class="session-body">
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <a href="{{ route('switch.to.client') }}" class="session-option client">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-user fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Session Client</h5>
                        <p class="mb-0 text-muted">Accédez à votre espace client pour gérer vos commandes et votre profil</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('switch.to.admin') }}" class="session-option admin">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-cog fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Session Administrateur</h5>
                        <p class="mb-0 text-muted">Accédez à l'espace d'administration pour gérer le site</p>
                    </div>
                </div>
            </a>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Vous ne pouvez être connecté qu'avec un seul type de compte à la fois
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 