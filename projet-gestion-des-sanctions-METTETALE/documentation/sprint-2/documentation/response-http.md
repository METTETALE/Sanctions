# Énoncé de Travail : Implémentation d'une Classe Response pour la Gestion des Réponses HTTP

[← Retour aux travaux à réaliser](travail-a-faire.md)

## 📋 Contexte et Objectif

Dans le cadre de l'évolution de l'application PHP-MVC-Films vers une architecture MVC plus évoluée, il est nécessaire de représenter le concept de réponse HTTP par une classe dédiée. Cette approche permet d'encapsuler toutes les données nécessaires pour envoyer une réponse au client et d'améliorer la maintenabilité, la testabilité et la flexibilité du code.

### Objectifs pédagogiques
- Comprendre le principe d'encapsulation des réponses HTTP
- Maîtriser la création d'une classe métier pour représenter une réponse
- Intégrer cette classe dans l'architecture MVC existante
- Améliorer la séparation des responsabilités dans l'application
- Comprendre le passage par référence des objets en PHP

## 🎯 Mission

Vous devez créer une classe `Response` qui encapsule toutes les données d'une réponse HTTP et remplacer l'utilisation directe des fonctions PHP (`header()`, `echo`, `exit`) dans les contrôleurs. L'objet Response sera passé en paramètre à chaque contrôleur pour une gestion optimisée.

## 📁 Structure à respecter

Créez la classe `Response` dans le fichier : `src/Http/Response.php`

## 🔧 Spécifications de la classe Response

### Propriétés privées
La classe doit contenir les propriétés privées suivantes :
- `$statusCode` : code de statut HTTP (défaut: 200)
- `$headers` : tableau des headers HTTP
- `$body` : corps de la réponse
- `$contentType` : type de contenu (défaut: 'text/html')
- `$charset` : encodage des caractères (défaut: 'UTF-8')

### Constructeur
Le constructeur doit initialiser toutes les propriétés avec des valeurs par défaut appropriées.

## 📝 Méthodes à implémenter

### 1. Méthodes de configuration de base

#### `setStatusCode($statusCode)`
- **Objectif** : Définit le code de statut HTTP
- **Signature** : `public function setStatusCode($statusCode)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setStatusCode(404); // Définit une erreur 404
  $response->setStatusCode(201); // Définit un code de création
  ```

#### `setBody($body)`
- **Objectif** : Définit le corps de la réponse
- **Signature** : `public function setBody($body)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setBody("<h1>Page d'accueil</h1>");
  $response->setBody("Contenu simple");
  ```

#### `setHeader($name, $value)`
- **Objectif** : Ajoute ou modifie un header HTTP
- **Signature** : `public function setHeader($name, $value)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setHeader("Content-Type", "application/json");
  $response->setHeader("Cache-Control", "no-cache");
  $response->setHeader("X-Custom-Header", "Valeur personnalisée");
  ```

#### `setContentType($contentType)`
- **Objectif** : Définit le type de contenu
- **Signature** : `public function setContentType($contentType)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setContentType("text/html");
  $response->setContentType("application/json");
  $response->setContentType("text/plain");
  ```

### 2. Méthodes de récupération

#### `getStatusCode()`
- **Objectif** : Récupère le code de statut HTTP
- **Signature** : `public function getStatusCode()`
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setStatusCode(404);
  $code = $response->getStatusCode(); // Retourne 404
  ```

#### `getBody()`
- **Objectif** : Récupère le corps de la réponse
- **Signature** : `public function getBody()`
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setBody("Contenu de la page");
  $contenu = $response->getBody(); // Retourne "Contenu de la page"
  ```

#### `getHeader($name)`
- **Objectif** : Récupère un header spécifique
- **Signature** : `public function getHeader($name)`
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setHeader("Content-Type", "text/html");
  $type = $response->getHeader("Content-Type"); // Retourne "text/html"
  ```

#### `getHeaders()`
- **Objectif** : Récupère tous les headers
- **Signature** : `public function getHeaders()`
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setHeader("Content-Type", "text/html");
  $response->setHeader("Cache-Control", "no-cache");
  $headers = $response->getHeaders(); // Retourne le tableau complet
  ```

### 3. Méthodes spécialisées

#### `redirect($url, $statusCode = 302)`
- **Objectif** : Effectue une redirection HTTP
- **Signature** : `public function redirect($url, $statusCode = 302)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->redirect("index.php?action=index"); // Redirection 302 par défaut
  $response->redirect("login.php", 301); // Redirection permanente
  ```

#### `view($templatePath, $data = [], $statusCode = 200)`
- **Objectif** : Rendu d'un template PHP avec données
- **Signature** : `public function view($templatePath, $data = [], $statusCode = 200)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $films = getAllFilms();
  $data = ['films' => $films, 'titre' => 'Liste des films'];
  
  $response->view(__DIR__ . '/../../templates/films/index.php', $data);
  ```

#### `error($message, $statusCode = 500)`
- **Objectif** : Crée une réponse d'erreur formatée
- **Signature** : `public function error($message, $statusCode = 500)`
- **Retour** : Instance of Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->error("Film non trouvé", 404);
  $response->error("Erreur serveur", 500);
  ```

#### `success($message, $statusCode = 200)`
- **Objectif** : Crée une réponse de succès formatée
- **Signature** : `public function success($message, $statusCode = 200)`
- **Retour** : Instance de Response pour le chaînage
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->success("Film créé avec succès");
  $response->success("Film modifié", 201);
  ```

### 4. Méthode d'envoi

#### `send()`
- **Objectif** : Envoie la réponse HTTP au client
- **Signature** : `public function send()`
- **Comportement** : Envoie le code de statut, les headers et le corps, puis termine l'exécution
- **Exemple d'utilisation** :
  ```php
  $response = new Response();
  $response->setStatusCode(200);
  $response->setBody("<h1>Page d'accueil</h1>");
  $response->send(); // Envoie la réponse et termine l'exécution
  ```

### 5. Méthodes statiques (optionnelles)

#### `redirectTo($url, $statusCode = 302)`
- **Objectif** : Crée rapidement une réponse de redirection
- **Signature** : `public static function redirectTo($url, $statusCode = 302)`
- **Exemple d'utilisation** :
  ```php
  $response = Response::redirectTo("index.php?action=index");
  $response->send();
  ```

#### `errorResponse($message, $statusCode = 500)`
- **Objectif** : Crée rapidement une réponse d'erreur
- **Signature** : `public static function errorResponse($message, $statusCode = 500)`
- **Exemple d'utilisation** :
  ```php
  $response = Response::errorResponse("Page non trouvée", 404);
  $response->send();
  ```

## 🔄 Intégration dans l'application

### 1. Modification du routeur (`public/index.php`)

Modifiez le fichier `public/index.php` pour :
- Inclure la classe Response
- Créer une instance de Response
- Passer l'objet Response aux fonctions des contrôleurs
- Envoyer automatiquement la réponse après traitement

**Exemple de modification** :
```php
// Inclusion de la classe Response
require_once __DIR__ . '/../src/Http/Response.php';

// Création des objets Request et Response
$request = new Request();
$response = new Response();

// Récupération de la méthode HTTP et de l'action
$method = $request->getMethod();
$action = $request->getAction();

// Appel du contrôleur avec les deux paramètres
call_user_func($route['fonction'], $request, $response);

// Envoi automatique de la réponse
$response->send();
```

### 2. Modification des contrôleurs (`src/controllers/filmController.php`)

Modifiez toutes les fonctions du contrôleur pour :
- Recevoir l'objet Response en paramètre
- Utiliser les méthodes de Response au lieu des fonctions PHP natives
- **IMPORTANT** : Ne pas retourner l'objet Response (il est passé par référence)

**Exemple de modification** :
```php
// Avant
function showFilm() {
    $id = $_GET['id'] ?? null;
    $film = getFilmById($id);
    
    if (!$film) {
        header("Location: index.php?action=index");
        exit;
    }
    
    include __DIR__ . '/../../templates/films/show.php';
}

// Après
function showFilm(Request $request, Response $response) {
    $id = $request->get('id');
    $film = getFilmById($id);
    
    if (!$film) {
        $response->redirect("index.php?action=index");
        return; // Pas besoin d'exit !
    }
    
    $data = ['film' => $film];
    $response->view(__DIR__ . '/../../templates/films/show.php', $data);
    // Pas besoin de return !
}
```

### 3. Principe du passage par référence

> **💡 Important : Passage par référence des objets**
> 
> En PHP, les objets sont **toujours passés par référence**. Cela signifie que :
> - Les modifications apportées à l'objet dans le contrôleur affectent l'objet original
> - Pas besoin de retourner l'objet Response
> - Une seule instance est créée et réutilisée
> 
> **Exemple illustratif :**
> ```php
> // Dans le routeur
> $response = new Response();
> call_user_func($route['fonction'], $request, $response);
> // L'objet $response est maintenant modifié par le contrôleur !
> 
> // Dans le contrôleur
> function monControleur(Request $request, Response $response) {
>     $response->setStatusCode(201);  // Modification directe
>     $response->setBody("Nouveau contenu");  // Modification directe
>     // Pas de return nécessaire !
> }
> ```

## ✅ Critères de validation

Votre implémentation sera validée selon les critères suivants :

1. **Structure** : La classe Response est correctement placée dans `src/Http/Response.php`
2. **Fonctionnalité** : Toutes les méthodes spécifiées sont implémentées et fonctionnelles
3. **Intégration** : Le routeur et les contrôleurs utilisent l'objet Response
4. **Code propre** : Suppression de l'utilisation directe des fonctions PHP natives dans les contrôleurs
5. **Passage par référence** : Compréhension et utilisation correcte du passage par référence
6. **Documentation** : Code commenté et méthodes documentées
7. **Chaînage** : Les méthodes retournent l'instance pour permettre le chaînage

## 🎯 Exemples de tests

### Test 1 : Réponse basique
```php
$response = new Response();
$response->setStatusCode(200)
         ->setBody("<h1>Test</h1>")
         ->setHeader("Content-Type", "text/html");

// Vérifications
assert($response->getStatusCode() === 200);
assert($response->getBody() === "<h1>Test</h1>");
assert($response->getHeader("Content-Type") === "text/html");
```

### Test 2 : Redirection
```php
$response = new Response();
$response->redirect("index.php?action=index", 301);

// Vérifications
assert($response->getStatusCode() === 301);
assert($response->getHeader("Location") === "index.php?action=index");
```

### Test 3 : Réponse d'erreur
```php
$response = new Response();
$response->error("Page non trouvée", 404);

// Vérifications
assert($response->getStatusCode() === 404);
assert(strpos($response->getBody(), "Page non trouvée") !== false);
```

## 📚 Ressources complémentaires

- Documentation PHP sur les headers HTTP : https://www.php.net/manual/fr/function.header.php
- Codes de statut HTTP : https://developer.mozilla.org/fr/docs/Web/HTTP/Status
- Principes MVC : https://fr.wikipedia.org/wiki/Modèle-vue-contrôleur
- Passage par référence en PHP : https://www.php.net/manual/fr/language.oop5.references.php

---

**Durée estimée** : 3-4 heures  
**Niveau** : Intermédiaire  
**Prérequis** : Connaissance de PHP OOP, architecture MVC et classe Request
