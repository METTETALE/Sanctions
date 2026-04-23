# Cours : Le Protocole HTTP - Application Films

## 📚 Table des matières

1. [Qu'est-ce qu'HTTP ?](#quest-ce-quhttp-)
2. [Comment fonctionne HTTP ?](#comment-fonctionne-http-)
3. [Les requêtes HTTP dans notre application](#les-requêtes-http-dans-notre-application)
4. [Les réponses HTTP dans notre application](#les-réponses-http-dans-notre-application)
5. [Les méthodes HTTP utilisées](#les-méthodes-http-utilisées)
6. [Les codes de statut importants](#les-codes-de-statut-importants)
7. [Headers essentiels](#headers-essentiels)
8. [Exemples pratiques avec notre application](#exemples-pratiques-avec-notre-application)

---

## Qu'est-ce qu'HTTP ?

### Définition simple

**HTTP** (HyperText Transfer Protocol) est le "langage" utilisé par votre navigateur pour communiquer avec notre serveur de films. C'est comme un dialogue entre deux personnes :

- **Le navigateur** dit : "Je veux voir la liste des films"
- **Le serveur** répond : "Voici la liste des films"

### Caractéristiques importantes

- **Simple** : Les messages HTTP sont lisibles par l'humain
- **Sans mémoire** : Chaque requête est indépendante (le serveur "oublie" après chaque réponse)
- **Basé sur du texte** : Pas de code compliqué, juste du texte

### Dans notre application films

Quand vous tapez `http://localhost/index.php?action=index` dans votre navigateur :
1. Le navigateur envoie une requête HTTP au serveur
2. Le serveur traite la requête et génère la page HTML
3. Le serveur renvoie la réponse HTTP avec le HTML
4. Le navigateur affiche la page des films

---

## Comment fonctionne HTTP ?

### Le dialogue client-serveur

```
Votre Navigateur          Notre Serveur PHP
      ↓                         ↓
   "Je veux voir les films"  →  Traitement
      ↓                         ↓
   Affichage de la page    ←  "Voici les films en HTML"
```

### Exemple concret avec notre application

**Vous cliquez sur "Voir les détails" d'un film :**

1. **Navigateur** → **Serveur** : `GET /index.php?action=show&id=123`
2. **Serveur** : Trouve le film avec l'ID 123 dans la base de données
3. **Serveur** → **Navigateur** : Envoie la page HTML avec les détails du film
4. **Navigateur** : Affiche la page avec les informations du film

---

## Les requêtes HTTP dans notre application

### Structure d'une requête

Une requête HTTP ressemble à ceci :

```
[MÉTHODE] [URL] [VERSION]
[Headers...]
[Ligne vide]
[Données (si nécessaire)]
```

### Exemple concret : Afficher un film

Quand vous cliquez sur "Voir les détails" d'un film, voici ce qui se passe :

```http
GET /index.php?action=show&id=123 HTTP/1.1
Host: localhost:8080
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)
Accept: text/html,application/xhtml+xml
Accept-Language: fr-FR,fr;q=0.9
```

**Explication :**
- `GET` : Je veux récupérer des informations
- `/index.php?action=show&id=123` : L'URL demandée
- `HTTP/1.1` : Version du protocole
- `Host: localhost:8080` : Le serveur à contacter
- `User-Agent` : Quel navigateur fait la requête

### Exemple concret : Créer un nouveau film

Quand vous soumettez le formulaire de création :

```http
POST /index.php?action=create HTTP/1.1
Host: localhost:8080
Content-Type: application/x-www-form-urlencoded
Content-Length: 45

titre=Inception&realisateur=Nolan&annee=2010
```

**Explication :**
- `POST` : Je veux envoyer des données
- `Content-Type` : Type de données envoyées (formulaire)
- `Content-Length` : Taille des données
- Les données du formulaire sont dans le corps de la requête

---

## Les réponses HTTP dans notre application

### Structure d'une réponse

Le serveur répond toujours avec cette structure :

```
[VERSION] [CODE] [MESSAGE]
[Headers...]
[Ligne vide]
[Contenu de la page]
```

### Exemple concret : Page des films affichée avec succès

```http
HTTP/1.1 200 OK
Date: Mon, 23 Oct 2023 22:38:34 GMT
Server: Apache/2.4.41 (Ubuntu)
Content-Type: text/html; charset=UTF-8
Content-Length: 1234

<!DOCTYPE html>
<html>
<head>
    <title>Liste des films</title>
</head>
<body>
    <h1>Mes films préférés</h1>
    <ul>
        <li>Inception (2010)</li>
        <li>The Dark Knight (2008)</li>
    </ul>
</body>
</html>
```

**Explication :**
- `HTTP/1.1 200 OK` : Tout s'est bien passé
- `Content-Type: text/html` : Je renvoie du HTML
- `Content-Length: 1234` : La page fait 1234 caractères
- Le HTML de la page est dans le corps de la réponse

### Exemple concret : Film non trouvé (erreur 404)

```http
HTTP/1.1 404 Not Found
Content-Type: text/html; charset=UTF-8
Content-Length: 234

<!DOCTYPE html>
<html>
<head><title>Erreur 404</title></head>
<body>
    <h1>Film non trouvé</h1>
    <p>Le film demandé n'existe pas.</p>
    <a href="index.php?action=index">Retour à la liste</a>
</body>
</html>
```

**Explication :**
- `HTTP/1.1 404 Not Found` : Le film n'existe pas
- Le serveur renvoie une page d'erreur HTML

---

## Les méthodes HTTP utilisées

### Dans notre application films, nous utilisons principalement 2 méthodes :

#### GET - Pour récupérer des informations
**Quand l'utiliser :**
- Afficher la liste des films
- Voir les détails d'un film
- Afficher le formulaire de création/modification

**Exemples dans notre app :**
```http
GET /index.php?action=index          # Liste des films
GET /index.php?action=show&id=123    # Détails du film 123
GET /index.php?action=create         # Formulaire de création
GET /index.php?action=edit&id=123    # Formulaire de modification
```

#### POST - Pour envoyer des données
**Quand l'utiliser :**
- Créer un nouveau film
- Modifier un film existant
- Supprimer un film

**Exemples dans notre app :**
```http
POST /index.php?action=create        # Créer un film
POST /index.php?action=update       # Modifier un film
POST /index.php?action=delete       # Supprimer un film
```

### Tableau récapitulatif

| Méthode | Usage dans notre app | Exemple |
|---------|---------------------|---------|
| GET | Afficher des pages | `index.php?action=show&id=123` |
| POST | Envoyer des données | Formulaire de création de film |

### Pourquoi ces méthodes ?

- **GET** : Pour les liens et les pages (pas de données sensibles)
- **POST** : Pour les formulaires (données cachées, plus sécurisé)

---

## Les codes de statut importants

### Codes que vous verrez souvent dans notre application :

#### 200 OK ✅
**Signification :** Tout s'est bien passé
**Exemple :** Page des films affichée correctement

#### 404 Not Found ❌
**Signification :** La page ou le film n'existe pas
**Exemple :** Vous demandez un film avec un ID qui n'existe pas

#### 500 Internal Server Error ⚠️
**Signification :** Erreur dans le code PHP
**Exemple :** Problème de connexion à la base de données

### Tableau des codes importants

| Code | Signification | Quand ça arrive |
|------|---------------|-----------------|
| 200 | OK | Page affichée avec succès |
| 404 | Not Found | Film ou page non trouvé |
| 500 | Server Error | Erreur dans le code PHP |

### Exemple concret dans notre application

**Scénario :** Vous cliquez sur un lien vers un film qui n'existe plus

1. **Requête :** `GET /index.php?action=show&id=999`
2. **Serveur :** Cherche le film avec ID 999 dans la base
3. **Résultat :** Film non trouvé
4. **Réponse :** `HTTP/1.1 404 Not Found`
5. **Page affichée :** "Film non trouvé"

---

## Headers essentiels

### Headers que vous verrez dans notre application

#### Content-Type
**Rôle :** Indique le type de contenu renvoyé
**Exemples :**
```http
Content-Type: text/html; charset=UTF-8    # Page HTML
Content-Type: application/json            # Données JSON
Content-Type: text/css                    # Fichier CSS
```

#### Content-Length
**Rôle :** Indique la taille du contenu en octets
**Exemple :**
```http
Content-Length: 1234    # La page fait 1234 caractères
```

#### Location
**Rôle :** Indique où rediriger l'utilisateur
**Exemple :** Après avoir créé un film, rediriger vers la liste
```http
Location: index.php?action=index
```

### Headers dans les requêtes

#### Host
**Rôle :** Indique quel serveur contacter
**Exemple :**
```http
Host: localhost:8080
```

#### User-Agent
**Rôle :** Indique quel navigateur fait la requête
**Exemple :**
```http
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)
```

### Pourquoi c'est important ?

Les headers permettent au navigateur de :
- **Savoir comment afficher** le contenu (HTML, image, etc.)
- **Savoir où rediriger** après une action
- **Optimiser l'affichage** (taille du contenu)

---

## Exemples pratiques avec notre application

### Scénario 1 : Afficher la liste des films

**Ce qui se passe quand vous allez sur la page d'accueil :**

1. **Vous tapez :** `http://localhost/index.php`
2. **Navigateur envoie :**
   ```http
   GET /index.php HTTP/1.1
   Host: localhost:8080
   ```
3. **Serveur répond :**
   ```http
   HTTP/1.1 200 OK
   Content-Type: text/html; charset=UTF-8
   
   <!DOCTYPE html>
   <html>
   <head><title>Mes Films</title></head>
   <body>
       <h1>Liste des films</h1>
       <ul>
           <li><a href="index.php?action=show&id=1">Inception</a></li>
           <li><a href="index.php?action=show&id=2">The Dark Knight</a></li>
       </ul>
   </body>
   </html>
   ```

### Scénario 2 : Créer un nouveau film

**Ce qui se passe quand vous soumettez le formulaire :**

1. **Vous remplissez le formulaire et cliquez "Créer"**
2. **Navigateur envoie :**
   ```http
   POST /index.php?action=create HTTP/1.1
   Host: localhost:8080
   Content-Type: application/x-www-form-urlencoded
   Content-Length: 45
   
   titre=Interstellar&realisateur=Nolan&annee=2014
   ```
3. **Serveur traite les données et répond :**
   ```http
   HTTP/1.1 302 Found
   Location: index.php?action=index
   
   ```
4. **Navigateur suit la redirection et affiche la liste mise à jour**

### Scénario 3 : Erreur - Film non trouvé

**Ce qui se passe quand vous demandez un film inexistant :**

1. **Vous cliquez sur un lien cassé :** `index.php?action=show&id=999`
2. **Navigateur envoie :**
   ```http
   GET /index.php?action=show&id=999 HTTP/1.1
   Host: localhost:8080
   ```
3. **Serveur répond :**
   ```http
   HTTP/1.1 404 Not Found
   Content-Type: text/html; charset=UTF-8
   
   <!DOCTYPE html>
   <html>
   <head><title>Erreur 404</title></head>
   <body>
       <h1>Film non trouvé</h1>
       <p>Le film demandé n'existe pas.</p>
       <a href="index.php?action=index">Retour à la liste</a>
   </body>
   </html>
   ```

### Comment voir ces échanges ?

**Dans votre navigateur :**
1. Ouvrez les **Outils de développement** (F12)
2. Allez dans l'onglet **Réseau** (Network)
3. Rechargez la page ou effectuez une action
4. Cliquez sur une requête pour voir les détails

**Vous verrez :**
- Les requêtes envoyées par le navigateur
- Les réponses du serveur
- Les codes de statut
- Les headers
- Le temps de réponse

---

## Résumé pour notre application

### Ce qu'il faut retenir :

1. **HTTP est simple** : C'est juste un dialogue texte entre navigateur et serveur
2. **GET** : Pour afficher des pages (liste, détails, formulaires)
3. **POST** : Pour envoyer des données (créer, modifier, supprimer)
4. **200** : Tout va bien
5. **404** : Quelque chose n'existe pas
6. **500** : Erreur dans le code PHP

### Dans notre code PHP :

```php
// Récupérer les données de la requête
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Envoyer une réponse
header('Content-Type: text/html; charset=UTF-8');
echo '<h1>Page des films</h1>';

// Rediriger après une action
header('Location: index.php?action=index');
exit;
```

### Prochaines étapes :

Maintenant que vous comprenez HTTP, vous pouvez :
- Implémenter la classe `Request` pour gérer les requêtes
- Implémenter la classe `Response` pour gérer les réponses
- Améliorer la sécurité de votre application
- Optimiser les performances

---

*Document créé pour le projet PHP-MVC-Films - Formation SIO2*
