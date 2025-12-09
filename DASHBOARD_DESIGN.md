# Dashboard - Design Reproduit

Ce document décrit le nouveau design du dashboard, reproduit exactement selon l'image de référence.

## Structure du Dashboard

Le dashboard est organisé en 3 colonnes (layout 3-grid) avec les widgets suivants :

### 1. DashboardHeaderWidget (Sort: 0)
- **Position**: Pleine largeur en haut
- **Contenu**:
  - Icône maison avec fond bleu
  - Titre: "Welcome to your dashboard"
  - Sous-titre: "View your statistics and activities in real time"
  - Sélecteur de période à droite

### 2. QuickActionsGridWidget (Sort: 1)
- **Position**: Pleine largeur
- **Contenu**: 6 boutons d'actions rapides en grille
  - New Organization (orange)
  - New Container (bleu)
  - New Leasing (vert)
  - New Trading (violet)
  - Quick Pickup (teal)
  - Quick Drop Off (amber)

### 3. GeneralOverviewWidget (Sort: 3)
- **Position**: 2 colonnes (gauche et centre)
- **Contenu**: "Vue d'Ensemble Générale"
  - Total Fleet avec mini graphique
  - Total Revenue avec variation %
  - Revenue to collect
  - Occupancy Rate avec barre de progression

### 4. SidebarActivitiesWidget (Sort: 3)
- **Position**: 1 colonne (droite)
- **Contenu**: "Activité récente"
  - Liste des activités avec avatar
  - Nom de l'utilisateur
  - Description de la tâche (tronquée)
  - Temps relatif

### 5. PerformanceIndicatorsGridWidget (Sort: 4)
- **Position**: 2 colonnes (gauche et centre)
- **Contenu**: "Indicateurs de Performance"
  - Onglets: Containers, Leasing, Trading, Releases, Returns, Factures
  - 3 métriques principales

## Layout Configuration

Le dashboard utilise un layout 3 colonnes avec les column spans appropriés pour chaque widget.
