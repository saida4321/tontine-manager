# Tontine Manager

Application web de gestion de tontine multi-agences, développée avec Laravel et Livewire.

> **Projet en cours de développement.** Le module administrateur est fonctionnel ; les espaces caissier, collecteur, comptable et client sont en construction.

## Contexte

Tontine Manager a été développé pendant mon stage à la DIESL (Lomé), d'octobre 2025 à février 2026. L'application centralise les clients, les cotisations et les retraits de plusieurs agences.

## Fonctionnalités

**Module administrateur (fonctionnel)**
- Tableau de bord
- Gestion des agences (cabinets) et affectation des agents
- Gestion des utilisateurs et des clients
- Enregistrement des cotisations et des retraits
- Rapports mensuels exportables en PDF
- Paramètres de l'application (entreprise, apparence, e-mails, sécurité…)

**Sécurité**
- Authentification (Laravel Breeze)
- Accès contrôlé par rôle via middleware : administrateur, caissier, collecteur, comptable, client
- Désactivation logique des comptes et des enregistrements
- Journal des activités

**Prévu**
- Espaces dédiés aux caissiers, collecteurs, comptables et clients
- Paiement en ligne par Mobile Money

## Technologies

| Côté | Outils |
|---|---|
| Back-end | PHP 8.2+, Laravel 12, Livewire 3 |
| Front-end | Blade, Tailwind CSS, Alpine.js, Vite |
| Base de données | MariaDB |
| PDF | barryvdh/laravel-dompdf |
| Tests | Pest |

## Installation

Prérequis : PHP 8.2+, Composer, Node.js, MariaDB ou MySQL.

```bash
git clone https://github.com/saida4321/tontine-manager.git
cd tontine-manager
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Renseigner les accès à la base de données dans `.env`, puis :

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

L'application est alors accessible sur http://localhost:8000.

## Comptes de démonstration

Créés par `php artisan migrate --seed` (données fictives) :

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Administrateur | admin@tontine.com | password |
| Caissier | caissier@tontine.com | password |
| Collecteur | collecteur@tontine.com | password |
| Comptable | comptable@tontine.com | password |
| Client | client@tontine.com | password |

## Documentation

Les spécifications techniques (base de données, règles de gestion) sont dans [`docs/specifications-techniques.md`](docs/specifications-techniques.md).

## Auteure

**Saïdatou A. KOLI**, développeuse full-stack
GitHub : [saida4321](https://github.com/saida4321)