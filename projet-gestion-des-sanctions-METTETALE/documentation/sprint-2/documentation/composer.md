# Documentation Composer - Gestionnaire de dépendances PHP

[← Retour aux travaux à réaliser](travail-a-faire.md)

## Vue d'ensemble

Ce document décrit la mise en place et la configuration de Composer dans le projet PHP MVC Films, ainsi que l'implémentation de l'autoloading PSR-4 pour une meilleure organisation du code.

## Qu'est-ce que Composer ?

Composer est le gestionnaire de dépendances standard pour PHP. Il permet de :
- Gérer les dépendances externes (bibliothèques, frameworks)
- Implémenter l'autoloading automatique des classes
- Organiser le code selon les standards PSR-4
- Faciliter la maintenance et le déploiement

## Configuration initiale

### Initialisation du projet

Dans le répertoire racine du projet :

```bash
composer init
```

Cette commande génère un fichier `composer.json` avec la configuration de base.

## Configuration du fichier composer.json

### Structure de base

```json
{
    "name": "franck/php-mvc-films-principal",
    "type": "project",
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Franck LAMY",
            "email": "exemple@email.com"
        }
    ],
    "require": {}
}
```

### Explication des sections

- **name** : Identifiant unique du projet (format vendor/package)
- **type** : Type de projet ("project" pour une application)
- **autoload** : Configuration de l'autoloading PSR-4
- **authors** : Informations sur les développeurs
- **require** : Dépendances externes (vide pour ce projet)

## Implémentation de l'autoloading PSR-4

### Principe PSR-4

PSR-4 définit un standard pour l'autoloading des classes basé sur les namespaces :
- Le namespace `App\` correspond au dossier `src/`
- Les sous-namespaces correspondent aux sous-dossiers
- Les noms de classes correspondent aux noms de fichiers

### Structure des namespaces

```
src/
├── Http/
│   ├── Request.php     → App\Http\Request
│   └── Response.php     → App\Http\Response
├── Routing/
│   └── Router.php       → App\Routing\Router
└── config/
    └── database.php     → App\Config\Database (optionnel)
```

## Mise à jour des classes existantes

### 1. Ajout des namespaces

Chaque classe a été mise à jour avec son namespace approprié :

#### Classe Router
```php
<?php

namespace App\Routing;

use App\Http\Request;
use App\Http\Response;

class Router {
    // ... contenu de la classe
}
```

#### Classe Request
```php
<?php

namespace App\Http;

class Request {
    // ... contenu de la classe
}
```

#### Classe Response
```php
<?php

namespace App\Http;

class Response {
    // ... contenu de la classe
}
```

### 2. Mise à jour des imports

Les fichiers utilisant ces classes ont été mis à jour avec les `use` statements :

```php
<?php

use App\Http\Request;
use App\Http\Response;

// ... reste du code
```

## Modification du point d'entrée

### Avant (avec require_once)
```php
<?php

// Inclusion des classes Request, Response et Router
require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ . '/../src/Http/Response.php';
require_once __DIR__ . '/../src/Routing/Router.php';

// Inclusion du contrôleur des films
require_once __DIR__ . '/../src/controllers/filmController.php';

// Création des objets
$request = new Request();
$response = new Response();
$router = new Router($request, $response);
```

### Après (avec autoloading)
```php
<?php

// Chargement de l'autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Inclusion du contrôleur des films
require_once __DIR__ . '/../src/controllers/filmController.php';

// Création des objets avec namespaces complets
$request = new App\Http\Request();
$response = new App\Http\Response();
$router = new App\Routing\Router($request, $response);
```

## Génération de l'autoloader

### Commande de génération
```bash
composer dump-autoload
```

Cette commande :
- Analyse tous les fichiers PHP du projet
- Génère le fichier `vendor/autoload.php`
- Crée les mappings PSR-4 nécessaires
- Optimise les performances de chargement

### Fichiers générés
```
vendor/
├── autoload.php              # Point d'entrée principal
├── composer/
│   ├── autoload_classmap.php    # Mapping des classes
│   ├── autoload_namespaces.php   # Mapping des namespaces
│   ├── autoload_psr4.php         # Mapping PSR-4
│   └── autoload_real.php         # Autoloader optimisé
└── ClassLoader.php           # Classe de chargement
```

## Avantages de cette approche

### 1. Organisation du code
- Structure claire et logique
- Respect des standards PSR-4
- Séparation des responsabilités

### 2. Performance
- Chargement automatique des classes
- Pas de require_once multiples
- Optimisation du cache

### 3. Maintenabilité
- Code plus lisible
- Gestion centralisée des dépendances
- Facilité d'ajout de nouvelles classes

### 4. Évolutivité
- Prêt pour l'ajout de dépendances externes
- Compatible avec les frameworks PHP modernes
- Facilite les tests unitaires

## Commandes utiles

### Génération de l'autoloader
```bash
# Génération complète
composer dump-autoload

# Génération optimisée (production)
composer dump-autoload --optimize

# Génération avec autorévision (développement)
composer dump-autoload --dev
```

### Vérification de la configuration
```bash
# Validation du composer.json
composer validate

# Affichage des informations du projet
composer show

# Mise à jour des dépendances
composer update
```

## Dépannage

### Problèmes courants

#### 1. Classe non trouvée
```php
Fatal error: Class 'App\Http\Request' not found
```

**Solution** : Vérifier que :
- Le namespace est correct dans la classe
- L'autoloader est chargé (`require_once 'vendor/autoload.php'`)
- Le fichier `composer.json` contient la bonne configuration PSR-4

#### 2. Autoloader non généré
```php
Warning: require_once(vendor/autoload.php): failed to open stream
```

**Solution** :
```bash
composer install
# ou
composer dump-autoload
```

#### 3. Cache de classes
Si les modifications ne sont pas prises en compte :
```bash
composer dump-autoload --optimize
```

## Conclusion

L'implémentation de Composer et de l'autoloading PSR-4 dans ce projet apporte :

1. **Structure professionnelle** : Code organisé selon les standards
2. **Performance améliorée** : Chargement automatique optimisé
3. **Maintenabilité** : Facilité de maintenance et d'évolution
4. **Évolutivité** : Prêt pour l'ajout de dépendances externes

Cette base solide permet une évolution naturelle vers des architectures plus complexes tout en conservant la simplicité du code existant.
