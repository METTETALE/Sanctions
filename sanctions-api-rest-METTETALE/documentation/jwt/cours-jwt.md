# 📚 Cours Complet : JSON Web Tokens (JWT)

## Table des matières

1. [Introduction et concepts](#introduction)
2. [Structure d'un JWT](#structure)
3. [Flux d'authentification](#flux)
4. [Implémentation détaillée](#implémentation)
5. [Sécurité et bonnes pratiques](#sécurité)
6. [Cas d'usage pratique](#cas-usage)
7. [Dépannage courant](#dépannage)

---

## 1. Introduction et concepts {#introduction}

### Qu'est-ce qu'un JWT ?

Un **JSON Web Token (JWT)** est un standard ouvert (RFC 7519) qui permet de transmettre de manière sécurisée des informations entre deux parties (client et serveur) sous la forme d'un objet JSON compact et auto-contenu.

**Définiton formelle** : C'est un token encodé en Base64URL, composé de trois segments séparés par des points, qui contient des revendications (claims) signées numériquement.

**Analogie simple** :
Imaginez un JWT comme un **badge d'accès sécurisé** que vous recevez après avoir montré votre pièce d'identité à la réception. Ce badge contient :

- Votre identifiant
- Votre email
- La date d'émission
- Une date d'expiration
- Une signature (pour prouver que c'est un vrai badge)

Avec ce badge, vous pouvez accéder directement à tous les services de l'établissement sans devoir vous re-présenter à chaque fois.

### Pourquoi utiliser les JWT ?

Dans le contexte d'une **API REST**, les JWT offrent plusieurs avantages décisifs :

| Avantage       | Explication                                                                              |
| -------------- | ---------------------------------------------------------------------------------------- |
| **Stateless**  | Le serveur n'a pas besoin de stocker les sessions en mémoire ou en base de données       |
| **Scalable**   | Facile à déployer sur plusieurs serveurs ou en microservices (pas de partage de session) |
| **Portable**   | Le token contient toutes les informations nécessaires pour identifier l'utilisateur      |
| **Sécurisé**   | Signé numériquement pour éviter la falsification                                         |
| **Performant** | Réduit les requêtes base de données (pas besoin de vérifier une session à chaque fois)   |
| **Compatible** | Fonctionne avec tous les types de clients (web, mobile, CLI, etc.)                       |

### JWT vs Sessions tradionnelles

**Avec sessions traditionnelles :**

```
Client → Serveur : identifiants
Serveur : crée session en mémoire/BD
Serveur → Client : session ID (cookie)
Client stocke : session ID
Client → Serveur : session ID pour chaque requête
Serveur : cherche la session correspondante
```

**Avec JWT :**

```
Client → Serveur : identifiants
Serveur : génère JWT (token)
Serveur → Client : token
Client stocke : token
Client → Serveur : token pour chaque requête
Serveur : valide le token (pas de recherche en BD)
```

Le JWT est plus efficace car il ne demande pas de recherche en base de données à chaque requête !

---

## 2. Structure d'un JWT {#structure}

### Vue d'ensemble

Un JWT est composé de **trois parties** séparées par des points (`.`), toutes encodées en Base64URL :

```
xxxxx.yyyyy.zzzzz
│      │     │
│      │     └─ Signature
│      └─ Payload
└─ Header
```

**Format général :**

```
header.payload.signature
```

### 2.1 Le Header (En-tête)

Le header est un objet JSON qui contient deux informations essentielles :

| Propriété | Description                       | Exemple                   |
| --------- | --------------------------------- | ------------------------- |
| **alg**   | Algorithme de signature utilisé   | `HS256`, `HS512`, `RS256` |
| **typ**   | Type de token (générallement JWT) | `JWT`                     |

**Exemple de header JSON :**

```json
{
  "alg": "HS256",
  "typ": "JWT"
}
```

**Encodage Base64URL :**
Ce header JSON est encodé en Base64URL, ce qui donne :

```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9
```

**Décodage :** Si vous décoder cette chaîne, vous retrouvez le JSON original.

#### Algorithmes courants

- **HS256** (HMAC SHA-256) : Algorithme symétrique, même clé pour signer et vérifier
- **HS512** (HMAC SHA-512) : Version plus sécurisée de HS256
- **RS256** (RSA SHA-256) : Algorithme asymétrique, clé privée pour signer, clé publique pour vérifier
- **RS512** (RSA SHA-512) : Version plus sécurisée de RS256

### 2.2 Le Payload (Charge utile)

Le payload est un objet JSON contenant les **claims** (revendications), c'est-à-dire les informations sur l'utilisateur.

#### Types de claims

Il existe trois catégories de claims :

#### 1️⃣ Claims standards/réservés

Ce sont des claims définis par la norme RFC 7519 :

| Claim   | Nom complet | Description                                           | Type         | Exemple                  |
| ------- | ----------- | ----------------------------------------------------- | ------------ | ------------------------ |
| **iss** | Issuer      | Entité qui a émis le token                            | string       | `"api.etablissement.fr"` |
| **sub** | Subject     | Sujet du token (généralement l'ID utilisateur)        | string       | `"123"`                  |
| **aud** | Audience    | Public cible du token                                 | string/array | `"etablissement-app"`    |
| **exp** | Expiration  | Date d'expiration en secondes (Unix timestamp)        | number       | `1609462800`             |
| **iat** | Issued At   | Date d'émission en secondes (Unix timestamp)          | number       | `1609459200`             |
| **nbf** | Not Before  | Date avant laquelle le token ne doit pas être accepté | number       | `1609459200`             |
| **jti** | JWT ID      | Identifiant unique du token                           | string       | `"unique-id-123"`        |

#### 2️⃣ Claims publics

Ce sont des claims définis par des applications ou standards. Exemple : `name`, `email`, `phone_number`, `address`.

```json
{
  "name": "Dupont",
  "email": "dupont@example.com",
  "phone_number": "+33612345678"
}
```

#### 3️⃣ Claims privés/personnalisés

Ce sont des claims définis par l'application pour ses besoins spécifiques.

```json
{
  "userId": 123,
  "role": "direction",
  "permission": "voir_sanctions",
  "departement": "vie-scolaire"
}
```

**Exemple complet de payload :**

```json
{
  "sub": "123",
  "userId": 123,
  "email": "vie-scolaire1@test.com",
  "role": "direction",
  "nom": "Martin",
  "prenom": "Jean",
  "iat": 1609459200,
  "exp": 1609462800,
  "nbf": 1609459200,
  "iss": "api.etablissement.fr"
}
```

**Après encodage Base64URL :**

```
eyJzdWIiOiIxMjMiLCJ1c2VySWQiOjEyMywiZW1haWwiOiJ2aWUtc2NvbGFpcmUxQHRlc3QuY29tIiwicm9sZSI6ImRpcmVjdGlvbiIsIm5vbSI6Ik1hcnRpbiIsInByZW5vbSI6IkplYW4iLCJpYXQiOjE2MDk0NTkyMDAsImV4cCI6MTYwOTQ2MjgwMCwibmJmIjoxNjA5NDU5MjAwLCJpc3MiOiJhcGkuZXRhYmxpc3NlbWVudC5mciJ9
```

⚠️ **IMPORTANT** : Le payload est encodé mais PAS chiffré ! N'y mettez pas d'informations sensibles comme les mots de passe ou numéros de carte bancaire.

### 2.3 La Signature

La signature est la partie la plus importante pour la sécurité. Elle permet de :

- **Vérifier l'intégrité** du token (qu'il n'a pas été modifié)
- **Authentifier l'émetteur** (prouver que c'est vraiment le serveur qui l'a généré)

#### Calcul de la signature

La signature est calculée en fonction de l'algorithme spécifié dans le header.

**Pour HS256 (HMAC SHA-256) :**

```
signature = HMACSHA256(
  base64UrlEncode(header) + "." + base64UrlEncode(payload),
  secret_key
)
```

**Processus étape par étape :**

1. Prendre le header encodé + point + payload encodé :

   ```
   eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjMiLCJ1c2VySWQiOjEyMywiZW1haWwiOiJ2aWUtc2NvbGFpcmUxQHRlc3QuY29tIiwicm9sZSI6ImRpcmVjdGlvbiIsImlhdCI6MTYwOTQ1OTIwMCwiZXhwIjoxNjA5NDYyODAwfQ
   ```

2. Appliquer HMAC-SHA256 avec une clé secrète :

   ```
   secret_key = "super_secret_key_very_long_and_random"
   ```

3. Encoder le résultat en Base64URL :
   ```
   SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c
   ```

#### Vérification de la signature

Quand le serveur reçoit un JWT, il effectue les étapes suivantes :

1. **Extraire** le header et payload
2. **Recalculer** la signature avec la même clé secrète
3. **Comparer** la signature reçue avec celle calculée
4. Si elles correspondent → Token valide ✅
5. Si elles ne correspondent pas → Token falsifié ❌

```
Token reçu:
header.payload.signature_reçue

Calcul:
signature_calculée = HMACSHA256(header.payload, secret)

Vérification:
Si signature_reçue === signature_calculée
  → Token valide ✅
Sinon
  → Token invalide ❌
```

#### Exemple complet de JWT

```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjMiLCJ1c2VySWQiOjEyMywiZW1haWwiOiJ2aWUtc2NvbGFpcmUxQHRlc3QuY29tIiwicm9sZSI6ImRpcmVjdGlvbiIsImlhdCI6MTYwOTQ1OTIwMCwiZXhwIjoxNjA5NDYyODAwfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c
```

**Décomposition :**

| Partie        | Contenu                                                                                                                                                  | Décodage                                                                                                           |
| ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------ |
| **Header**    | `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9`                                                                                                                   | `{"alg":"HS256","typ":"JWT"}`                                                                                      |
| **Payload**   | `eyJzdWIiOiIxMjMiLCJ1c2VySWQiOjEyMywiZW1haWwiOiJ2aWUtc2NvbGFpcmUxQHRlc3QuY29tIiwicm9sZSI6ImRpcmVjdGlvbiIsImlhdCI6MTYwOTQ1OTIwMCwiZXhwIjoxNjA5NDYyODAwfQ` | `{"sub":"123","userId":123,"email":"vie-scolaire1@test.com","role":"direction","iat":1609459200,"exp":1609462800}` |
| **Signature** | `SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c`                                                                                                            | Signature HMAC-SHA256 du header.payload                                                                            |

---

## 3. Flux d'authentification avec JWT {#flux}

### Vue d'ensemble du cycle de vie

```
┌────────────────────────────────────────────────────────────────┐
│                    CYCLE DE VIE D'UN JWT                        │
└────────────────────────────────────────────────────────────────┘

1. CONNEXION (Login)
   └─ Client envoie identifiants
   └─ Serveur génère JWT
   └─ Serveur retourne le token

2. STOCKAGE
   └─ Client sauvegarde le token

3. UTILISATION
   └─ Client envoie token avec chaque requête
   └─ Serveur valide le token
   └─ Serveur vérifie expiration
   └─ Serveur traite la requête si valide

4. EXPIRATION
   └─ Token expire
   └─ Client doit se re-connecter
   └─ Nouveau token généré
```

### Étape 1 : Connexion (Login)

#### Diagramme de séquence

```
┌─────────┐                    ┌─────────┐
│ Client  │                    │ Serveur │
│  (CLI)  │                    │  (API)  │
└────┬────┘                    └────┬────┘
     │                              │
     │  1. POST /api/auth/login     │
     │  { email, password }          │
     ├─────────────────────────────>│
     │                              │
     │                              │ 2. Extraction des identifiants
     │                              │
     │                              │ 3. Recherche en BD
     │                              │    - User.find({ email })
     │                              │
     │                              │ 4. Vérification du mot de passe
     │                              │    - bcrypt.compare(pwd, hash)
     │                              │
     │                              │ 5. Création du payload JWT
     │                              │    {
     │                              │      userId: 123,
     │                              │      email: "test@test.com",
     │                              │      iat: now,
     │                              │      exp: now + 3600
     │                              │    }
     │                              │
     │                              │ 6. Génération du token
     │                              │    jwt.sign(payload, secret)
     │                              │
     │  7. Réponse 200 OK           │
     │  {                           │
     │    token: "eyJ...",          │
     │    expiresIn: 3600           │
     │  }                           │
     │<─────────────────────────────┤
     │                              │
     │ 8. Stockage du token         │
     │    fs.writeFileSync(.token)  │
     │                              │
```

#### Requête HTTP complète

```http
POST /api/auth/login HTTP/1.1
Host: api.etablissement.fr
Content-Type: application/json

{
  "email": "vie-scolaire1@test.com",
  "password": "motDePasse123"
}
```

#### Réponse du serveur

```http
HTTP/1.1 200 OK
Content-Type: application/json
Set-Cookie: refreshToken=eyJ...; HttpOnly; Secure

{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjMiLCJ1c2VySWQiOjEyMywiZW1haWwiOiJ2aWUtc2NvbGFpcmUxQHRlc3QuY29tIiwiaWF0IjoxNjA5NDU5MjAwLCJleHAiOjE2MDk0NjI4MDB9.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c",
  "expiresIn": 3600,
  "user": {
    "id": 123,
    "email": "vie-scolaire1@test.com",
    "nom": "Martin"
  }
}
```

**Explication des champs :**

- **token** : Le JWT à utiliser pour les requêtes suivantes
- **expiresIn** : Durée de vie du token en secondes (3600 = 1 heure)
- **user** : Informations de base de l'utilisateur

### Étape 2 : Utilisation du token

Une fois connecté, le client stocke le token et l'envoie avec chaque requête dans l'en-tête `Authorization`.

#### Format de l'en-tête

```
Authorization: Bearer <token>
```

**Explication :**

- **Bearer** : Type d'authentification (indique que c'est un token)
- **Token** : Le JWT complet

#### Exemple de requête avec token

```http
GET /api/eleves/search?nom=Dupont HTTP/1.1
Host: api.etablissement.fr
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjMiLCJ1c2VySWQiOjEyMywiZW1haWwiOiJ2aWUtc2NvbGFpcmUxQHRlc3QuY29tIiwiaWF0IjoxNjA5NDU5MjAwLCJleHAiOjE2MDk0NjI4MDB9.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c
```

### Étape 3 : Vérification côté serveur

Quand le serveur reçoit une requête avec un JWT, il doit :

#### Processus de vérification

```
1. EXTRACTION
   ↓
   Récupérer l'en-tête Authorization
   Vérifier le format "Bearer <token>"
   Extraire le token

2. VÉRIFICATION DE SIGNATURE
   ↓
   Recalculer la signature avec la clé secrète
   Comparer avec la signature du token reçu
   Si ≠ : Rejeter (401 Unauthorized)

3. VÉRIFICATION DE L'EXPIRATION
   ↓
   Lire le claim "exp" du payload
   Le comparer avec l'heure actuelle
   Si exp < now : Rejeter (401 Unauthorized - Token expiré)

4. VÉRIFICATION DE L'UTILISATEUR (OPTIONNEL)
   ↓
   Vérifier en BD que l'utilisateur existe toujours
   Vérifier que l'utilisateur n'est pas désactivé

5. EXTRACTION DES CLAIMS
   ↓
   Décoder le payload
   Récupérer userId, email, role, etc.

6. STOCKAGE DANS LA REQUÊTE
   ↓
   req.user = { id: 123, email: "...", role: "..." }

7. PASSAGE À LA ROUTE SUIVANTE
   ↓
   next() → La requête continue vers le endpoint
```

**Diagramme complet :**

```
┌─────────────────────────────────────────────────────────────┐
│              REQUÊTE AVEC JWT REÇUE                          │
└─────────────────────────────────────────────────────────────┘

Authorization: Bearer eyJ...

   │
   ├─→ Middleware de vérification JWT
   │   │
   │   ├─ Extraction : eyJ... (valide ?)
   │   ├─ Split par "." : [header, payload, signature]
   │   ├─ Vérifier signature : HMAC(header.payload) == signature ?
   │   ├─ Vérifier expiration : exp > now ?
   │   ├─ Vérifier utilisateur en BD : existe et actif ?
   │   │
   │   ├─ ✅ OK → Continuer
   │   │        req.user = { id: 123, email: "..." }
   │   │        next()
   │   │
   │   └─ ❌ ERREUR → Retourner 401
   │            message: "Token invalide"
   │
   └─→ Route protégée
       GET /api/eleves/search
       (req.user est disponible)
```

---

## 4. Implémentation détaillée {#implémentation}

### 4.1 Côté serveur (API REST)

#### Installation des dépendances

Avec Node.js, il faut installer la bibliothèque `jsonwebtoken` :

```bash
npm install jsonwebtoken bcryptjs
npm install dotenv  # Pour gérer les variables d'environnement
```

#### Configuration des variables d'environnement

Créer un fichier `.env` :

```env
JWT_SECRET=votre_secret_super_long_et_aleatoire_ici_123456789
JWT_EXPIRES_IN=3600
NODE_ENV=production
```

⚠️ **IMPORTANT** :

- Ne JAMAIS committer le `.env` en Git
- Utiliser des secrets forts et différents par environnement
- Générer avec : `node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"`

#### 1. Fonction de génération du JWT

```javascript
import jwt from "jsonwebtoken";
import dotenv from "dotenv";

dotenv.config();

const SECRET_KEY = process.env.JWT_SECRET;
const EXPIRES_IN = process.env.JWT_EXPIRES_IN || 3600;

/**
 * Génère un JWT pour un utilisateur
 * @param {Object} user - Objet utilisateur de la base de données
 * @returns {string} - Le token JWT généré
 */
export function generateToken(user) {
  // Créer le payload
  const now = Math.floor(Date.now() / 1000); // En secondes
  const payload = {
    // Claims standards
    sub: user.id.toString(), // Subject : ID utilisateur
    iat: now, // Issued At : maintenant
    exp: now + parseInt(EXPIRES_IN), // Expiration : +1 heure

    // Claims personnalisés
    userId: user.id,
    email: user.email,
    role: user.role || "user",
    nom: user.nom || "",
    prenom: user.prenom || "",
  };

  try {
    // Signer le token
    const token = jwt.sign(payload, SECRET_KEY, {
      algorithm: "HS256",
    });

    return token;
  } catch (error) {
    throw new Error(`Erreur lors de la génération du token: ${error.message}`);
  }
}

/**
 * Décrit les informations du token (pour déboguer)
 */
export function getTokenInfo(token) {
  try {
    const decoded = jwt.decode(token); // Sans vérifier (juste décoder)

    return {
      userId: decoded.userId,
      email: decoded.email,
      expiresAt: new Date(decoded.exp * 1000),
      issuedAt: new Date(decoded.iat * 1000),
      expiresIn:
        Math.round((decoded.exp - Math.floor(Date.now() / 1000)) / 60) +
        " minutes",
    };
  } catch (error) {
    throw new Error("Token invalide");
  }
}
```

#### 2. Middleware de vérification du JWT

```javascript
import jwt from "jsonwebtoken";
import dotenv from "dotenv";

dotenv.config();

const SECRET_KEY = process.env.JWT_SECRET;

/**
 * Middleware pour vérifier le JWT dans les requêtes
 * Extrait le token de l'en-tête Authorization
 * Vérifie la signature et l'expiration
 * Ajoute les informations de l'utilisateur à req.user
 */
export function verifyToken(req, res, next) {
  try {
    // 1. Extraire l'en-tête Authorization
    const authHeader = req.headers.authorization;

    if (!authHeader) {
      return res.status(401).json({
        success: false,
        message: "En-tête Authorization manquant",
      });
    }

    // 2. Vérifier le format "Bearer <token>"
    if (!authHeader.startsWith("Bearer ")) {
      return res.status(401).json({
        success: false,
        message: "Format Authorization invalide. Utiliser: Bearer <token>",
      });
    }

    // 3. Extraire le token (enlever "Bearer ")
    const token = authHeader.substring(7);

    if (!token) {
      return res.status(401).json({
        success: false,
        message: "Token manquant",
      });
    }

    // 4. Vérifier et décoder le token
    const decoded = jwt.verify(token, SECRET_KEY, {
      algorithms: ["HS256"],
    });

    // 5. Ajouter les infos utilisateur à la requête
    req.user = {
      id: decoded.userId,
      email: decoded.email,
      role: decoded.role || "user",
      nom: decoded.nom,
      prenom: decoded.prenom,
      sub: decoded.sub,
    };

    // 6. Continuer vers la route suivante
    next();
  } catch (error) {
    // Gérer les erreurs spécifiques de JWT
    let message = "Token invalide";
    let statusCode = 401;

    if (error.name === "TokenExpiredError") {
      message = `Token expiré le ${new Date(error.expiredAt).toLocaleString()}`;
    } else if (error.name === "JsonWebTokenError") {
      message = "Token malformé ou signature invalide";
    } else if (error.name === "NotBeforeError") {
      message = "Token non encore valide";
    }

    return res.status(statusCode).json({
      success: false,
      message: message,
      error: process.env.NODE_ENV === "development" ? error.message : undefined,
    });
  }
}

/**
 * Middleware pour vérifier les permissions (exemple)
 */
export function checkRole(requiredRole) {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({
        success: false,
        message: "Utilisateur non authentifié",
      });
    }

    if (req.user.role !== requiredRole) {
      return res.status(403).json({
        success: false,
        message: `Accès refusé. Rôle requis: ${requiredRole}`,
      });
    }

    next();
  };
}
```

#### 3. Route de login

```javascript
import express from "express";
import bcrypt from "bcryptjs";
import { generateToken } from "./auth.js";
import db from "./config/db.js";

const router = express.Router();

/**
 * Endpoint de connexion
 * POST /api/auth/login
 */
router.post("/login", async (req, res) => {
  try {
    const { email, password } = req.body;

    // 1. Valider les entrées
    if (!email || !password) {
      return res.status(400).json({
        success: false,
        message: "Email et mot de passe requis",
      });
    }

    // 2. Chercher l'utilisateur en base de données
    const query = "SELECT * FROM utilisateurs WHERE email = ?";
    const [rows] = await db.execute(query, [email]);

    if (rows.length === 0) {
      return res.status(401).json({
        success: false,
        message: "Email ou mot de passe incorrect",
      });
    }

    const user = rows[0];

    // 3. Vérifier le mot de passe (supposant qu'il est hashé en BD)
    const passwordMatch = await bcrypt.compare(password, user.password_hash);

    if (!passwordMatch) {
      return res.status(401).json({
        success: false,
        message: "Email ou mot de passe incorrect",
      });
    }

    // 4. Vérifier que l'utilisateur est actif
    if (user.active === false) {
      return res.status(403).json({
        success: false,
        message: "Compte désactivé. Contactez l'administrateur.",
      });
    }

    // 5. Générer le JWT
    const token = generateToken(user);

    // 6. Optionnel : Mettre à jour la date de dernière connexion
    const updateQuery =
      "UPDATE utilisateurs SET last_login = NOW() WHERE id = ?";
    await db.execute(updateQuery, [user.id]);

    // 7. Retourner le token
    return res.status(200).json({
      success: true,
      message: "Connexion réussie",
      data: {
        token: token,
        expiresIn: process.env.JWT_EXPIRES_IN || 3600,
        user: {
          id: user.id,
          email: user.email,
          nom: user.nom,
          prenom: user.prenom,
          role: user.role,
        },
      },
    });
  } catch (error) {
    console.error("Erreur lors de la connexion:", error);
    res.status(500).json({
      success: false,
      message: "Erreur serveur lors de la connexion",
    });
  }
});

export default router;
```

#### 4. Utilisation dans les routes

```javascript
import express from "express";
import { verifyToken, checkRole } from "./middleware/auth.js";
import db from "./config/db.js";

const router = express.Router();

/**
 * Route PROTÉGÉE : Rechercher des élèves
 * GET /api/eleves/search?nom=Dupont
 * Nécessite : Token JWT valide
 */
router.get("/search", verifyToken, async (req, res) => {
  try {
    const { nom, prenom } = req.query;

    // À partir d'ici, req.user contient les infos de l'utilisateur authentifié
    console.log(`Recherche effectuée par: ${req.user.email}`);

    // Construire la requête SQL
    let query = "SELECT * FROM eleves WHERE 1=1";
    const params = [];

    if (nom) {
      query += " AND nom LIKE ?";
      params.push(`%${nom}%`);
    }

    if (prenom) {
      query += " AND prenom LIKE ?";
      params.push(`%${prenom}%`);
    }

    const [results] = await db.execute(query, params);

    return res.status(200).json({
      success: true,
      message: "Recherche réussie",
      data: results,
    });
  } catch (error) {
    console.error("Erreur:", error);
    res.status(500).json({
      success: false,
      message: "Erreur serveur",
    });
  }
});

/**
 * Route PROTÉGÉE avec VÉRIFICATION DE RÔLE
 * GET /api/sanctions
 * Nécessite : Token JWT valide + Rôle "direction"
 */
router.get(
  "/sanctions",
  verifyToken,
  checkRole("direction"),
  async (req, res) => {
    try {
      // Seuls les utilisateurs avec le rôle "direction" arrivent ici

      const query = "SELECT * FROM sanctions ORDER BY date DESC LIMIT 50";
      const [results] = await db.execute(query);

      return res.status(200).json({
        success: true,
        data: results,
      });
    } catch (error) {
      res.status(500).json({
        success: false,
        message: "Erreur serveur",
      });
    }
  },
);

export default router;
```

### 4.2 Côté client (CLI avec Node.js)

#### Stockage du token

Après une connexion réussie, stocker le token dans un fichier local :

```javascript
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const TOKEN_FILE = path.join(__dirname, ".token");
const CONFIG_FILE = path.join(__dirname, ".config.json");

/**
 * Sauvegarder le token après la connexion
 * @param {string} token - Le JWT
 * @param {Object} user - Les infos utilisateur
 */
export function saveToken(token, user) {
  try {
    const config = {
      token: token,
      user: {
        id: user.id,
        email: user.email,
        nom: user.nom,
        prenom: user.prenom,
        role: user.role,
      },
      savedAt: new Date().toISOString(),
    };

    fs.writeFileSync(CONFIG_FILE, JSON.stringify(config, null, 2));
    console.log("✅ Token sauvegardé avec succès");
  } catch (error) {
    console.error("❌ Erreur lors de la sauvegarde du token:", error.message);
    throw error;
  }
}

/**
 * Charger le token stocké localement
 * @returns {string|null} - Le token ou null s'il n'existe pas
 */
export function loadToken() {
  try {
    if (!fs.existsSync(CONFIG_FILE)) {
      return null;
    }

    const config = JSON.parse(fs.readFileSync(CONFIG_FILE, "utf8"));
    return config.token;
  } catch (error) {
    return null;
  }
}

/**
 * Obtenir les infos utilisateur stockées
 */
export function getStoredUser() {
  try {
    if (!fs.existsSync(CONFIG_FILE)) {
      return null;
    }

    const config = JSON.parse(fs.readFileSync(CONFIG_FILE, "utf8"));
    return config.user;
  } catch (error) {
    return null;
  }
}

/**
 * Supprimer le token stocké (déconnexion)
 */
export function removeToken() {
  try {
    if (fs.existsSync(CONFIG_FILE)) {
      fs.unlinkSync(CONFIG_FILE);
      console.log("✅ Déconnexion réussie");
    }
  } catch (error) {
    console.error("❌ Erreur lors de la déconnexion:", error.message);
  }
}
```

#### Requête avec le token

Pour chaque requête, inclure le token dans l'en-tête `Authorization` :

```javascript
import fetch from "node-fetch";
import { loadToken } from "./auth-client.js";

const API_URL = "http://localhost:3000/api";

/**
 * Faire une requête authentifiée à l'API
 * @param {string} endpoint - L'endpoint (ex: "/eleves/search")
 * @param {Object} options - Options fetch (method, body, etc.)
 * @returns {Promise<Object>} - La réponse JSON
 */
export async function authenticatedFetch(endpoint, options = {}) {
  try {
    const token = loadToken();

    if (!token) {
      throw new Error("Non authentifié. Veuillez d'abord vous connecter.");
    }

    // Préparer les en-têtes
    const headers = {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
      ...options.headers,
    };

    // Effectuer la requête
    const response = await fetch(`${API_URL}${endpoint}`, {
      ...options,
      headers,
    });

    // Vérifier la réponse
    const data = await response.json();

    if (!response.ok) {
      if (response.status === 401) {
        throw new Error(
          "Session expirée ou token invalide. Veuillez vous re-connecter.",
        );
      }
      throw new Error(data.message || `Erreur ${response.status}`);
    }

    return data;
  } catch (error) {
    console.error("❌ Erreur lors de la requête:", error.message);
    throw error;
  }
}

/**
 * Exemple d'utilisation : Rechercher des élèves
 */
export async function searchEleves(nom, prenom) {
  const params = new URLSearchParams();
  if (nom) params.append("nom", nom);
  if (prenom) params.append("prenom", prenom);

  const endpoint = `/eleves/search?${params.toString()}`;
  return authenticatedFetch(endpoint);
}

/**
 * Exemple : Obtenir les sanctions
 */
export async function getSanctions() {
  return authenticatedFetch("/sanctions");
}
```

#### Commande de connexion

Exemple avec la librarie `commander.js` :

```javascript
import { program } from "commander";
import fetch from "node-fetch";
import { saveToken, removeToken, getStoredUser } from "./auth-client.js";

const API_URL = "http://localhost:3000/api";

/**
 * Commande LOGIN
 */
program
  .command("login <email> <password>")
  .description("Se connecter à l'API")
  .action(async (email, password) => {
    try {
      console.log("🔐 Tentative de connexion...");

      const response = await fetch(`${API_URL}/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Erreur de connexion");
      }

      // Sauvegarder le token
      saveToken(data.data.token, data.data.user);

      console.log(`✅ Connecté en tant que: ${data.data.user.email}`);
      console.log(`⏱️  Token expire dans: ${data.data.expiresIn} secondes`);
    } catch (error) {
      console.error("❌ Erreur:", error.message);
      process.exit(1);
    }
  });

/**
 * Commande LOGOUT
 */
program
  .command("logout")
  .description("Se déconnecter")
  .action(() => {
    removeToken();
  });

/**
 * Commande ME (Afficher l'utilisateur courant)
 */
program
  .command("me")
  .description("Afficher les infos de l'utilisateur connecté")
  .action(() => {
    const user = getStoredUser();

    if (!user) {
      console.log("❌ Non connecté. Utilisez: login <email> <password>");
      process.exit(1);
    }

    console.log("📋 Utilisateur connecté:");
    console.table(user);
  });

program.parse();
```

---

## 5. Sécurité et bonnes pratiques {#sécurité}

### 5.1 Bon pratiques de sécurité

#### ✅ À FAIRE

| Pratique              | Description                                     | Exemple                                  |
| --------------------- | ----------------------------------------------- | ---------------------------------------- |
| **HTTPS obligatoire** | Toujours transmettre les JWT via HTTPS          | En production: `https://api.exemple.fr`  |
| **Secret fort**       | Clé secrète complexe et aléatoire               | `crypto.randomBytes(32).toString('hex')` |
| **Expiration courte** | Durée de vie limitée (15min à 1h)               | `JWT_EXPIRES_IN=3600`                    |
| **Variables d'env**   | Stocker les secrets dans .env                   | `JWT_SECRET=xxx` dans `.env`             |
| **Refresh tokens**    | Utiliser des refresh tokens pour la reconnexion | Vérifier plus bas ↓                      |
| **Vérifier en BD**    | Vérifier que l'utilisateur existe toujours      | `User.findById(token.userId)`            |
| **Logs**              | Tracer les authentifications                    | Log chaque login/logout                  |
| **CORS**              | Configurer CORS correctement                    | Autoriser les origines spécifiques       |
| **httpOnly**          | Pour les cookies : utiliser httpOnly            | `Set-Cookie: token=xxx; HttpOnly`        |
| **Versioning**        | Versioner les endpoints auth                    | `/api/v1/auth/login`                     |

#### ⚠️ LIMITATIONS DU JWT

| Limitation           | Problème                             | Solution                                  |
| -------------------- | ------------------------------------ | ----------------------------------------- |
| **Non révocable**    | Token reste valide jusqu'à exp       | Vérifier utilisateur en BD régulièrement  |
| **Taille**           | Payload grand = gros token           | Limiter les claims dans le payload        |
| **Secret compromis** | Tous les tokens deviennent invalides | Utiliser secrets différents/env           |
| **Pas de stateful**  | Impossible de revoquer immédiatement | Utiliser une whitelist/blacklist en Redis |

### 5.2 Pattern Refresh Token

Pour améliorer la sécurité, utiliser deux tokens :

- **Access Token** : JWT court (15 minutes) pour accéder aux ressources
- **Refresh Token** : Token long (7 jours) pour obtenir un nouveau access token

```
┌──────────────────────────────────────────┐
│     PATTERN REFRESH TOKEN                │
└──────────────────────────────────────────┘

1. CONNEXION
   Client → POST /auth/login
   Serveur → Retourne:
            - accessToken (JWT court - 15min)
            - refreshToken (token long - 7j)

2. REQUÊTE NORMALE
   Client → GET /api/eleves
            Authorization: Bearer <accessToken>
   Serveur → Répond si accessToken valide

3. EXPIRATION DU ACCESS TOKEN
   Client → GET /api/eleves
            Authorization: Bearer <accessToken expiré>
   Serveur → 401 Token expiré

4. REFRESH DU TOKEN
   Client → POST /auth/refresh
            refreshToken: <refreshToken>
   Serveur → Génère nouveau accessToken
            → Retourne nouveau accessToken

5. NOUVELLE REQUÊTE
   Client → GET /api/eleves
            Authorization: Bearer <nouveauAccessToken>
   Serveur → Répond
```

**Implémentation du refresh token :**

```javascript
/**
 * Endpoint pour rafraîchir le token
 * POST /api/auth/refresh
 */
router.post("/refresh", async (req, res) => {
  try {
    const { refreshToken } = req.body;

    if (!refreshToken) {
      return res.status(401).json({
        message: "Refresh token manquant",
      });
    }

    // Vérifier le refresh token
    const decoded = jwt.verify(refreshToken, process.env.REFRESH_TOKEN_SECRET);

    // Récupérer l'utilisateur
    const [rows] = await db.execute("SELECT * FROM utilisateurs WHERE id = ?", [
      decoded.userId,
    ]);

    if (rows.length === 0) {
      return res.status(401).json({ message: "Utilisateur introuvable" });
    }

    // Générer un nouveau access token
    const newAccessToken = generateToken(rows[0]);

    res.status(200).json({
      accessToken: newAccessToken,
      expiresIn: 900, // 15 minutes
    });
  } catch (error) {
    res.status(401).json({ message: "Refresh token invalide" });
  }
});
```

### 5.3 Gestion des erreurs

Gérer les différentes erreurs de token correctement :

```javascript
/**
 * Gestion complète des erreurs de JWT
 */
function verifyTokenWithDetails(token) {
  try {
    return jwt.verify(token, SECRET_KEY);
  } catch (error) {
    const errorResponse = {
      type: error.name,
      message: error.message,
      statusCode: 401,
    };

    if (error.name === "TokenExpiredError") {
      errorResponse.message = "Token expiré";
      errorResponse.expiredAt = error.expiredAt;
      errorResponse.statusCode = 401;
      errorResponse.suggestion =
        "Utilisez le refresh token pour obtenir un nouveau token";
    } else if (error.name === "JsonWebTokenError") {
      errorResponse.message = "Token malformé ou signature invalide";
      errorResponse.statusCode = 401;
    } else if (error.name === "NotBeforeError") {
      errorResponse.message = "Token non encore valide";
      errorResponse.notBefore = error.date;
      errorResponse.statusCode = 401;
    }

    throw errorResponse;
  }
}
```

---

## 6. Cas d'usage pratique {#cas-usage}

### Projet Sanctions API REST

Dans le contexte du projet **Sanctions API REST**, les JWT sont utilisés de la manière suivante :

#### Architecture générale

```
┌─────────────────┐           ┌──────────────────┐
│   CLI Client    │           │   API REST       │
│  (Commander.js) │◄─────────►│  (Node + Express)│
└─────────────────┘           └──────────────────┘
       │                                │
       │ 1. login <email> <pwd>        │
       ├───────────────────────────────►│
       │                           Cherche utilisateur
       │                           Valide mot de passe
       │                           Génère JWT
       │ 2. JWT                        │
       │◄───────────────────────────────┤
       │ 3. Stocke dans .config.json    │
       │                                │
       │ 4. GET /api/sanctions          │
       │    Authorization: Bearer JWT   │
       ├───────────────────────────────►│
       │                           Vérifie JWT
       │                           Décode payload
       │                           Retourne sanctions
       │ 5. Sanctions JSON              │
       │◄───────────────────────────────┤
```

#### Endpoints protégés

```
POST /api/auth/login
├─ Public (pas d'auth requise)
├─ Paramètres: { email, password }
└─ Retour: { token, expiresIn, user }

GET /api/eleves/search?nom=...
├─ Privé (JWT requis)
├─ En-tête: Authorization: Bearer <token>
└─ Retour: Array d'élèves

GET /api/sanctions
├─ Privé (JWT requis)
├─ En-tête: Authorization: Bearer <token>
└─ Retour: Array de sanctions

GET /api/eleves/:id/historique
├─ Privé (JWT requis)
├─ En-tête: Authorization: Bearer <token>
└─ Retour: Historique des sanctions de l'élève
```

#### Workflow complet

```javascript
// 1. CONNEXION
async function commandLogin(email, password) {
  const response = await fetch("http://api.local/api/auth/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password }),
  });

  const { token } = await response.json();
  saveToken(token); // Stocké dans .config.json
  console.log("✅ Connecté");
}

// 2. REQUÊTE AVEC TOKEN
async function commandSearchEleves(nom) {
  const token = loadToken();

  const response = await fetch(
    `http://api.local/api/eleves/search?nom=${nom}`,
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    },
  );

  const eleves = await response.json();
  console.table(eleves);
}

// 3. DÉCONNEXION
function commandLogout() {
  removeToken(); // Supprime le fichier .config.json
  console.log("✅ Déconnecté");
}
```

---

## 7. Dépannage courant {#dépannage}

### Problèmes et solutions

#### ❌ Erreur : "Token manquant"

**Cause :** L'en-tête `Authorization` n'est pas envoyé

**Solution :**

```javascript
// ❌ MAUVAIS
fetch("/api/sanctions");

// ✅ BON
fetch("/api/sanctions", {
  headers: {
    Authorization: `Bearer ${token}`,
  },
});
```

#### ❌ Erreur : "Token expiré"

**Cause :** Le JWT a dépassé sa durée de vie (`exp`)

**Solution :**

```javascript
// 1. Vérifier la durée du token
console.log(new Date(decoded.exp * 1000)); // Quand il expire

// 2. Augmenter la durée (mais ce n'est pas recommandé)
JWT_EXPIRES_IN = 86400; // 24 heures au lieu de 1 heure

// 3. Utiliser un refresh token (recommandé)
const newToken = await fetch("/api/auth/refresh", {
  method: "POST",
  body: JSON.stringify({ refreshToken }),
});
```

#### ❌ Erreur : "Signature invalide"

**Cause :** La clé secrète est différente entre le serveur qui génère et celui qui vérifie

**Solution :**

```javascript
// Vérifier que les deux serveurs utilisent la même clé
// .env serveur 1
JWT_SECRET=abc123...

// .env serveur 2
JWT_SECRET=abc123...  // IDENTIQUE

// Ou recréer le token
const newToken = generateToken(user); // Avec la bonne clé
```

#### ❌ Erreur : ".config.json non trouvé" (côté client)

**Cause :** Le fichier de config n'existe pas (pas encore connecté)

**Solution :**

```javascript
const token = loadToken();

if (!token) {
  console.error("❌ Non connecté. Utilisez: login <email> <password>");
  process.exit(1);
}
```

#### ❌ Erreur : "CORS error"

**Cause :** L'API n'autorise pas les requêtes du client

**Solution (côté serveur):**

```javascript
import cors from "cors";

app.use(
  cors({
    origin: "http://localhost:3001",
    credentials: true,
  }),
);
```

### Outils de débogage

#### 1. Site jwt.io

Décoder et inspecter un JWT en ligne :

- Aller sur https://jwt.io
- Coller le token
- Voir le header, payload et signature

#### 2. Décoder dans Node.js

```javascript
import jwt from "jsonwebtoken";

const token = "eyJ...";

// Décoder SANS vérifier (juste pour voir le contenu)
const decoded = jwt.decode(token);
console.log(decoded);

// Décoder ET vérifier
try {
  const verified = jwt.verify(token, SECRET_KEY);
  console.log("✅ Token valide");
  console.log(verified);
} catch (error) {
  console.log("❌ Token invalide:", error.message);
}
```

#### 3. Logs détaillés

```javascript
export function verifyTokenWithLogs(token) {
  console.log("🔍 Vérification du token");
  console.log(`Token: ${token.substring(0, 50)}...`);

  try {
    const decoded = jwt.verify(token, SECRET_KEY);
    console.log(`✅ Token valide`);
    console.log(`   - User ID: ${decoded.userId}`);
    console.log(`   - Email: ${decoded.email}`);
    console.log(`   - Expire le: ${new Date(decoded.exp * 1000)}`);
    return decoded;
  } catch (error) {
    console.log(`❌ Erreur: ${error.message}`);
    throw error;
  }
}
```

---

## Résumé et points clés

### 🎯 À retenir absolument

| Concept             | Explication                                                 |
| ------------------- | ----------------------------------------------------------- |
| **JWT = 3 parties** | Header.Payload.Signature, toutes en Base64URL               |
| **Stateless**       | Le serveur n'a pas besoin de stocker les sessions           |
| **Auto-contenu**    | Toutes les infos nécessaires sont dans le token             |
| **Signé**           | La signature garantit l'intégrité (pas de falsification)    |
| **Expiration**      | Les tokens ont toujours une durée de vie limitée            |
| **Bearer token**    | Format HTTP : `Authorization: Bearer <token>`               |
| **Pas chiffré**     | Le payload est encodé mais lisible (pas de secrets dedans!) |
| **Vérification**    | Le serveur valide signature + expiration à chaque requête   |

### 📋 Checklist de sécurité

- [ ] Utiliser HTTPS en production
- [ ] Générer un secret fort aléatoire
- [ ] Configurer une expiration courte (15min à 1h)
- [ ] Stocker les secrets dans des variables d'env
- [ ] Ne pas committer le `.env` en Git
- [ ] Ajouter un `.gitignore` contenant `.env`
- [ ] Vérifier que l'utilisateur existe toujours en BD (optionnel mais recommandé)
- [ ] Implémenter les refresh tokens pour meilleure UX
- [ ] Gérer les erreurs de token correctement
- [ ] Logger les authentifications
- [ ] Tester le refresh token et l'expiration

### 🔗 Ressources

- **RFC 7519** : https://tools.ietf.org/html/rfc7519 (spécification JWT)
- **jwt.io** : https://jwt.io (décodeur et explication visuelle)
- **jsonwebtoken npm** : https://www.npmjs.com/package/jsonwebtoken
- **OWASP JWT Cheat Sheet** : https://cheatsheetseries.owasp.org/cheatsheets/JSON_Web_Token_for_Java_Cheat_Sheet.html

---

**Fin du cours.** Vous avez maintenant une compréhension complète des JWT et comment les implémenter en Node.js/Express ! 🚀
