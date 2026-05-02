# Documentation Technique - SUPFile

## 1. Introduction

SUPFile est une plateforme de stockage et de partage de fichiers dans le cloud (équivalent à Google Drive ou Dropbox), conçue avec une architecture Laravel monolithique modulaire (Web + Mobile PWA) et un stockage persistant via Docker.

## 2. Pré-requis

- **Docker** et **Docker Compose**
- **PHP 8.2+** et **Composer** (pour le développement local hors conteneur)
- **Node.js** et **NPM** (pour la compilation des assets)

## 3. Installation et Déploiement Local

1. Cloner le dépôt :
   ```bash
   git clone <url-du-depot>
   cd supfile
   ```

2. Installer les dépendances :
   ```bash
   composer install
   npm install && npm run build
   ```

3. Configurer l'environnement :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Démarrer l'infrastructure Docker :
   ```bash
   docker-compose up -d
   ```

5. Lancer les migrations de base de données :
   ```bash
   php artisan migrate --seed
   ```

L'application web est maintenant accessible sur `http://localhost`.

## 4. Choix Technologiques

- **Backend** : Laravel (Choisi pour sa robustesse, son système d'ORM Eloquent, et sa gestion native de l'authentification et des files system).
- **Base de données** : PostgreSQL (dans Docker) pour des transactions fiables et l'intégrité des métadonnées.
- **Frontend** : Blade, HTML5, CSS3 vanilla (architecture "Bento-box"). Choisi pour minimiser les dépendances et garantir des temps de chargement records.
- **Déploiement** : Docker et Docker Compose (isolation des services Nginx, PHP-FPM, et Postgres).
