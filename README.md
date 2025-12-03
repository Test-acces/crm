# CRM Léger - Gestion de Clients

Un système de gestion de la relation client (CRM) léger développé avec Laravel et Filament, suivant les principes de Clean Architecture.

## 🏗️ Architecture

### Structure des Dossiers

```
app/
├── Models/                 # Modèles Eloquent
│   ├── Client.php
│   ├── Contact.php
│   ├── Task.php
│   └── Activity.php
├── Repositories/           # Couche Repository (Clean Architecture)
│   ├── ClientRepositoryInterface.php
│   └── ClientRepository.php
├── Services/               # Couche Service
│   └── ClientService.php
├── Filament/               # Interface Admin (Filament)
│   ├── Resources/
│   │   ├── ClientResource.php
│   │   ├── ContactResource.php
│   │   ├── TaskResource.php
│   │   └── ActivityResource.php
│   ├── Pages/
│   └── Widgets/
│       ├── CrmStatsWidget.php
│       ├── ClientStatusChartWidget.php
│       └── TaskStatusChartWidget.php
└── Http/Controllers/       # Contrôleurs API (optionnel)
```

### Relations entre Modèles

```
Client (1) ──── (n) Contact
   │
   ├── (n) Task
   │
   └── (n) Activity

Contact (1) ──── (n) Task
   │
   └── (n) Activity

Task (1) ──── (n) Activity

User (1) ──── (n) Task
   │
   └── (n) Activity
```

## 📊 Schéma de Base de Données

### Tables Principales

#### clients
- `id` (PK)
- `name` (string)
- `email` (string, unique)
- `phone` (string, nullable)
- `address` (text, nullable)
- `status` (enum: active/inactive, default: active)
- `timestamps`

#### contacts
- `id` (PK)
- `client_id` (FK → clients.id, cascade delete)
- `name` (string)
- `email` (string, unique)
- `phone` (string, nullable)
- `position` (string, nullable)
- `notes` (text, nullable)
- `timestamps`

#### tasks
- `id` (PK)
- `client_id` (FK → clients.id, cascade delete)
- `contact_id` (FK → contacts.id, set null)
- `user_id` (FK → users.id, set null)
- `title` (string)
- `description` (text, nullable)
- `status` (enum: pending/in_progress/completed)
- `priority` (enum: low/medium/high)
- `due_date` (date, nullable)
- `timestamps`

#### activities
- `id` (PK)
- `client_id` (FK → clients.id, cascade delete)
- `contact_id` (FK → contacts.id, set null)
- `task_id` (FK → tasks.id, set null)
- `user_id` (FK → users.id, set null)
- `type` (string: call/email/meeting/note/task_created/task_updated)
- `description` (text, nullable)
- `date` (datetime)
- `timestamps`

## 🚀 Installation & Configuration

### Prérequis
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL

### Installation
```bash
# Cloner le projet
git clone <repository-url>
cd crm_leger

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Assets
npm run build
```

### Démarrage
```bash
# Serveur de développement
php artisan serve

# Accès admin
# URL: http://localhost:8000/admin
# Login: Utiliser les credentials de seed ou créer un utilisateur
```

## 🧪 Tests

### Exécution des Tests
```bash
# Tests unitaires et fonctionnels
php artisan test

# Tests spécifiques
php artisan test --filter=ClientCrudTest
php artisan test --filter=RelationsTest
```

### Tests Implémentés
- **ClientCrudTest**: CRUD complet pour les clients
- **RelationsTest**: Validation des relations Eloquent et contraintes DB

## 🎨 Interface Utilisateur

### Sidebar
- 🏠 Dashboard
- 👥 Clients
- 📞 Contacts
- 📋 Tâches
- 📜 Historique

### Dashboard
- **Statistiques**: 7 cards avec métriques clés
- **Graphiques**: Bar chart (tâches par statut), Pie chart (clients par statut)

### Tables CRUD
- **Tri et recherche** sur colonnes principales
- **Filtres** avancés (statut, priorité)
- **Actions** : Créer, modifier, supprimer
- **Pagination** automatique

### Modals
- **Formulaires** validés avec feedback visuel
- **Relations** : Selects dynamiques pour clients/contacts

## 🔧 Fonctionnalités

### Gestion Clients
- ✅ CRUD complet
- ✅ Statut actif/inactif
- ✅ Relations avec contacts et tâches

### Gestion Contacts
- ✅ CRUD avec notes
- ✅ Liaison client obligatoire
- ✅ Position et informations détaillées

### Gestion Tâches
- ✅ CRUD avec priorité et statut
- ✅ Assignation utilisateur
- ✅ Date d'échéance
- ✅ Liaison client/contact

### Historique d'Activités
- ✅ Log automatique des actions
- ✅ Types d'activités variés
- ✅ Filtrage et recherche
- ✅ Relations complètes

## 📈 Métriques Dashboard

- Total clients actifs/inactifs
- Nombre total de contacts
- Tâches en cours/terminées
- Activités récentes (dernière semaine)
- Graphiques visuels pour insights rapides

## 🛡️ Sécurité & Performance

- **Authentification** Filament intégrée
- **Validation** côté serveur
- **Contraintes DB** pour intégrité
- **Clean Architecture** pour maintenabilité
- **Tests automatisés** pour fiabilité

## 📝 Développement

### Bonnes Pratiques Implémentées

#### 🏛️ Architecture Clean
- **Séparation des responsabilités** : Models (données), Services (logique métier), Repositories (accès données), Resources (interface)
- **Dépendances injectées** : Utilisation de l'injection de dépendances pour les Services et Repositories
- **Single Responsibility Principle** : Chaque classe a une responsabilité unique

#### 🔧 Patterns Utilisés
- **Repository Pattern** : Abstraction de l'accès aux données
- **Service Layer** : Logique métier centralisée
- **Trait Pattern** : Réutilisation de code (HasStatus, CanBeDeleted, HasTasks)
- **Base Classes** : BaseForm, BasePolicy pour éviter la duplication

#### 📊 Optimisations Performance
- **Eager Loading** : Relations chargées à l'avance dans les Resources
- **Query Optimization** : Utilisation des scopes Eloquent
- **Enum Casting** : Types énumérés pour validation et performance

#### 🛡️ Sécurité
- **Policies** : Autorisation granulaire avec héritage de BasePolicy
- **Validation** : Règles métier dans les Services
- **Soft Deletes** : Suppression sécurisée avec vérification des relations

### Ajouter une Nouvelle Fonctionnalité
1. Créer le modèle avec traits appropriés (HasStatus, CanBeDeleted, etc.)
2. Définir les relations Eloquent optimisées
3. Créer Repository Interface et implémentation
4. Implémenter Service avec logique métier
5. Créer Resource Filament avec BaseForm
6. Ajouter Policy héritant de BasePolicy
7. Écrire tests unitaires et fonctionnels
8. Mettre à jour documentation

### Conventions de Code
- PSR-4 pour l'autoloading
- PSR-12 pour le style de code
- Eloquent pour l'ORM avec relations optimisées
- Filament pour l'interface admin
- Tests avec PHPUnit et couverture complète
- Documentation PHPDoc pour toutes les méthodes publiques
- Utilisation d'enums pour les valeurs fixes (statuts, priorités)

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature
3. Commiter les changements
4. Push et créer une PR

## 📄 Licence

MIT License - voir LICENSE pour plus de détails.
