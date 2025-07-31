# API Authentification Documentation

## Base URL
```
http://localhost:8000/api/v1/auth
```

## Authentification
L'API utilise Laravel Sanctum pour l'authentification. Les routes protégées nécessitent un token Bearer.

## Endpoints

### 1. Inscription d'un utilisateur
```
POST /register
```

**Body (JSON) :**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "phone": "0123456789",
    "birth_date": "1990-01-01",
    "gender": "male",
    "newsletter_subscription": true
}
```

**Réponse (201) :**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "0123456789",
            "birth_date": "1990-01-01",
            "gender": "male",
            "newsletter_subscription": true,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 2. Connexion d'un utilisateur
```
POST /login
```

**Body (JSON) :**
```json
{
    "email": "john@example.com",
    "password": "Password123!"
}
```

**Réponse (200) :**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "0123456789",
            "birth_date": "1990-01-01",
            "gender": "male",
            "newsletter_subscription": true,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 3. Déconnexion (Protégé)
```
POST /logout
```

**Headers :**
```
Authorization: Bearer {token}
```

**Réponse (200) :**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### 4. Obtenir les informations de l'utilisateur (Protégé)
```
GET /user
```

**Headers :**
```
Authorization: Bearer {token}
```

**Réponse (200) :**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "0123456789",
            "birth_date": "1990-01-01",
            "gender": "male",
            "shipping_address": "123 Rue de la Paix, 75001 Paris",
            "billing_address": "123 Rue de la Paix, 75001 Paris",
            "newsletter_subscription": true,
            "total_spent": "1250.50",
            "orders_count": 5,
            "last_order_at": "2024-01-01T00:00:00.000000Z",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "stats": {
            "orders_count": 5,
            "total_spent": "1,250.50 €",
            "has_orders": true,
            "newsletter_subscribed": true
        }
    }
}
```

### 5. Mettre à jour le profil (Protégé)
```
PUT /profile
```

**Headers :**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body (JSON) :**
```json
{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "phone": "0987654321",
    "birth_date": "1990-01-01",
    "gender": "male",
    "shipping_address": "456 Avenue des Champs, 75008 Paris",
    "billing_address": "456 Avenue des Champs, 75008 Paris",
    "newsletter_subscription": false
}
```

**Réponse (200) :**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Updated",
            "email": "john.updated@example.com",
            "phone": "0987654321",
            "birth_date": "1990-01-01",
            "gender": "male",
            "shipping_address": "456 Avenue des Champs, 75008 Paris",
            "billing_address": "456 Avenue des Champs, 75008 Paris",
            "newsletter_subscription": false,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

### 6. Changer le mot de passe (Protégé)
```
POST /change-password
```

**Headers :**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body (JSON) :**
```json
{
    "current_password": "Password123!",
    "password": "NewPassword123!",
    "password_confirmation": "NewPassword123!"
}
```

**Réponse (200) :**
```json
{
    "success": true,
    "message": "Password changed successfully"
}
```

### 7. Rafraîchir le token (Protégé)
```
POST /refresh
```

**Headers :**
```
Authorization: Bearer {token}
```

**Réponse (200) :**
```json
{
    "success": true,
    "message": "Token refreshed successfully",
    "data": {
        "token": "2|def456...",
        "token_type": "Bearer"
    }
}
```

## Codes de statut HTTP

- `200` - Succès
- `201` - Créé avec succès
- `400` - Requête invalide
- `401` - Non autorisé
- `422` - Erreur de validation
- `500` - Erreur serveur

## Exemples de requêtes Postman

### Collection Postman pour l'authentification
```json
{
    "info": {
        "name": "E-commerce Auth API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Register",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/register",
                "header": [
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"name\": \"Test User\",\n    \"email\": \"test@example.com\",\n    \"password\": \"Password123!\",\n    \"password_confirmation\": \"Password123!\",\n    \"phone\": \"0123456789\",\n    \"birth_date\": \"1990-01-01\",\n    \"gender\": \"male\",\n    \"newsletter_subscription\": true\n}"
                }
            }
        },
        {
            "name": "Login",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/login",
                "header": [
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"email\": \"test@example.com\",\n    \"password\": \"Password123!\"\n}"
                }
            }
        },
        {
            "name": "Get User",
            "request": {
                "method": "GET",
                "url": "{{base_url}}/auth/user",
                "header": [
                    {
                        "key": "Authorization",
                        "value": "Bearer {{token}}"
                    }
                ]
            }
        },
        {
            "name": "Update Profile",
            "request": {
                "method": "PUT",
                "url": "{{base_url}}/auth/profile",
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
                    "raw": "{\n    \"name\": \"Updated Name\",\n    \"email\": \"updated@example.com\",\n    \"phone\": \"0987654321\",\n    \"newsletter_subscription\": false\n}"
                }
            }
        },
        {
            "name": "Change Password",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/change-password",
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
                    "raw": "{\n    \"current_password\": \"Password123!\",\n    \"password\": \"NewPassword123!\",\n    \"password_confirmation\": \"NewPassword123!\"\n}"
                }
            }
        },
        {
            "name": "Logout",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/logout",
                "header": [
                    {
                        "key": "Authorization",
                        "value": "Bearer {{token}}"
                    }
                ]
            }
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

## Test de l'API d'authentification

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

4. **Utilisateurs de test créés :**
   - Email: `admin@example.com` / Mot de passe: `password123`
   - Email: `john@example.com` / Mot de passe: `password123`
   - Email: `jane@example.com` / Mot de passe: `password123`

5. **Tester avec Postman :**
   - Importer la collection
   - Tester l'inscription d'abord
   - Tester la connexion
   - Utiliser le token retourné pour les requêtes protégées

## Sécurité

- Les mots de passe sont hashés avec bcrypt
- Les tokens Sanctum expirent automatiquement
- Validation stricte des données d'entrée
- Protection CSRF pour les routes web
- Rate limiting sur les routes API 