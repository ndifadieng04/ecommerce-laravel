<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
        }
        .endpoint {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        .method {
            font-weight: bold;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        .method.get { background-color: #d1ecf1; color: #0c5460; }
        .method.post { background-color: #d4edda; color: #155724; }
        .method.put { background-color: #fff3cd; color: #856404; }
        .method.delete { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">API Documentation - E-commerce</h1>
        
        <div class="alert alert-info">
            <strong>Base URL:</strong> <code>http://localhost:8000/api/v1</code>
        </div>

        <h2>Endpoints</h2>

        <div class="endpoint">
            <h3>Catégories</h3>
            
            <div class="mb-3">
                <span class="method get">GET</span>
                <code>/categories</code>
                <p class="mt-2">Lister toutes les catégories</p>
                <pre>GET http://localhost:8000/api/v1/categories?active=true&sort_by=name&per_page=10</pre>
            </div>

            <div class="mb-3">
                <span class="method get">GET</span>
                <code>/categories/{id}</code>
                <p class="mt-2">Obtenir une catégorie spécifique</p>
                <pre>GET http://localhost:8000/api/v1/categories/1</pre>
            </div>

            <div class="mb-3">
                <span class="method get">GET</span>
                <code>/categories/{id}/products</code>
                <p class="mt-2">Obtenir les produits d'une catégorie</p>
                <pre>GET http://localhost:8000/api/v1/categories/1/products?active=true&in_stock=true</pre>
            </div>

            <div class="mb-3">
                <span class="method post">POST</span>
                <code>/categories</code>
                <p class="mt-2">Créer une catégorie (Protégé)</p>
                <pre>POST http://localhost:8000/api/v1/categories
Headers: Authorization: Bearer {token}
Body: {
    "name": "Nouvelle Catégorie",
    "description": "Description",
    "is_active": true
}</pre>
            </div>
        </div>

        <div class="endpoint">
            <h3>Produits</h3>
            
            <div class="mb-3">
                <span class="method get">GET</span>
                <code>/products</code>
                <p class="mt-2">Lister tous les produits</p>
                <pre>GET http://localhost:8000/api/v1/products?category_id=1&active=true&min_price=10&max_price=100</pre>
            </div>

            <div class="mb-3">
                <span class="method get">GET</span>
                <code>/products/{id}</code>
                <p class="mt-2">Obtenir un produit spécifique</p>
                <pre>GET http://localhost:8000/api/v1/products/1</pre>
            </div>

            <div class="mb-3">
                <span class="method get">GET</span>
                <code>/products/search/{query}</code>
                <p class="mt-2">Rechercher des produits</p>
                <pre>GET http://localhost:8000/api/v1/products/search/smartphone</pre>
            </div>

            <div class="mb-3">
                <span class="method post">POST</span>
                <code>/products</code>
                <p class="mt-2">Créer un produit (Protégé)</p>
                <pre>POST http://localhost:8000/api/v1/products
Headers: Authorization: Bearer {token}
Body (Form Data):
- name: "Nouveau Produit"
- description: "Description"
- price: 99.99
- stock: 50
- category_id: 1
- image: [fichier]</pre>
            </div>
        </div>

        <h2>Codes de statut HTTP</h2>
        <ul>
            <li><strong>200</strong> - Succès</li>
            <li><strong>201</strong> - Créé avec succès</li>
            <li><strong>400</strong> - Requête invalide</li>
            <li><strong>401</strong> - Non autorisé</li>
            <li><strong>404</strong> - Ressource non trouvée</li>
            <li><strong>422</strong> - Erreur de validation</li>
            <li><strong>500</strong> - Erreur serveur</li>
        </ul>

        <h2>Test avec Postman</h2>
        <p>Importez cette collection dans Postman pour tester l'API :</p>
        <pre>{
    "info": {
        "name": "E-commerce API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Get Categories",
            "request": {
                "method": "GET",
                "url": "http://localhost:8000/api/v1/categories"
            }
        },
        {
            "name": "Get Products",
            "request": {
                "method": "GET",
                "url": "http://localhost:8000/api/v1/products"
            }
        }
    ]
}</pre>

        <div class="alert alert-warning mt-4">
            <strong>Note :</strong> Les endpoints POST, PUT et DELETE nécessitent une authentification avec un token Bearer.
        </div>
    </div>
</body>
</html> 