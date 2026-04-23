# API REST Sanctions - Gestion des Élèves et Sanctions

API REST développée avec Express.js pour la gestion des sanctions scolaires, destinée aux personnels de direction (proviseur, proviseur adjoint, secrétaire).

## 📋 Description

Cette API permet de gérer et consulter les données relatives aux élèves et leurs sanctions dans un établissement scolaire. Elle offre des fonctionnalités de recherche d'élèves et de consultation d'historiques de sanctions avec filtrage par dates.

## 🚀 Fonctionnalités

### Implémentées

- **Recherche d'élèves** : Recherche par nom et/ou prénom avec filtres flexibles
- **Historique des sanctions** : Consultation des sanctions d'un élève avec filtrage par période (from/to)
- **Base de données MySQL** : Gestion des entités (élèves, classes, sanctions, professeurs)
- **Docker** : Conteneurisation complète de l'application

### En développement

- Authentification JWT (middleware préparé)
- Dashboard avec indicateurs clés
- Gestion complète CRUD des sanctions

## 🛠️ Technologies

- **Backend** : Node.js + Express.js 5.2
- **Base de données** : MySQL 8.0
- **Authentification** : JWT (jsonwebtoken)
- **Sécurité** : bcrypt pour le hashage des mots de passe
- **Conteneurisation** : Docker + Docker Compose
- **Développement** : Nodemon pour le hot-reload

## 📦 Prérequis

- [Docker](https://www.docker.com/) et Docker Compose
- Node.js 18+ (si exécution en local sans Docker)
- Un client REST (VS Code REST Client, Postman, Insomnia...)

## 🔧 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/BTS-SIO-2025-2026/sanctions-api-rest-METTETALE.git
cd sanctions-api-rest-METTETALE
```

### 2. Configuration

Créer un fichier `.env` dans le dossier `code/` :

```env
# Port de l'API
PORT=4500

# Configuration MySQL
MYSQL_ROOT_PASSWORD=root
DB_NAME=db_sanctions
DB_USER=user
DB_PASSWORD=password
DB_HOST=db
DB_PORT=3306

# JWT (à configurer pour l'authentification)
JWT_SECRET=your_super_secret_key_here
```

### 3. Lancement avec Docker

```bash
cd code
docker compose up -d
```

L'API sera accessible sur `http://localhost:4500`

La base de données sera automatiquement initialisée avec le schéma et les données de test (`init.sql`).

### 4. Vérification

Vérifier que les conteneurs sont bien lancés :

```bash
docker ps
```

Vérifier les logs :

```bash
docker compose logs -f
```

## 📡 Endpoints API

### Base URL

```
http://localhost:4500/api
```

### Recherche d'élèves

```http
GET /api/eleves?nom={nom}&prenom={prenom}
```

**Query Parameters:**

- `nom` (optionnel) : Nom de l'élève (recherche partielle insensible à la casse)
- `prenom` (optionnel) : Prénom de l'élève (recherche partielle insensible à la casse)

**Note:** Au moins un des deux paramètres (nom ou prenom) est requis.

**Réponse (200 OK):**

```json
{
  "eleves": [
    {
      "id": 2,
      "nom": "Mettetal",
      "prenom": "Ethann",
      "date_naissance": "11/10/2006",
      "classe": {
        "id": 2,
        "libelle": "BTS SIO"
      }
    }
  ]
}
```

**Erreur (400 Bad Request):**

```json
{
  "message": "Au moins un filtre est requis : nom et/ou prenom"
}
```

### Historique des sanctions d'un élève

```http
GET /api/eleves/:id/sanctions?from={date}&to={date}
```

**Path Parameters:**

- `id` (requis) : ID de l'élève

**Query Parameters:**

- `from` (optionnel) : Date de début (format: YYYY/MM/DD ou YYYY-MM-DD)
- `to` (optionnel) : Date de fin (format: YYYY/MM/DD ou YYYY-MM-DD)

**Règles de filtrage:**

- Si `from` est fourni sans `to`, alors `to` = date du jour
- `to` ne peut pas être utilisé seul (nécessite `from`)
- `from` doit être antérieur ou égal à `to`

**Réponse (200 OK):**

```json
{
  "eleve": {
    "id": 2,
    "nom": "Mettetal",
    "prenom": "Ethann",
    "date_naissance": "11/10/2006",
    "classe": {
      "id": 2,
      "libelle": "BTS SIO"
    }
  },
  "sanctions": [
    {
      "id": 1,
      "type": "Avertissement",
      "date": "15/12/2025",
      "motif": "Retard répété"
    }
  ]
}
```

**Erreur (404 Not Found):**

```json
{
  "message": "Élève introuvable"
}
```

## 🧪 Tests

Des fichiers de tests HTTP sont disponibles dans `code/test/` :

- `eleves.http` : Tests pour la recherche d'élèves
- `sanctions.http` : Tests pour les sanctions

Pour les utiliser avec VS Code, installer l'extension [REST Client](https://marketplace.visualstudio.com/items?itemName=humao.rest-client).

## 📁 Structure du projet

```
code/
├── config/
│   └── db.js              # Configuration connexion MySQL
├── middleware/
│   └── middleware.js      # Middleware d'authentification (TODO)
├── routes/
│   └── sanctions-db-routes.js  # Routes API
├── test/
│   ├── eleves.http        # Tests recherche élèves
│   └── sanctions.http     # Tests sanctions
├── .env                   # Variables d'environnement
├── docker-compose.yml     # Configuration Docker
├── index.js              # Point d'entrée de l'application
├── init.sql              # Script d'initialisation DB
└── package.json          # Dépendances Node.js

documentation/
├── api-rest-cahier-des-charges.md  # Cahier des charges
├── api-rest-sprint1.md             # Documentation Sprint 1
├── schema-bd.md                    # Schéma de la base de données
└── sprint1/
    ├── US1-authentification.md
    ├── US3-historique-sanctions-eleve.md
    └── US4-recherche-eleves.md
```

## 🗄️ Base de données

### Entités principales

- **classes** : Classes de l'établissement (BTS, Terminale, Première...)
- **eleves** : Informations sur les élèves (nom, prénom, date de naissance, classe)
- **sanctions** : Sanctions appliquées aux élèves
- **professeurs** : Personnel enseignant
- **utilisateurs** : Comptes pour l'authentification

Le schéma complet est défini dans `code/init.sql`.

## 🔐 Sécurité

- JWT pour l'authentification (en cours d'implémentation)
- Hashage des mots de passe avec bcrypt
- Validation des entrées utilisateur
- Gestion des erreurs SQL

## 🚧 Développement

### Mode développement (sans Docker)

```bash
cd code
npm install
npm run dev
```

### Commandes utiles

```bash
# Arrêter les conteneurs
docker compose down

# Arrêter et supprimer les volumes (réinitialiser la DB)
docker compose down -v

# Voir les logs
docker compose logs -f

# Redémarrer uniquement l'API
docker compose restart
```

## 📝 Roadmap

- [ ] Implémentation complète de l'authentification JWT
- [ ] Dashboard avec indicateurs globaux
- [ ] CRUD complet pour les sanctions (POST, PUT, DELETE)
- [ ] Gestion des professeurs
- [ ] Gestion des classes
- [ ] Filtres avancés multi-critères
- [ ] Export des données (PDF, CSV)

## 👤 Auteur

**Mettetal Ethann** - BTS SIO

## 📄 Licence

ISC

---

**Note:** Ce projet est développé dans le cadre d'un projet pédagogique BTS SIO.
