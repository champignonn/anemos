# 🏔️ Anemos

**Plateforme SaaS d'entraînement ultra-trail avec génération intelligente de plans**

## 📋 Description

Anemos est une plateforme complète dédiée aux coureurs de trail et ultra-trail. Elle permet de :

- 📅 Gérer ses courses (trail, ultra, vertical, backyard)
- 🤖 Générer automatiquement des plans d'entraînement personnalisés
- 📊 Suivre ses séances avec métriques détaillées
- 📸 Documenter ses aventures avec des vlogs
- 👥 Partager avec une communauté de passionnés
- 🔗 Synchroniser avec Strava

## ✨ Fonctionnalités Principales

### 🧠 Algorithme Intelligent
Le cœur du projet : un générateur de plans d'entraînement qui :
- Détecte automatiquement le format de course (backyard, vertical, ultra 100km+, etc.)
- Génère 40-60 séances personnalisées sur 8-24 semaines
- Applique une périodisation en 4 phases (Base, Build, Peak, Taper)
- Calcule les charges d'entraînement progressives
- Adapte l'intensité selon le profil utilisateur (VMA, FC, allures)

### 📈 Suivi Complet
- Zones d'intensité (Z1 à Z5) basées sur formule de Karvonen
- Comparaison prévu vs réalisé
- Statistiques personnelles (km cumulés, D+, taux de réussite)
- Graphiques de progression

### 🌍 Découverte de Courses
- Base de données de courses publiques
- Recherche géographique (formule de Haversine)
- Filtres avancés (type, difficulté, distance, ville)

### 👥 Réseau Social
- Timeline style Instagram
- Followers / Following
- Vlogs avec photos et vidéos
- Likes, vues

## 🛠️ Technologies

- **Backend** : Laravel 12 (PHP 8.3)
- **Frontend** : Blade Templates, Tailwind CSS, Alpine.js
- **Database** : MySQL 8.0
- **API** : Strava OAuth 2.0
- **Storage** : Laravel Storage

## 📦 Installation
```bash
# Cloner le repo
git clone https://github.com/champignonn/anemos.git
cd anemos

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Storage
php artisan storage:link

# Lancer le serveur
php artisan serve
npm run dev
```

## 🎓 Projet Portfolio

Ce projet a été réalisé dans le cadre de mon portfolio de développement web.

**Durée** : ~50 heures  
**Lignes de code** : ~15,000  
**Challenge principal** : Conception de l'algorithme de génération de plans d'entraînement

### Le plus grand défi
Le plus grand challenge a été la conception de l'algorithme de planification, car il repose sur des connaissances en **physiologie de l'effort** que je ne possédais pas initialement. J'ai dû :
- Me former en périodisation sportive
- Comprendre les zones cardiaques (formule de Karvonen)
- Étudier les spécificités de chaque format de course
- Calculer des progressions de charge mathématiques
- **~50h de recherche** en complément du développement

N'étant pas coach sportif à la base, cette dimension scientifique a représenté un véritable apprentissage.

## 👩‍💻 Auteur

**Charlotte Duverger**
- GitHub : [@champignonn](https://github.com/champignonn)

## 📄 Licence

Projet éducatif - 2026

---

*Fait avec ❤️ et beaucoup de recherches en physiologie sportive*
