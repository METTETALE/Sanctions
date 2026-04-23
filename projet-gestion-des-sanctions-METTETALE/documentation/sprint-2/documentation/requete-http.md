# Énoncé de Travail : Implémentation d'une Classe Request pour la Gestion des Requêtes HTTP

[← Retour aux travaux à réaliser](travail-a-faire.md)

## 📋 Contexte et Objectif

Dans le cadre de l'évolution de l'application PHP-MVC-Films vers une architecture MVC plus évoluée, il est nécessaire de représenter le concept de requête HTTP par une classe dédiée. Cette approche permet d'encapsuler les données de la requête HTTP et d'améliorer la maintenabilité, la testabilité et la lisibilité du code.

### Objectifs pédagogiques
- Comprendre le principe d'encapsulation des données HTTP
- Maîtriser la création d'une classe métier pour représenter une requête
- Intégrer cette classe dans l'architecture MVC existante
- Améliorer la séparation des responsabilités dans l'application

## 🎯 Mission

Vous devez créer une classe `Request` qui encapsule toutes les données d'une requête HTTP et remplacer l'utilisation directe des superglobales PHP (`$_GET`, `$_POST`, `$_SERVER`) dans les contrôleurs.

## 📁 Structure à respecter

Créez la classe `Request` dans le fichier : `src/Http/Request.php`

## 🔧 Spécifications de la classe Request

### Propriétés privées
La classe doit contenir les propriétés privées suivantes :
- `$get` : tableau des paramètres GET
- `$post` : tableau des paramètres POST  
- `$server` : tableau des données du serveur
- `$method` : méthode HTTP de la requête
- `$action` : action demandée (paramètre GET 'action')

### Constructeur
Le constructeur doit initialiser toutes les propriétés avec les superglobales PHP correspondantes.

## 📝 Méthodes à implémenter

### 1. Méthodes de récupération de paramètres

> **💡 Important : Le paramètre `$default`**
> 
> Le paramètre `$default` est crucial pour éviter les erreurs et rendre le code plus robuste. Il permet de :
> - **Éviter les valeurs `null`** quand un paramètre n'existe pas
> - **Définir des valeurs sensées** par défaut (page 1, chaîne vide, etc.)
> - **Simplifier le code** en évitant les vérifications manuelles
> 
> **Exemple de problème sans `$default` :**
> ```php
> $page = $request->get('page'); // Peut retourner null
> if ($page === null) {
>     $page = 1; // Gestion manuelle nécessaire
> }
> ```
> 
> **Solution avec `$default` :**
> ```php
> $page = $request->get('page', 1); // Retourne directement 1 si 'page' n'existe pas
> ```

#### `get($key, $default = null)`
- **Objectif** : Récupère un paramètre GET spécifique
- **Signature** : `public function get($key, $default = null)`
- **Paramètre `$default`** : Valeur retournée si le paramètre n'existe pas dans la requête
- **Exemple d'utilisation** :
  ```php
  // Pour une requête : index.php?action=show&id=123
  $request = new Request();
  $id = $request->get('id');        // Retourne "123" (paramètre existe)
  $page = $request->get('page', 1); // Retourne 1 (paramètre n'existe pas, utilise la valeur par défaut)
  $search = $request->get('search', ''); // Retourne "" (chaîne vide par défaut)
  ```

#### `post($key, $default = null)`
- **Objectif** : Récupère un paramètre POST spécifique
- **Signature** : `public function post($key, $default = null)`
- **Paramètre `$default`** : Valeur retournée si le paramètre n'existe pas dans les données POST
- **Exemple d'utilisation** :
  ```php
  // Pour un formulaire POST avec champ 'titre' mais sans 'note'
  $request = new Request();
  $titre = $request->post('titre');           // Retourne la valeur du champ 'titre'
  $note = $request->post('note', 0);         // Retourne 0 (champ 'note' n'existe pas, utilise la valeur par défaut)
  $actif = $request->post('actif', true);    // Retourne true (valeur par défaut)
  ```

#### `allPost()`
- **Objectif** : Récupère tous les paramètres POST
- **Signature** : `public function allPost()`
- **Exemple d'utilisation** :
  ```php
  // Pour un formulaire de création de film
  $request = new Request();
  $donnees = $request->allPost(); // Retourne ['titre' => '...', 'realisateur' => '...', ...]
  ```

#### `allGet()`
- **Objectif** : Récupère tous les paramètres GET
- **Signature** : `public function allGet()`
- **Exemple d'utilisation** :
  ```php
  // Pour une requête : index.php?action=search&search=batman&page=2
  $request = new Request();
  $params = $request->allGet(); // Retourne ['action' => 'search', 'search' => 'batman', 'page' => '2']
  ```

### 2. Méthodes de vérification du type de requête

#### `isPost()`
- **Objectif** : Vérifie si la requête utilise la méthode POST
- **Signature** : `public function isPost()`
- **Exemple d'utilisation** :
  ```php
  $request = new Request();
  if ($request->isPost()) {
      // Traitement d'un formulaire soumis
      $donnees = $request->allPost();
  }
  ```

#### `isGet()`
- **Objectif** : Vérifie si la requête utilise la méthode GET
- **Signature** : `public function isGet()`
- **Exemple d'utilisation** :
  ```php
  $request = new Request();
  if ($request->isGet()) {
      // Affichage d'une page ou traitement d'un lien
      $id = $request->get('id');
  }
  ```

### 3. Méthodes d'information sur la requête

#### `getMethod()`
- **Objectif** : Récupère la méthode HTTP de la requête
- **Signature** : `public function getMethod()`
- **Exemple d'utilisation** :
  ```php
  $request = new Request();
  $method = $request->getMethod(); // Retourne "GET", "POST", "PUT", etc.
  ```

#### `getAction()`
- **Objectif** : Récupère l'action demandée (paramètre GET 'action')
- **Signature** : `public function getAction()`
- **Exemple d'utilisation** :
  ```php
  // Pour une requête : index.php?action=edit&id=123
  $request = new Request();
  $action = $request->getAction(); // Retourne "edit"
  ```

#### `has($key)`
- **Objectif** : Vérifie si un paramètre existe dans GET ou POST
- **Signature** : `public function has($key)`
- **Exemple d'utilisation** :
  ```php
  $request = new Request();
  if ($request->has('search')) {
      // Le paramètre 'search' existe
      $search = $request->get('search');
  }
  ```

### 4. Méthodes utilitaires

#### `getUrl()`
- **Objectif** : Récupère l'URL complète de la requête
- **Signature** : `public function getUrl()`
- **Exemple d'utilisation** :
  ```php
  $request = new Request();
  $url = $request->getUrl(); // Retourne "http://localhost/index.php?action=show&id=123"
  ```

#### `getClientIp()`
- **Objectif** : Récupère l'adresse IP du client
- **Signature** : `public function getClientIp()`
- **Exemple d'utilisation** :
  ```php
  $request = new Request();
  $ip = $request->getClientIp(); // Retourne "192.168.1.100" ou "unknown"
  ```

## 🔄 Intégration dans l'application

### 1. Modification du routeur (`public/index.php`)

Modifiez le fichier `public/index.php` pour :
- Inclure la classe Request
- Créer une instance de Request
- Utiliser `$request->getMethod()` et `$request->getAction()` au lieu des superglobales
- Transmettre l'objet Request aux fonctions des contrôleurs

**Exemple de modification** :
```php
// Inclusion de la classe Request
require_once __DIR__ . '/../src/Http/Request.php';

// Création de l'objet Request
$request = new Request();

// Récupération de la méthode HTTP et de l'action
$method = $request->getMethod();
$action = $request->getAction();

// Transmission de l'objet Request aux contrôleurs
call_user_func($route['fonction'], $request);
```

### 2. Modification des contrôleurs (`src/controllers/filmController.php`)

Modifiez toutes les fonctions du contrôleur pour :
- Recevoir l'objet Request en paramètre
- Remplacer les superglobales par les méthodes de Request

**Exemple de modification** :
```php
// Avant
function showFilm() {
    $id = $_GET['id'] ?? null;
    // ...
}

// Après
function showFilm(Request $request) {
    $id = $request->get('id');
    // ...
}

// Exemple avec valeurs par défaut
function searchFilms(Request $request) {
    $search = $request->get('search', '');     // Chaîne vide par défaut
    $page = $request->get('page', 1);         // Page 1 par défaut
    $limit = $request->get('limit', 20);      // 20 résultats par défaut
    // ...
}
```

## ✅ Critères de validation

Votre implémentation sera validée selon les critères suivants :

1. **Structure** : La classe Request est correctement placée dans `src/Http/Request.php`
2. **Fonctionnalité** : Toutes les méthodes spécifiées sont implémentées et fonctionnelles
3. **Intégration** : Le routeur et les contrôleurs utilisent l'objet Request
4. **Code propre** : Suppression de l'utilisation directe des superglobales dans les contrôleurs
5. **Documentation** : Code commenté et méthodes documentées

## 🎯 Exemple de requête HTTP pour les tests

Utilisez cette requête pour tester vos méthodes :

**URL** : `http://localhost/index.php?action=edit&id=123&search=batman`

**Méthode** : GET

**Résultats attendus** :
- `$request->getAction()` → `"edit"`
- `$request->get('id')` → `"123"`
- `$request->get('search')` → `"batman"`
- `$request->isGet()` → `true`
- `$request->isPost()` → `false`

## 📚 Ressources complémentaires

- Documentation PHP sur les superglobales : https://www.php.net/manual/fr/language.variables.superglobals.php
- Principes MVC : https://fr.wikipedia.org/wiki/Modèle-vue-contrôleur
- Bonnes pratiques PHP : https://www.php.net/manual/fr/language.oop5.php

---

**Durée estimée** : 2-3 heures  
**Niveau** : Intermédiaire  
**Prérequis** : Connaissance de PHP OOP et architecture MVC
