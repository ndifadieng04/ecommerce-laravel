# E-commerce Laravel

Un système e-commerce complet développé avec Laravel 10, incluant une API REST et une interface web moderne.

## 🚀 Fonctionnalités

### ✅ Système d'authentification complet
- **Inscription/Connexion** avec validation avancée
- **Gestion de profil** utilisateur complète
- **Changement de mot de passe** sécurisé
- **API REST** avec authentification Sanctum
- **Interface web** moderne et responsive

### ✅ Gestion des produits et catégories
- **CRUD complet** pour les produits et catégories
- **Upload d'images** pour les produits
- **Recherche et filtrage** avancés
- **API REST** pour l'intégration mobile

### ✅ Système de commandes
- **Panier d'achat** avec session
- **Processus de commande** complet
- **Gestion des paiements** (structure prête)
- **Historique des commandes**

### ✅ Interface utilisateur
- **Design moderne** avec Bootstrap 5
- **Responsive** pour tous les appareils
- **Navigation intuitive** avec menu utilisateur
- **Tableau de bord** avec statistiques

## 📋 Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL/MariaDB
- Node.js (pour les assets)

## 🛠️ Installation

1. **Cloner le projet :**
   ```bash
   git clone <repository-url>
   cd ecommerce-laravel
   ```

2. **Installer les dépendances :**
   ```bash
   composer install
   npm install
   ```

3. **Configuration :**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurer la base de données dans `.env` :**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce_laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Exécuter les migrations et seeders :**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Démarrer le serveur :**
   ```bash
   php artisan serve
   ```

## 👥 Utilisateurs de test

Après avoir exécuté les seeders, vous pouvez vous connecter avec :

- **Admin :** `admin@example.com` / `password123`
- **Client 1 :** `john@example.com` / `password123`
- **Client 2 :** `jane@example.com` / `password123`

## 🌐 Routes principales

### Interface web
- `/` - Page d'accueil
- `/login` - Connexion
- `/register` - Inscription
- `/dashboard` - Tableau de bord (protégé)
- `/profile` - Profil utilisateur (protégé)
- `/change-password` - Changer le mot de passe (protégé)

### API REST
- `GET /api/v1/categories` - Lister les catégories
- `GET /api/v1/products` - Lister les produits
- `POST /api/v1/auth/register` - Inscription API
- `POST /api/v1/auth/login` - Connexion API
- `GET /api/v1/auth/user` - Profil utilisateur (protégé)

## 📚 Documentation

- [Documentation API principale](API_DOCUMENTATION.md)
- [Documentation API Authentification](AUTH_API_DOCUMENTATION.md)

## 🔧 Structure du projet

```
app/
├── Http/Controllers/
│   ├── Auth/AuthController.php     # Contrôleur d'authentification web
│   ├── Api/AuthController.php      # Contrôleur d'authentification API
│   ├── Api/CategoryController.php  # Contrôleur API catégories
│   ├── Api/ProductController.php   # Contrôleur API produits
│   └── ...
├── Models/
│   ├── User.php                    # Modèle utilisateur
│   ├── Product.php                 # Modèle produit
│   ├── Category.php                # Modèle catégorie
│   ├── Order.php                   # Modèle commande
│   └── ...
└── ...

resources/views/
├── auth/
│   ├── login.blade.php             # Page de connexion
│   ├── register.blade.php          # Page d'inscription
│   ├── dashboard.blade.php         # Tableau de bord
│   ├── profile.blade.php           # Profil utilisateur
│   └── change-password.blade.php   # Changement de mot de passe
└── ...

routes/
├── web.php                         # Routes web
└── api.php                         # Routes API
```

## 🚀 Prochaines étapes

1. **Interface d'administration** pour gérer les produits/catégories
2. **Système de panier avancé** avec persistance
3. **Processus de commande** complet avec paiement
4. **Notifications email** pour les commandes
5. **Système de reviews** et notes
6. **Gestion des stocks** en temps réel

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
