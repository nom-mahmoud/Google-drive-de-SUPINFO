# Manuel Utilisateur - SUPFile

Bienvenue sur SUPFile, votre nouvel espace de stockage cloud personnel, sécurisé et rapide.
Ce manuel vous guidera dans vos premiers pas sur l'application.

---

## Sommaire

1. [Inscription et Connexion](#1-inscription-et-connexion)
2. [Interface Principale (Dashboard)](#2-interface-principale)
3. [Gérer vos fichiers et dossiers](#3-gérer-vos-fichiers)
4. [Partage et Collaboration](#4-partage)
5. [Application Mobile](#5-application-mobile)

---

## 1. Inscription et Connexion

Pour utiliser SUPFile, vous avez besoin d'un compte.

### S'inscrire
1. Rendez-vous sur la page d'accueil et cliquez sur **"Créer un compte"**.
2. Remplissez le formulaire avec votre prénom, nom, adresse e-mail et choisissez un mot de passe sécurisé.
3. Cliquez sur **"M'inscrire maintenant"**. Vous bénéficiez instantanément de 30 Go de stockage.

### Se connecter
1. Sur la page de connexion, entrez votre adresse e-mail et votre mot de passe.
2. *Astuce : Vous pouvez également vous connecter en un clic en utilisant votre compte Google ou GitHub.*

---

## 2. Interface Principale (Dashboard)

Une fois connecté, vous arrivez sur votre tableau de bord (Dashboard), conçu sous la forme d'un design "Bento-box" moderne et épuré.

### Structure de l'interface :
- **Barre latérale (Sidebar)** : Permet de naviguer entre le **Dashboard** principal, la **Corbeille**, et les **Paramètres**. C'est aussi ici que vous pouvez changer le thème global de l'application (Sombre / Clair) grâce au bouton de bascule de thème.
- **Espace de stockage (Quota)** : Une jauge visuelle dynamique vous montre en temps réel l'espace disque consommé sur votre quota gratuit de **30 Go**.
- **Zone centrale** : Affiche vos dossiers récents et la liste de vos fichiers importés, avec leurs métadonnées (nom, taille, type de fichier, date d'ajout).
- **Barre de recherche** : Située en haut de la page, elle vous permet de trouver instantanément un fichier ou un dossier par son nom.

---

## 3. Gérer vos fichiers et dossiers

### Créer un dossier
1. Cliquez sur le bouton **"Nouveau dossier"** sur le Dashboard.
2. Saisissez le nom du dossier et validez.
3. Le dossier apparaît immédiatement. Double-cliquez dessus pour y accéder. Un fil d'Ariane (breadcrumb) vous permet de savoir où vous vous situez et de remonter facilement dans l'arborescence.

### Importer un fichier (Upload)
1. Dans le dossier de votre choix, cliquez sur **"Importer un fichier"**.
2. Sélectionnez le fichier sur votre appareil.
3. Une barre de progression s'affiche en temps réel pour suivre le transfert. Une fois l'importation terminée, la liste se met à jour automatiquement.

### Visualiser et Télécharger
- **Téléchargement** : Cliquez sur l'icône de téléchargement à droite d'un fichier ou d'un dossier (les dossiers sont automatiquement compressés et téléchargés sous forme d'archive **ZIP**).
- **Prévisualisation** : Cliquez sur le nom d'un fichier compatible (Images, fichiers PDF, documents texte) pour l'ouvrir directement dans le navigateur sans le télécharger.

### Supprimer et Restaurer (Corbeille)
1. Cliquez sur le bouton de suppression (icône de corbeille) à côté d'un fichier ou dossier pour le déplacer vers la corbeille.
2. Pour restaurer un élément ou le supprimer définitivement, rendez-vous dans l'onglet **"Corbeille"** depuis la barre latérale.

---

## 4. Partage et Collaboration

SUPFile facilite le partage de vos fichiers avec des tiers de manière sécurisée.

### Générer un lien de partage
1. Cliquez sur le bouton **"Partager"** à côté du fichier ou dossier souhaité.
2. Un lien public unique est généré (ex: `http://localhost/s/{token}`).
3. Vous pouvez copier ce lien et l'envoyer par email ou messagerie.

### Accès visiteur
- Les personnes disposant du lien peuvent accéder à une interface de prévisualisation et télécharger directement le fichier partagé sans avoir besoin de créer un compte SUPFile.
- Vous pouvez à tout moment révoquer l'accès en allant dans vos fichiers partagés et en cliquant sur **"Révoquer le lien"**.

---

## 5. Application Mobile (Responsive / PWA)

SUPFile a été pensé dès sa conception pour être une application web progressive (PWA).

### Comment l'utiliser sur mobile :
- Ouvrez le navigateur de votre smartphone et accédez à l'adresse de l'application.
- L'interface s'adapte automatiquement avec un menu tactile épuré, des boutons d'action rapides et une navigation simplifiée.
- Vous pouvez ajouter l'application à l'écran d'accueil de votre téléphone pour l'utiliser comme une application native.
