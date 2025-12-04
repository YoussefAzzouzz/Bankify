[![Thumbnail](thumbnail.png)](https://github.com/user-attachments/assets/83a1a972-e002-4fd7-8d73-355461dade62)



# Bankify - Système de Gestion Bancaire

Une application web complète de gestion bancaire basée sur Symfony qui offre des opérations bancaires complètes incluant la gestion de comptes, les transactions, les crédits, les assurances et le traitement des chèques.

## 📋 Fonctionnalités

### Fonctionnalités Bancaires de Base
- **Gestion des Utilisateurs** : Inscription, authentification, gestion de profil avec contrôle d'accès basé sur les rôles (Admin/User)
- **Gestion des Comptes** : Création et gestion de comptes clients avec suivi des soldes et types de comptes
- **Transactions** : Traitement complet des transactions par carte avec suivi des statuts
- **Gestion des Cartes** : Émission et gestion de cartes de crédit/débit (Visa, MasterCard)
- **Virements** : Transferts inter-comptes avec traitement en temps réel

### Fonctionnalités Avancées
- **Système de Crédit** : 
  - Demande et approbation de crédit
  - Multiples catégories de crédit avec limites personnalisables
  - Suivi et gestion des remboursements
  - Calcul des intérêts et durée de remboursement
  
- **Module d'Assurance** :
  - Gestion des polices d'assurance
  - Multiples catégories d'assurance
  - Gestion des agences
  - Suivi de la couverture et des bénéficiaires

- **Gestion des Chèques** :
  - Émission et suivi des chèques
  - Gestion des bénéficiaires favoris
  - Système de réclamation de chèques

### Fonctionnalités de Sécurité
- **Authentification à Deux Facteurs (2FA)** : Protection renforcée via Email et Google Authenticator
- **OAuth 2.0** : Connexion via Google
- **reCAPTCHA v3** : Protection contre les bots et les abus
- **Réinitialisation de Mot de Passe** : Système sécurisé de récupération de compte
- **Vérification d'Email** : Validation des adresses email lors de l'inscription

### Fonctionnalités Supplémentaires
- **Génération PDF** : Génération de rapports pour les transactions et documents bancaires
- **Intégration QR Code** : Génération de codes QR pour les transactions
- **Notifications Email** : Notifications email automatiques pour les événements importants
- **Intégration SMS** : Intégration Twilio pour les notifications SMS
- **Calendrier d'Événements** : Gestion des événements bancaires et rendez-vous
- **Système de Packs** : Offres de services bancaires personnalisées

## 🛠️ Stack Technologique

### Backend
- **PHP** : 7.2.5+
- **Symfony** : 5.4.* (Framework PHP)
- **Doctrine ORM** : 2.4+ (Gestion de base de données)
- **Twig** : 2.12/3.0 (Moteur de templates)

### Base de Données
- **PostgreSQL** : 15 (Base de données principale)
- **MySQL** : Compatible (voir configuration)

### Sécurité & Authentification
- **Symfony Security Bundle** : 5.4.*
- **Scheb 2FA Bundle** : 6.6 (Authentification à deux facteurs)
- **KnpU OAuth2 Client Bundle** : 2.18 (OAuth Google)
- **Karser reCAPTCHA3 Bundle** : 0.1.26

### Bibliothèques Principales
- **DomPDF** : 2.0 (Génération PDF)
- **Endroid QR Code Bundle** : 5.0 (Génération de codes QR)
- **Twilio SDK** : 7.16 (Notifications SMS)
- **KnpPaginator Bundle** : 5.9 (Pagination)
- **Symfony Mailer** : 5.4.* (Envoi d'emails)

### Outils de Développement
- **Symfony Maker Bundle** : 1.0+ (Génération de code)
- **Doctrine Fixtures Bundle** : 3.6 (Données de test)
- **PHPUnit** : 9.5 (Tests unitaires)
- **Symfony Web Profiler** : 5.4.* (Débogage)

### Infrastructure
- **Docker** : Containerisation
- **Docker Compose** : Orchestration des services

## 📁 Structure du Projet

```
Bankify-merge3/
├── assets/                     # Assets frontend (CSS, JS, images)
├── bin/
│   └── console                 # Console Symfony
├── config/                     # Configuration de l'application
│   ├── packages/               # Configuration des bundles
│   └── routes/                 # Configuration des routes
├── migrations/                 # Migrations de base de données
├── public/                     # Point d'entrée web
│   └── index.php
├── src/
│   ├── Command/                # Commandes console
│   ├── Controller/             # Contrôleurs
│   │   ├── AgenceController.php
│   │   ├── AssuranceController.php
│   │   ├── CarteController.php
│   │   ├── ChequeController.php
│   │   ├── CompteClientController.php
│   │   ├── CreditController.php
│   │   ├── GoogleController.php
│   │   ├── RegistrationController.php
│   │   ├── SecurityController.php
│   │   ├── TransactionController.php
│   │   ├── UserController.php
│   │   ├── VirementController.php
│   │   └── ...
│   ├── DataFixtures/           # Fixtures de données
│   │   └── AppFixtures.php
│   ├── Entity/                 # Entités Doctrine
│   │   ├── Agence.php
│   │   ├── Assurance.php
│   │   ├── Carte.php
│   │   ├── CategorieAssurance.php
│   │   ├── CategorieCredit.php
│   │   ├── Cheque.php
│   │   ├── Compte.php
│   │   ├── CompteClient.php
│   │   ├── Credit.php
│   │   ├── Reclamtion.php
│   │   ├── Remboursement.php
│   │   ├── Transaction.php
│   │   ├── User.php
│   │   ├── Virement.php
│   │   └── ...
│   ├── Form/                   # Formulaires Symfony
│   ├── Repository/             # Repositories Doctrine
│   ├── Security/               # Classes de sécurité
│   └── Service/                # Services métier
├── templates/                  # Templates Twig
│   ├── Back/                   # Interface d'administration
│   ├── Front/                  # Interface utilisateur
│   ├── User/                   # Templates utilisateur
│   ├── Compte/
│   ├── Assurance/
│   ├── Cheques/
│   └── ...
├── tests/                      # Tests
├── var/                        # Cache et logs
├── vendor/                     # Dépendances Composer
├── .env                        # Variables d'environnement
├── compose.yaml                # Configuration Docker Compose
├── composer.json               # Dépendances PHP
└── README.md
```

## 🚀 Démarrage

### Prérequis

- **PHP** 7.2.5 ou supérieur
- **Composer** (gestionnaire de dépendances PHP)
- **Docker** et **Docker Compose** (pour la base de données)
- **Symfony CLI** (recommandé mais optionnel)
- **Git** (pour cloner le dépôt)

### Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/votrenomdutilisateur/bankify.git
   cd Bankify-merge3
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configurer les variables d'environnement**
   
   a. Copier le fichier `.env` en `.env.local` :
   ```bash
   cp .env .env.local
   ```
   
   b. Mettre à jour les variables dans `.env.local` :
   ```env
   # Configuration de la base de données
   DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
   
   # Ou pour MySQL
   DATABASE_URL="mysql://root:@127.0.0.1:3306/bankify"
   
   # Configuration Email (Mailtrap pour le développement)
   MAILER_DSN=smtp://username:password@sandbox.smtp.mailtrap.io:25
   
   # Google OAuth (optionnel)
   GOOGLE_ID=votre_google_client_id
   GOOGLE_SECRET=votre_google_client_secret
   GOOGLE_CALLBACK="https://127.0.0.1:8000/connect/google/check"
   
   # reCAPTCHA v3
   RECAPTCHA3_KEY=votre_recaptcha_site_key
   RECAPTCHA3_SECRET=votre_recaptcha_secret_key
   ```

4. **Démarrer la base de données avec Docker**
   ```bash
   docker-compose up -d
   ```

5. **Créer la base de données**
   ```bash
   php bin/console doctrine:database:create
   ```

6. **Exécuter les migrations**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

7. **Charger les données de test (Optionnel)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```
   
   Cela créera :
   - 1 compte administrateur : `admin@bankify.com` / `admin123`
   - 3 comptes utilisateurs : `user1@bankify.com`, `user2@bankify.com`, `user3@bankify.com` / `user123`
   - 3 entrées pour chaque entité (comptes, cartes, transactions, etc.)

8. **Démarrer le serveur de développement**
   ```bash
   # Avec Symfony CLI (recommandé)
   symfony server:start
   
   # Ou avec PHP
   php -S 127.0.0.1:8000 -t public
   ```

9. **Accéder à l'application**
   
   Ouvrez votre navigateur et accédez à : `http://127.0.0.1:8000`

## 🎯 Utilisation

### Connexion par Défaut

Après avoir chargé les fixtures, vous pouvez vous connecter avec :

**Compte Administrateur :**
- Email : `admin@bankify.com`
- Mot de passe : `admin123`

**Comptes Utilisateurs :**
- Email : `user1@bankify.com`, `user2@bankify.com`, `user3@bankify.com`
- Mot de passe : `user123`

### Interfaces Disponibles

- **Interface Frontend** : Interface client pour les opérations bancaires courantes
- **Interface Backend** : Panneau d'administration pour la gestion du système

## 🗄️ Schéma de Base de Données

L'application utilise les tables principales suivantes :

### Gestion des Utilisateurs
- `user` - Comptes utilisateurs et authentification
- `reset_password_request` - Demandes de réinitialisation de mot de passe
- `image` - Photos de profil utilisateur

### Gestion Bancaire
- `compte` - Comptes bancaires génériques
- `compte_client` - Comptes clients détaillés
- `type` - Types de comptes
- `pack` - Packs de services bancaires

### Cartes et Transactions
- `carte` - Cartes de crédit/débit
- `type_c` - Types de cartes (Visa, MasterCard)
- `transaction` - Enregistrements de transactions
- `virement` - Virements d'argent

### Système de Crédit
- `credit` - Demandes de crédit
- `categorie_credit` - Catégories de crédit
- `remboursement` - Enregistrements de remboursement

### Assurances
- `assurance` - Polices d'assurance
- `categorie_assurance` - Catégories d'assurance
- `agence` - Informations sur les agences

### Chèques
- `cheque` - Enregistrements de chèques
- `reclamtion` - Réclamations de chèques

### Autres
- `calendar_event` - Événements du calendrier

Voir les fichiers de migration dans `migrations/` pour la structure complète de la base de données.

## 🔧 Configuration

### Configuration Email

Pour activer les notifications email :

1. Créer un compte sur [Mailtrap](https://mailtrap.io/) (pour le développement)
2. Mettre à jour `MAILER_DSN` dans `.env.local` :
   ```env
   MAILER_DSN=smtp://username:password@sandbox.smtp.mailtrap.io:25
   ```

Pour la production, utilisez un service SMTP réel (Gmail, SendGrid, etc.)

### Configuration SMS (Twilio)

Pour activer les notifications SMS :

1. Créer un compte [Twilio](https://www.twilio.com/)
2. Obtenir votre Account SID et Auth Token
3. Configurer les identifiants dans votre code ou variables d'environnement

### Configuration OAuth Google

Pour activer la connexion via Google :

1. Créer un projet sur [Google Cloud Console](https://console.cloud.google.com/)
2. Activer l'API Google+ 
3. Créer des identifiants OAuth 2.0
4. Mettre à jour les variables dans `.env.local` :
   ```env
   GOOGLE_ID=votre_client_id
   GOOGLE_SECRET=votre_client_secret
   GOOGLE_CALLBACK="https://127.0.0.1:8000/connect/google/check"
   ```

### Configuration reCAPTCHA

Pour activer la protection reCAPTCHA :

1. Obtenir des clés sur [Google reCAPTCHA](https://www.google.com/recaptcha/admin)
2. Mettre à jour les variables dans `.env.local` :
   ```env
   RECAPTCHA3_KEY=votre_site_key
   RECAPTCHA3_SECRET=votre_secret_key
   ```

## 💻 Commandes Utiles

### Doctrine

```bash
# Créer une nouvelle entité
php bin/console make:entity

# Générer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les fixtures
php bin/console doctrine:fixtures:load

# Vider le cache
php bin/console cache:clear
```

### Développement

```bash
# Créer un contrôleur
php bin/console make:controller

# Créer un formulaire
php bin/console make:form

# Créer un CRUD complet
php bin/console make:crud

# Lister toutes les routes
php bin/console debug:router
```

### Tests

```bash
# Exécuter tous les tests
php bin/phpunit

# Exécuter des tests spécifiques
php bin/phpunit tests/Controller/UserControllerTest.php
```

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez suivre ces étapes :

1. Forker le dépôt
2. Créer une branche de fonctionnalité (`git checkout -b feature/NouvelleFonctionnalite`)
3. Commiter vos changements (`git commit -m 'Ajout d'une nouvelle fonctionnalité'`)
4. Pousser vers la branche (`git push origin feature/NouvelleFonctionnalite`)
5. Ouvrir une Pull Request

### Standards de Code

- Suivre les [standards de code Symfony](https://symfony.com/doc/current/contributing/code/standards.html)
- Utiliser PHP CS Fixer pour le formatage
- Écrire des tests pour les nouvelles fonctionnalités
- Documenter les changements importants

## 📝 Licence

Ce projet est sous licence MIT - voir le fichier LICENSE pour plus de détails.

## 👥 Auteurs

- Youssef Azzouz

## 🙏 Remerciements

- Communauté Symfony pour l'excellent framework
- Doctrine pour l'ORM puissant
- Tous les contributeurs des bundles utilisés

## 📞 Support

Pour obtenir de l'aide :
- Ouvrir un ticket dans le dépôt GitHub
- Consulter la [documentation Symfony](https://symfony.com/doc/current/index.html)
- Contacter l'équipe de développement

## 🐛 Problèmes Connus

- Assurez-vous que Docker est en cours d'exécution avant de démarrer l'application
- La base de données PostgreSQL doit être accessible sur le port 5432
- Certaines fonctionnalités (OAuth, SMS) nécessitent une configuration externe
- La génération PDF nécessite des permissions d'écriture dans le répertoire `var/`

## 🔮 Améliorations Futures

- [ ] API REST pour l'intégration mobile
- [ ] Tableau de bord avec graphiques et statistiques avancées
- [ ] Support multilingue (i18n)
- [ ] Thème mode sombre
- [ ] Authentification biométrique
- [ ] Notifications en temps réel (WebSockets)
- [ ] Export de données vers plusieurs formats (Excel, CSV, PDF)
- [ ] Application mobile (React Native / Flutter)
- [ ] Intégration avec des services de paiement externes
- [ ] Module de chat en direct pour le support client

---

**Note** : Il s'agit d'un système de gestion bancaire à des fins éducatives/de démonstration. Pour une utilisation en production, assurez-vous de mettre en place des mesures de sécurité appropriées, le chiffrement des données sensibles, l'audit de sécurité, et la conformité aux réglementations bancaires (PCI DSS, RGPD, etc.).
