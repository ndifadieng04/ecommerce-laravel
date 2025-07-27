# API E-commerce Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentification
L'API utilise Laravel Sanctum pour l'authentification. Les routes protégées nécessitent un token Bearer.

## Endpoints

### Catégories

#### 1. Lister toutes les catégories
```
GET /categories
```

**Paramètres de requête :**
- `active` (boolean) - Filtrer par statut actif
- `sort_by` (string) - Champ de tri (name, created_at, etc.)
- `sort_order` (string) - Ordre de tri (asc, desc)
- `per_page` (integer) - Nombre d'éléments par page

**Exemple Postman :**
```
GET http://localhost:8000/api/v1/categories?active=true&sort_by=name&sort_order=asc&per_page=10
```

#### 2. Obtenir une catégorie spécifique
```
GET /categories/{id}
```

**Exemple Postman :**
```
GET http://localhost:8000/api/v1/categories/1
```

#### 3. Obtenir les produits d'une catégorie
```
GET /categories/{id}/products
```

**Paramètres de requête :**
- `active` (boolean) - Filtrer par statut actif
- `in_stock` (boolean) - Filtrer par disponibilité
- `min_price` (number) - Prix minimum
- `max_price` (number) - Prix maximum
- `sort_by` (string) - Champ de tri
- `sort_order` (string) - Ordre de tri
- `per_page` (integer) - Nombre d'éléments par page

**Exemple Postman :**
```
GET http://localhost:8000/api/v1/categories/1/products?active=true&in_stock=true&min_price=10&max_price=100
```

#### 4. Créer une catégorie (Protégé)
```
POST /categories
```

**Headers :**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body (JSON) :**
```json
{
    "name": "Nouvelle Catégorie",
    "description": "Description de la catégorie",
    "slug": "nouvelle-categorie",
    "is_active": true
}
```

#### 5. Modifier une catégorie (Protégé)
```
PUT /categories/{id}
```

**Headers :**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body (JSON) :**
```json
{
    "name": "Catégorie Modifiée",
    "description": "Nouvelle description",
    "is_active": false
}
```

#### 6. Supprimer une catégorie (Protégé)
```
DELETE /categories/{id}
```

**Headers :**
```
Authorization: Bearer {token}
```

### Produits

#### 1. Lister tous les produits
```
GET /products
```

**Paramètres de requête :**
- `category_id` (integer) - Filtrer par catégorie
- `active` (boolean) - Filtrer par statut actif
- `in_stock` (boolean) - Filtrer par disponibilité
- `min_price` (number) - Prix minimum
- `max_price` (number) - Prix maximum
- `sort_by` (string) - Champ de tri (name, price, created_at, etc.)
- `sort_order` (string) - Ordre de tri (asc, desc)
- `per_page` (integer) - Nombre d'éléments par page

**Exemple Postman :**
```
GET http://localhost:8000/api/v1/products?category_id=1&active=true&in_stock=true&min_price=10&max_price=500&sort_by=price&sort_order=asc
```

#### 2. Obtenir un produit spécifique
```
GET /products/{id}
```

**Exemple Postman :**
```
GET http://localhost:8000/api/v1/products/1
```

#### 3. Rechercher des produits
```
GET /products/search/{query}
```

**Exemple Postman :**
```
GET http://localhost:8000/api/v1/products/search/smartphone
```

#### 4. Créer un produit (Protégé)
```
POST /products
```

**Headers :**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Body (Form Data) :**
```
name: "Nouveau Produit"
description: "Description du produit"
price: 99.99
stock: 50
category_id: 1
is_active: true
image: [fichier image]
```

#### 5. Modifier un produit (Protégé)
```
PUT /products/{id}
```

**Headers :**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Body (Form Data) :**
```
name: "Produit Modifié"
price: 89.99
stock: 25
is_active: true
image: [fichier image optionnel]
```

#### 6. Supprimer un produit (Protégé)
```
DELETE /products/{id}
```

**Headers :**
```
Authorization: Bearer {token}
```

## Réponses

### Format de réponse standard
```json
{
    "success": true,
    "data": [...],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

### Format d'erreur
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["Le nom est requis"],
        "price": ["Le prix doit être un nombre positif"]
    }
}
```

## Codes de statut HTTP

- `200` - Succès
- `201` - Créé avec succès
- `400` - Requête invalide
- `401` - Non autorisé
- `404` - Ressource non trouvée
- `422` - Erreur de validation
- `500` - Erreur serveur

## Exemples de requêtes Postman

### Collection Postman
Vous pouvez importer cette collection dans Postman :

```json
{
    "info": {
        "name": "E-commerce API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Categories",
            "item": [
                {
                    "name": "Get All Categories",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/categories"
                    }
                },
                {
                    "name": "Get Category",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/categories/1"
                    }
                },
                {
                    "name": "Create Category",
                    "request": {
                        "method": "POST",
                        "url": "{{base_url}}/categories",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{token}}"
                            },
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"name\": \"Test Category\",\n    \"description\": \"Test description\",\n    \"is_active\": true\n}"
                        }
                    }
                }
            ]
        },
        {
            "name": "Products",
            "item": [
                {
                    "name": "Get All Products",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/products"
                    }
                },
                {
                    "name": "Get Product",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/products/1"
                    }
                },
                {
                    "name": "Search Products",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/products/search/smartphone"
                    }
                }
            ]
        }
    ],
    "variable": [
        {
            "key": "base_url",
            "value": "http://localhost:8000/api/v1"
        },
        {
            "key": "token",
            "value": "your_token_here"
        }
    ]
}
```

## Test de l'API

1. **Démarrer le serveur :**
   ```bash
   php artisan serve
   ```

2. **Exécuter les migrations :**
   ```bash
   php artisan migrate
   ```

3. **Seeder les données :**
   ```bash
   php artisan db:seed
   ```

4. **Tester avec Postman :**
   - Importer la collection
   - Tester les endpoints publics d'abord
   - Configurer l'authentification pour les endpoints protégés 