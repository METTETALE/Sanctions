# 🔐 Guide d'installation JWT dans le projet Sanctions API

## 📋 Vue d'ensemble

Ce guide vous accompagne dans l'intégration complète de **JSON Web Tokens (JWT)** à votre API REST. Ce système permettra de sécuriser les endpoints selon vos User Stories, notamment l'**US-A1 (Connexion avec token)**.

---

## 📦 Étape 1 : Installation des dépendances

### 1.1 Installer les packages nécessaires

Ouvrez votre terminal et exécutez :

```bash
npm install jsonwebtoken bcryptjs
npm install --save-dev nodemon
```

**Explication :**

- **`jsonwebtoken`** : Crée et vérifie les JWT
- **`bcryptjs`** : Hash les mots de passe de manière sécurisée
- **`nodemon`** : Relance automatiquement votre serveur lors des modifications (déjà présent)

### 1.2 Vérifier les dépendances

Votre `package.json` devrait ressembler à ceci :

```json
{
  "dependencies": {
    "body-parser": "^2.2.2",
    "dotenv": "^17.2.3",
    "express": "^5.2.1",
    "mysql2": "^3.16.1",
    "jsonwebtoken": "^9.x.x",
    "bcryptjs": "^2.4.x"
  },
  "devDependencies": {
    "nodemon": "^3.1.11"
  }
}
```

---

## 🔑 Étape 2 : Configuration JWT

### 2.1 Ajouter les variables d'environnement

Créez ou modifiez votre fichier `.env` à la racine du projet :

```env
# Existing config
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=yourpassword
DB_NAME=sanctions_db
PORT=3000

# New JWT config
JWT_SECRET=votre_cle_secrete_tres_longue_et_aleatoire_au_moins_32_caracteres
JWT_EXPIRES_IN=24h
```

⚠️ **IMPORTANT** :

- Remplacez `votre_cle_secrete_...` par une clé aléatoire longue (minimum 32 caractères)
- Utilisez une clé différente en production
- **Ne versionnez JAMAIS le `.env` en production**

### 2.2 Générer une clé secrète robuste (optionnel)

En Node.js, générez une clé sécurisée :

```javascript
// À exécuter une seule fois dans votre terminal Node
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
```

Copié-collez le résultat dans votre `.env` comme `JWT_SECRET`.

---

## 🛠️ Étape 3 : Créer les middlewares d'authentification

### 3.1 Créer le dossier middleware

Créez un dossier `middleware` à la racine du projet :

```
middleware/
  ├── auth.js          (vérification JWT)
  └── errorHandler.js  (gestion d'erreurs)
```

### 3.2 Créer le middleware JWT

Créez le fichier `middleware/auth.js` :

```javascript
import jwt from "jsonwebtoken";
import "dotenv/config";

/**
 * Middleware de vérification du JWT
 * Ajoute les informations de l'utilisateur à request.user
 */
export const verifyToken = (request, response, next) => {
  try {
    // Récupérer le token depuis l'en-tête Authorization
    const authHeader = request.headers.authorization;

    if (!authHeader) {
      return response.status(401).json({
        message: "Token manquant. Format attendu: Bearer <token>",
      });
    }

    // Format attendu : "Bearer <token>"
    const token = authHeader.split(" ")[1];

    if (!token) {
      return response.status(401).json({
        message: "Token invalide. Utilisez le format: Bearer <token>",
      });
    }

    // Vérifier et décoder le token
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    request.user = decoded; // Ajouter les données du token au request

    next();
  } catch (error) {
    if (error.name === "TokenExpiredError") {
      return response.status(401).json({
        message: "Token expiré. Veuillez vous reconnecter.",
      });
    }

    if (error.name === "JsonWebTokenError") {
      return response.status(403).json({
        message: "Token invalide",
      });
    }

    return response.status(500).json({
      message: "Erreur lors de la vérification du token",
    });
  }
};

/**
 * Générer un JWT
 * @param {Object} payload - Données à encoder (ex: {id: 1, email: 'user@example.com'})
 * @returns {String} Token JWT
 */
export const generateToken = (payload) => {
  return jwt.sign(payload, process.env.JWT_SECRET, {
    expiresIn: process.env.JWT_EXPIRES_IN || "24h",
  });
};
```

### 3.3 Créer le middleware de gestion d'erreurs (optionnel)

Créez le fichier `middleware/errorHandler.js` :

```javascript
/**
 * Middleware de gestion centralisée des erreurs
 */
export const errorHandler = (error, request, response, next) => {
  console.error("Erreur :", error);

  return response.status(error.statusCode || 500).json({
    message: error.message || "Erreur interne du serveur",
    error: process.env.NODE_ENV === "development" ? error : {},
  });
};
```

---

## 🔐 Étape 4 : Créer l'endpoint de connexion

### 4.1 Créer la route d'authentification

Créez le fichier `routes/auth-routes.js` :

```javascript
import express from "express";
import bcrypt from "bcryptjs";
import pool from "../config/db.js";
import { generateToken } from "../middleware/auth.js";

const router = express.Router();

/**
 * POST /api/auth/login
 * Authentifie un utilisateur et retourne un JWT
 *
 * Body:
 * {
 *   "email": "user@example.com",
 *   "password": "password123"
 * }
 */
router.post("/login", async (request, response) => {
  try {
    const { email, password } = request.body;

    // Validation des entrées
    if (!email || !password) {
      return response.status(400).json({
        message: "Email et mot de passe requis",
      });
    }

    // Chercher l'utilisateur dans la base de données
    // NOTE: Adapter la requête selon votre schéma (table utilisateurs, administrateurs, etc.)
    const [users] = await pool.execute(
      "SELECT id, email, password, nom, prenom FROM utilisateurs WHERE email = :email LIMIT 1",
      { email },
    );

    if (users.length === 0) {
      return response.status(401).json({
        message: "Email ou mot de passe incorrect",
      });
    }

    const user = users[0];

    // Vérifier le mot de passe
    const isPasswordValid = await bcrypt.compare(password, user.password);
    if (!isPasswordValid) {
      return response.status(401).json({
        message: "Email ou mot de passe incorrect",
      });
    }

    // Générer le JWT
    const token = generateToken({
      id: user.id,
      email: user.email,
      nom: user.nom,
      prenom: user.prenom,
    });

    response.status(200).json({
      message: "Connexion réussie",
      token,
      user: {
        id: user.id,
        email: user.email,
        nom: user.nom,
        prenom: user.prenom,
      },
    });
  } catch (error) {
    console.error("Erreur lors de la connexion :", error);
    response.status(500).json({
      message: "Erreur serveur lors de la connexion",
    });
  }
});

/**
 * POST /api/auth/register
 * Crée un nouvel utilisateur (optionnel)
 */
router.post("/register", async (request, response) => {
  try {
    const { email, password, nom, prenom } = request.body;

    if (!email || !password || !nom || !prenom) {
      return response.status(400).json({
        message: "Email, mot de passe, nom et prénom requis",
      });
    }

    // Vérifier que l'email n'existe pas déjà
    const [existingUsers] = await pool.execute(
      "SELECT id FROM utilisateurs WHERE email = :email LIMIT 1",
      { email },
    );

    if (existingUsers.length > 0) {
      return response.status(409).json({
        message: "Email déjà utilisé",
      });
    }

    // Hash le mot de passe
    const hashedPassword = await bcrypt.hash(password, 10);

    // Insérer l'utilisateur
    await pool.execute(
      "INSERT INTO utilisateurs (email, password, nom, prenom) VALUES (:email, :password, :nom, :prenom)",
      { email, password: hashedPassword, nom, prenom },
    );

    response.status(201).json({
      message: "Utilisateur créé avec succès",
    });
  } catch (error) {
    console.error("Erreur lors de l'inscription :", error);
    response.status(500).json({
      message: "Erreur serveur lors de l'inscription",
    });
  }
});

export default router;
```

---

## 🚀 Étape 5 : Intégrer JWT dans votre serveur principal

### 5.1 Modifier `index.js`

Mettez à jour votre fichier `index.js` :

```javascript
import eleves from "./routes/sanctions-db-routes.js";
import auth from "./routes/auth-routes.js";

import "dotenv/config";
import express from "express";
import bodyParser from "body-parser";
import { verifyToken } from "./middleware/auth.js";
import { errorHandler } from "./middleware/errorHandler.js";

const app = express();
const PORT = process.env.PORT;

// Middlewares
app.use(bodyParser.json());

// Routes publiques (sans authentification)
app.use("/api/auth", auth);

// Routes protégées (nécessitent un JWT)
app.use("/api/eleves", verifyToken, eleves);

// Middleware de gestion d'erreurs (à la fin)
app.use(errorHandler);

// Fallback pour les routes non trouvées
app.use(function (request, response) {
  response.status(404).json({
    message: "Route non trouvée",
  });
});

app.listen(PORT, function () {
  console.log(`Serveur démarré sur le port ${PORT}`);
});
```

---

## ✅ Étape 6 : Tester votre implémentation JWT

### 6.1 Tester via REST Client

Créez ou modifiez votre fichier `tests/auth.http` :

```http
### Test de connexion
POST http://localhost:3000/api/auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}

### Récupérer les élèves (avec token)
@token = eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

GET http://localhost:3000/api/eleves?nom=Dupont
Authorization: Bearer @token

### Récupérer les sanctions d'un élève
GET http://localhost:3000/api/eleves/1/sanctions
Authorization: Bearer @token
```

### 6.2 Tester via cURL (en ligne de commande)

```bash
# 1. Récupérer un token
curl -X POST http://localhost:3000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'

# 2. Utiliser le token pour accéder à une route protégée
curl -X GET http://localhost:3000/api/eleves?nom=Dupont \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🛡️ Étape 7 : Hashage des mots de passe existants (IMPORTANT !)

Si vous avez déjà des utilisateurs en base de données, vous devez hasher leurs mots de passe.

### 7.1 Script de migration (à exécuter une seule fois)

Créez le fichier `scripts/hash-passwords.js` :

```javascript
import bcrypt from "bcryptjs";
import pool from "../config/db.js";
import "dotenv/config";

async function hashExistingPasswords() {
  try {
    const [users] = await pool.execute("SELECT id, password FROM utilisateurs");

    for (const user of users) {
      // Vérifier si le mot de passe est déjà hashé (commence par $2a$, $2b$, etc.)
      if (user.password.startsWith("$2")) {
        console.log(`User ${user.id}: mot de passe déjà hashé`);
        continue;
      }

      // Hasher le mot de passe
      const hashedPassword = await bcrypt.hash(user.password, 10);
      await pool.execute(
        "UPDATE utilisateurs SET password = :password WHERE id = :id",
        { password: hashedPassword, id: user.id },
      );

      console.log(`User ${user.id}: mot de passe hashé avec succès`);
    }

    console.log("✅ Migration terminée");
    process.exit(0);
  } catch (error) {
    console.error("❌ Erreur lors de la migration :", error);
    process.exit(1);
  }
}

hashExistingPasswords();
```

Exécutez-le :

```bash
node scripts/hash-passwords.js
```

---

## 🔒 Étape 8 : Protéger les routes selon vos besoins

### 8.1 Routes publiques vs protégées

**Routes publiques** (sans authentification) :

- `POST /api/auth/login` ✅ Connexion
- `POST /api/auth/register` ✅ Inscription

**Routes protégées** (nécessitent un JWT) :

- `GET /api/eleves` 🔒 Liste des élèves
- `GET /api/eleves/:id/sanctions` 🔒 Sanctions d'un élève
- `GET /api/dashboard` 🔒 Dashboard (US-B1)
- `POST /api/sanctions` 🔒 Créer une sanction (US-C1)

### 8.2 Ajouter le middleware à des routes spécifiques

Vous pouvez protéger seulement certaines routes :

```javascript
import { verifyToken } from "./middleware/auth.js";

// Route publique
app.get("/api/auth/public-info", (request, response) => {
  response.json({ message: "Info publique" });
});

// Route protégée
app.get("/api/eleves", verifyToken, (request, response) => {
  // request.user contient les infos du token
  response.json({ userId: request.user.id });
});
```

---

## 📋 Checklist d'implémentation

- [ ] ✅ Installer `jsonwebtoken` et `bcryptjs`
- [ ] ✅ Configurer les variables d'environnement (`.env`)
- [ ] ✅ Créer le middleware `auth.js`
- [ ] ✅ Créer les routes d'authentification (`auth-routes.js`)
- [ ] ✅ Intégrer JWT dans `index.js`
- [ ] ✅ Hasher les mots de passe existants
- [ ] ✅ Tester avec REST Client ou cURL
- [ ] ✅ Protéger les routes nécessaires
- [ ] ✅ Documenter les endpoints dans Postman/Insomnia

---

## 🐛 Dépannage courant

### "JsonWebTokenError: invalid token"

**Cause** : Le token n'est pas valide ou mal formaté
**Solution** : Vérifiez que vous envoyez le format `Bearer <token>`

### "TokenExpiredError: jwt expired"

**Cause** : Le token a expiré (après 24h par défaut)
**Solution** : L'utilisateur doit se reconnecter

### "Cannot find module 'jsonwebtoken'"

**Cause** : Package non installé
**Solution** : Exécutez `npm install jsonwebtoken`

### "Comparaison de mot de passe échoue"

**Cause** : Mot de passe non hashé à la création
**Solution** : Exécutez le script de migration (Étape 7)

---

## 📚 Ressources complémentaires

- [Documentation officielle JWT](https://jwt.io)
- [jsonwebtoken sur npm](https://www.npmjs.com/package/jsonwebtoken)
- [bcryptjs sur npm](https://www.npmjs.com/package/bcryptjs)
- [Votre cours JWT local](./jwt.md)

---

## 🎯 Prochaines étapes

Après cette implémentation :

1. **Ajouter les roles/permissions** : Créer un middleware pour vérifier les rôles (admin, prof, direction, etc.)
2. **Refresh tokens** : Implémenter un système de refresh token pour prolonger les sessions
3. **Logout** : Implémenter un système de logout (blacklist de tokens)
4. **2FA** : Ajouter l'authentification à deux facteurs pour plus de sécurité

---

**Bonne implémentation ! 🚀**
