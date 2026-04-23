# Énoncé de Travail : Implémentation d'une Classe Router pour la Gestion du Routage HTTP

[← Retour aux travaux à réaliser](travail-a-faire.md)

## 📋 Contexte et Objectif

Dans le cadre de l'évolution de l'application PHP-MVC-Films vers une architecture MVC plus évoluée, il est nécessaire de créer une classe dédiée au routage des requêtes HTTP. Cette approche permet d'encapsuler la logique de routage et d'améliorer la maintenabilité, la testabilité et la flexibilité du code en séparant clairement les responsabilités.

### Objectifs pédagogiques
- Comprendre le principe de routage dans une architecture MVC
- Maîtriser la création d'une classe de routage flexible et extensible
- Intégrer cette classe dans l'architecture MVC existante
- Améliorer la séparation des responsabilités dans l'application
- Comprendre la gestion des erreurs HTTP et des méthodes non autorisées

## 🎯 Mission

Vous devez créer une classe `Router` qui gère le routage des requêtes HTTP et remplacer la logique de routage actuellement présente dans le fichier `index.php`. Cette classe doit permettre d'ajouter facilement de nouvelles routes et de gérer les erreurs de routage de manière centralisée.

## 📁 Structure à respecter

Créez la classe `Router` dans le fichier : `src/Routing/Router.php`

## 🔧 Spécifications de la classe Router

### Propriétés privées
La classe doit contenir les propriétés privées suivantes :
- `$routes` : tableau des routes définies
- `$request` : objet Request pour récupérer les informations de la requête
- `$response` : objet Response pour gérer la réponse HTTP

### Constructeur
Le constructeur doit recevoir les objets Request et Response en paramètres et les stocker dans les propriétés correspondantes.

## 📝 Méthodes à implémenter

### 1. Méthodes de gestion des routes

#### `addRoute($action, $fonction, $methodes = ['GET'])`
- **Objectif** : Ajoute une route au tableau des routes
- **Signature** : `public function addRoute($action, $fonction, $methodes = ['GET'])`
- **Paramètres** :
  - `$action` : Nom de l'action (ex: 'index', 'show', 'create')
  - `$fonction` : Nom de la fonction à appeler dans le contrôleur
  - `$methodes` : Méthodes HTTP autorisées (ex: ['GET'], ['GET', 'POST'])
- **Retour** : Instance de Router pour le chaînage de méthodes
- **Exemple d'utilisation** :
  ```php
  $router = new Router($request, $response);
  $router->addRoute('index', 'indexFilms', ['GET'])
         ->addRoute('create', 'createFilm', ['GET', 'POST'])
         ->addRoute('edit', 'editFilm', ['GET', 'POST']);
  ```

### 2. Méthode principale de traitement

#### `handleRequest()`
- **Objectif** : Traite la requête courante et exécute la route correspondante
- **Signature** : `public function handleRequest()`
- **Comportement** :
  1. Récupère la méthode HTTP et l'action depuis l'objet Request
  2. Vérifie si la route existe
  3. Vérifie si la méthode HTTP est autorisée pour cette route
  4. Vérifie que la fonction existe
  5. Exécute la fonction correspondante en passant Request et Response
  6. Envoie la réponse
- **Exemple d'utilisation** :
  ```php
  $router = new Router($request, $response);
  $router->addRoute('index', 'indexFilms', ['GET']);
  $router->handleRequest(); // Traite la requête courante
  ```

### 3. Méthodes de gestion d'erreurs

#### `handleNotFound()`
- **Objectif** : Gère le cas où aucune route n'est trouvée
- **Signature** : `private function handleNotFound()`
- **Comportement** : Redirige vers l'action 'index' par défaut
- **Exemple d'utilisation** :
  ```php
  // Appelée automatiquement par handleRequest() si la route n'existe pas
  ```

#### `handleMethodNotAllowed($methodesAutorisees)`
- **Objectif** : Gère le cas où la méthode HTTP n'est pas autorisée
- **Signature** : `private function handleMethodNotAllowed($methodesAutorisees)`
- **Paramètres** :
  - `$methodesAutorisees` : Tableau des méthodes autorisées pour cette route
- **Comportement** : Retourne une erreur 405 avec la liste des méthodes autorisées
- **Exemple d'utilisation** :
  ```php
  // Appelée automatiquement par handleRequest() si la méthode n'est pas autorisée
  ```

#### `handleFunctionNotFound()`
- **Objectif** : Gère le cas où la fonction du contrôleur n'existe pas
- **Signature** : `private function handleFunctionNotFound()`
- **Comportement** : Redirige vers l'action 'index' par défaut
- **Exemple d'utilisation** :
  ```php
  // Appelée automatiquement par handleRequest() si la fonction n'existe pas
  ```

### 4. Méthodes utilitaires

#### `getRoutes()`
- **Objectif** : Retourne toutes les routes définies (utile pour le debug)
- **Signature** : `public function getRoutes()`
- **Retour** : Tableau des routes
- **Exemple d'utilisation** :
  ```php
  $router = new Router($request, $response);
  $router->addRoute('index', 'indexFilms', ['GET']);
  $routes = $router->getRoutes(); // Retourne le tableau des routes
  ```

#### `hasRoute($action)`
- **Objectif** : Vérifie si une route existe
- **Signature** : `public function hasRoute($action)`
- **Paramètres** :
  - `$action` : Nom de l'action à vérifier
- **Retour** : `true` si la route existe, `false` sinon
- **Exemple d'utilisation** :
  ```php
  $router = new Router($request, $response);
  $router->addRoute('index', 'indexFilms', ['GET']);
  
  if ($router->hasRoute('index')) {
      // La route 'index' existe
  }
  ```

## 🔄 Intégration dans l'application

### 1. Modification du routeur (`public/index.php`)

Modifiez le fichier `public/index.php` pour :
- Inclure la classe Router
- Créer une instance de Router avec Request et Response
- Définir toutes les routes de l'application
- Appeler `handleRequest()` pour traiter la requête

**Exemple de modification** :
```php
// Inclusion des classes Request, Response et Router
require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ . '/../src/Http/Response.php';
require_once __DIR__ . '/../src/Routing/Router.php';

// Inclusion du contrôleur des films
require_once __DIR__ . '/../src/controllers/filmController.php';

// Création des objets Request, Response et Router
$request = new Request();
$response = new Response();
$router = new Router($request, $response);

// Définition de toutes les routes de l'application
$router->addRoute('index', 'indexFilms', ['GET'])
       ->addRoute('show', 'showFilm', ['GET'])
       ->addRoute('create', 'createFilm', ['GET', 'POST'])
       ->addRoute('edit', 'editFilm', ['GET', 'POST'])
       ->addRoute('delete', 'deleteFilm', ['GET'])
       ->addRoute('search', 'searchFilms', ['GET']);

// Ajout de routes personnalisées supplémentaires
// $router->addRoute('about', 'aboutPage', ['GET']);

// Traitement de la requête courante
$router->handleRequest();
```

### 2. Avantages de cette approche

> **💡 Important : Séparation des responsabilités**
> 
> La classe Router permet de :
> - **Centraliser le routage** : Toute la logique de routage est dans une seule classe
> - **Faciliter l'ajout de routes** : Ajouter une nouvelle route ne nécessite qu'une ligne
> - **Gérer les erreurs** : Les erreurs de routage sont gérées de manière cohérente
> - **Améliorer la lisibilité** : Le fichier index.php devient plus simple et lisible
> - **Faciliter les tests** : La logique de routage peut être testée indépendamment

## ✅ Critères de validation

Votre implémentation sera validée selon les critères suivants :

1. **Structure** : La classe Router est correctement placée dans `src/Routing/Router.php`
2. **Fonctionnalité** : Toutes les méthodes spécifiées sont implémentées et fonctionnelles
3. **Intégration** : Le fichier `index.php` utilise la classe Router correctement
4. **Gestion d'erreurs** : Les cas d'erreur (route non trouvée, méthode non autorisée, fonction inexistante) sont gérés
5. **Chaînage** : La méthode `addRoute()` retourne l'instance pour permettre le chaînage
6. **Code propre** : Suppression de la logique de routage du fichier `index.php`
7. **Documentation** : Code commenté et méthodes documentées

## 🎯 Exemples de tests

### Test 1 : Ajout de routes
```php
$router = new Router($request, $response);
$router->addRoute('index', 'indexFilms', ['GET'])
       ->addRoute('create', 'createFilm', ['GET', 'POST']);

// Vérifications
assert($router->hasRoute('index') === true);
assert($router->hasRoute('create') === true);
assert($router->hasRoute('inexistant') === false);
```

### Test 2 : Récupération des routes
```php
$router = new Router($request, $response);
$router->addRoute('index', 'indexFilms', ['GET']);

$routes = $router->getRoutes();
assert(isset($routes['index']));
assert($routes['index']['fonction'] === 'indexFilms');
assert($routes['index']['methodes'] === ['GET']);
```

### Test 3 : Gestion des erreurs
```php
// Test avec une route inexistante
$router = new Router($request, $response);
$router->addRoute('index', 'indexFilms', ['GET']);

// Simuler une requête vers une route inexistante
// Le router doit rediriger vers l'index
```

## 🔧 Codes de statut HTTP à gérer

- **200** : Succès (route trouvée et fonction exécutée)
- **302** : Redirection (route non trouvée, redirection vers l'index)
- **405** : Méthode non autorisée (méthode HTTP non autorisée pour cette route)

## 📚 Ressources complémentaires

- Documentation PHP sur les fonctions : https://www.php.net/manual/fr/function.call-user-func.php
- Codes de statut HTTP : https://developer.mozilla.org/fr/docs/Web/HTTP/Status
- Principes MVC : https://fr.wikipedia.org/wiki/Modèle-vue-contrôleur
- Bonnes pratiques PHP : https://www.php.net/manual/fr/language.oop5.php

---

**Durée estimée** : 2-3 heures  
**Niveau** : Intermédiaire  
**Prérequis** : Connaissance de PHP OOP, architecture MVC, classes Request et Response
