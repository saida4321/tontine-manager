\# SPÉCIFICATIONS TECHNIQUES - TONTINE MANAGER



\*\*Projet :\*\* Système de Gestion de Tontine Multi-Cabinets  

\*\*Version :\*\* 2.0 (Mise à jour selon cahier des charges)  

\*\*Date :\*\* 13 Novembre 2025  

\*\*Développeur :KOLI Saïdatou-Agbandjala 

\*\*Framework :\*\* Laravel 12  

\*\*Base de données :\*\* MariaDB 11.8  

\*\*Délai :\*\* 4-6 semaines  



---



\## 1. PRÉSENTATION DU PROJET



\### 1.1 Objectif

Développer une application web de gestion de tontine \*\*modulaire, sécurisée et multi-utilisateurs\*\* permettant :

\- La gestion des cotisations, retraits et mouvements financiers des clients

\- La gestion de \*\*plusieurs cabinets (points de service/agences)\*\*

\- Le paiement en ligne via \*\*Mobile Money (PayGate Global)\*\*

\- Le paiement en espèces via les collecteurs

\- La traçabilité complète de toutes les opérations



\### 1.2 Concept Multi-Cabinets

L'application gère plusieurs cabinets (agences). Un client peut être rattaché à un cabinet principal mais peut effectuer des opérations dans n'importe quel cabinet du réseau.



\### 1.3 Acteurs du système

\- \*\*Administrateur principal / Responsable\*\* : Supervise l'ensemble des opérations

\- \*\*Caissier\*\* : Gère les entrées/sorties de fonds au niveau d'un cabinet

\- \*\*Collecteur\*\* : Encaisse les cotisations sur le terrain

\- \*\*Comptable\*\* : Analyse et contrôle les mouvements financiers

\- \*\*Client\*\* : Membre participant à la tontine



---



\## 2. ARCHITECTURE DE LA BASE DE DONNÉES



\### 2.1 Table : roles

\*\*Description :\*\* Définit les différents rôles dans le système (IDs fixes)



| Champ       | Type         | Contraintes           | Description                    |

|-------------|--------------|----------------------|--------------------------------|

| id          | BIGINT       | PK, FIXE             | 1=Admin, 2=Caissier, 3=Collecteur, 4=Comptable, 5=Client |

| nom         | VARCHAR(50)  | NOT NULL, UNIQUE     | Nom du rôle                    |

| description | TEXT         | NULLABLE             | Description du rôle            |

| created\_at  | TIMESTAMP    | NULL                 | Date de création               |

| updated\_at  | TIMESTAMP    | NULL                 | Date de modification           |

| deleted\_at  | TIMESTAMP    | NULLABLE             | Soft Delete (actif/inactif)    |



\*\*Données initiales (IDs FIXES) :\*\*

\- ID 1 : Administrateur

\- ID 2 : Caissier  

\- ID 3 : Collecteur

\- ID 4 : Comptable

\- ID 5 : Client



\*\*Constantes (RoleConstants.php) :\*\*

```php

const ADMINISTRATEUR = 1;

const CAISSIER = 2;

const COLLECTEUR = 3;

const COMPTABLE = 4;

const CLIENT = 5;

```

---



\### 2.2 Table : users

\*\*Description :\*\* Utilisateurs du système (équipe administrative + clients)



| Champ       | Type          | Contraintes              | Description                    |

|-------------|---------------|--------------------------|--------------------------------|

| id          | BIGINT        | PK, AUTO\_INCREMENT       | Identifiant unique             |

| nom         | VARCHAR(100)  | NOT NULL                 | Nom de l'utilisateur           |

| email       | VARCHAR(150)  | NOT NULL, UNIQUE         | Email (login)                  |

| telephone   | VARCHAR(20)   | NULLABLE, UNIQUE         | Numéro de téléphone            |

| password    | VARCHAR(255)  | NOT NULL                 | Mot de passe hashé             |

| role\_id     | BIGINT        | NOT NULL, FK(roles.id)   | Rôle de l'utilisateur          |

| is\_active   | BOOLEAN       | DEFAULT TRUE             | Compte actif ou non            |

| created\_at  | TIMESTAMP     | NULL                     | Date de création               |

| updated\_at  | TIMESTAMP     | NULL                     | Date de modification           |



\*\*Relations :\*\*

\- `users.role\_id` → `roles.id` (belongsTo)

\- `users` → `cabinets` (belongsToMany via `cabinet\_user`)



\*\*Index :\*\*

\- Index sur `email`

\- Index sur `telephone`



---



\### 2.3 Table : cabinets

\*\*Description :\*\* Points de service / Agences



| Champ       | Type         | Contraintes            | Description                    |

|-------------|--------------|------------------------|--------------------------------|

| id          | BIGINT       | PK, AUTO\_INCREMENT     | Identifiant unique             |

| nom         | VARCHAR(100) | NOT NULL, UNIQUE       | Nom du cabinet                 |

| adresse     | TEXT         | NULLABLE               | Adresse du cabinet             |

| telephone   | VARCHAR(20)  | NULLABLE               | Téléphone du cabinet           |

| email       | VARCHAR(150) | NULLABLE               | Email du cabinet               |

| is\_active   | BOOLEAN      | DEFAULT TRUE           | Cabinet actif ou non           |

| created\_at  | TIMESTAMP    | NULL                   | Date de création               |

| updated\_at  | TIMESTAMP    | NULL                   | Date de modification           |



\*\*Relations :\*\*

\- `cabinets` → `users` (belongsToMany via `cabinet\_user`)



---



\### 2.4 Table : cabinet\_user (Pivot)

\*\*Description :\*\* Association entre agents et cabinets (un agent peut travailler dans plusieurs cabinets)



| Champ       | Type    | Contraintes                | Description           |

|-------------|---------|----------------------------|-----------------------|

| id          | BIGINT  | PK, AUTO\_INCREMENT         | Identifiant unique    |

| cabinet\_id  | BIGINT  | NOT NULL, FK(cabinets.id)  | Cabinet associé       |

| user\_id     | BIGINT  | NOT NULL, FK(users.id)     | Agent associé         |

| created\_at  | TIMESTAMP | NULL                     | Date d'association    |

| updated\_at  | TIMESTAMP | NULL                     | Date de modification  |



\*\*Contraintes :\*\*

\- UNIQUE(cabinet\_id, user\_id) : Un agent ne peut être associé qu'une fois à un cabinet



\*\*Relations :\*\*

\- `cabinet\_user.cabinet\_id` → `cabinets.id`

\- `cabinet\_user.user\_id` → `users.id`



---



\### 2.5 Table : clients

\*\*Description :\*\* Membres participants à la tontine



| Champ               | Type         | Contraintes               | Description                       |

|---------------------|--------------|---------------------------|-----------------------------------|

| id                  | BIGINT       | PK, AUTO\_INCREMENT        | Identifiant unique                |

| identifiant\_unique  | VARCHAR(50)  | NOT NULL, UNIQUE          | Code client auto-généré           |

| nom                 | VARCHAR(100) | NOT NULL                  | Nom du client                     |

| prenom              | VARCHAR(100) | NOT NULL                  | Prénom du client                  |

| telephone           | VARCHAR(20)  | NOT NULL, UNIQUE          | Numéro de téléphone               |

| email               | VARCHAR(150) | NULLABLE, UNIQUE          | Email du client                   |

| adresse             | TEXT         | NULLABLE                  | Adresse physique                  |

| cabinet\_id          | BIGINT       | NOT NULL, FK(cabinets.id) | Cabinet d'attachement principal   |

| date\_inscription    | DATE         | NOT NULL                  | Date d'adhésion                   |

| solde               | DECIMAL(10,2)| DEFAULT 0.00              | Solde actuel du client            |

| is\_active           | BOOLEAN      | DEFAULT TRUE              | Client actif ou non               |

| created\_at          | TIMESTAMP    | NULL                      | Date de création                  |

| updated\_at          | TIMESTAMP    | NULL                      | Date de modification              |



\*\*Relations :\*\*

\- `clients.cabinet\_id` → `cabinets.id` (belongsTo)



\*\*Index :\*\*

\- Index sur `identifiant\_unique`

\- Index sur `telephone`

\- Index sur `email`

\- Index sur `cabinet\_id`



\*\*Règles :\*\*

\- `identifiant\_unique` généré automatiquement : Format `CLI-YYYYMMDD-XXXXX`



---



\### 2.6 Table : cotisations

\*\*Description :\*\* Enregistrement des cotisations des clients



| Champ            | Type          | Contraintes                | Description                        |

|------------------|---------------|----------------------------|------------------------------------|

| id               | BIGINT        | PK, AUTO\_INCREMENT         | Identifiant unique                 |

| client\_id        | BIGINT        | NOT NULL, FK(clients.id)   | Client qui cotise                  |

| cabinet\_id       | BIGINT        | NOT NULL, FK(cabinets.id)  | Cabinet où la cotisation est faite |

| montant          | DECIMAL(10,2) | NOT NULL                   | Montant de la cotisation           |

| date\_cotisation  | DATE          | NOT NULL                   | Date de la cotisation              |

| type\_paiement    | ENUM          | 'especes','mobile\_money'   | Mode de paiement                   |

| collecteur\_id    | BIGINT        | NULLABLE, FK(users.id)     | Collecteur (si espèces)            |

| reference\_transaction | VARCHAR(100) | NULLABLE              | Réf. transaction Mobile Money      |

| reference\_recu   | VARCHAR(50)   | NULLABLE, UNIQUE           | Référence du reçu généré           |

| statut           | ENUM          | 'en\_attente','validé','rejeté' | Statut de la cotisation        |

| validé\_par       | BIGINT        | NULLABLE, FK(users.id)     | Caissier ayant validé              |

| date\_validation  | DATETIME      | NULLABLE                   | Date de validation                 |

| created\_at       | TIMESTAMP     | NULL                       | Date de création                   |

| updated\_at       | TIMESTAMP     | NULL                       | Date de modification               |



\*\*Relations :\*\*

\- `cotisations.client\_id` → `clients.id` (belongsTo)

\- `cotisations.cabinet\_id` → `cabinets.id` (belongsTo)

\- `cotisations.collecteur\_id` → `users.id` (belongsTo)

\- `cotisations.validé\_par` → `users.id` (belongsTo)



\*\*Index :\*\*

\- Index sur `client\_id`

\- Index sur `cabinet\_id`

\- Index sur `date\_cotisation`

\- Index sur `statut`



\*\*Règles métier :\*\*

\- Un client ne peut cotiser qu'\*\*une fois par mois\*\* (vérification à implémenter)

\- Le montant minimum est de \*\*5000 FCFA\*\* (configurable)

\- Génération automatique de `reference\_recu` : `COT-YYYYMMDD-XXXXX`



---



\### 2.7 Table : retraits

\*\*Description :\*\* Demandes et exécutions de retraits



| Champ           | Type          | Contraintes                | Description                        |

|-----------------|---------------|----------------------------|------------------------------------|

| id              | BIGINT        | PK, AUTO\_INCREMENT         | Identifiant unique                 |

| client\_id       | BIGINT        | NOT NULL, FK(clients.id)   | Client demandant le retrait        |

| cabinet\_id      | BIGINT        | NOT NULL, FK(cabinets.id)  | Cabinet où le retrait est demandé  |

| montant         | DECIMAL(10,2) | NOT NULL                   | Montant du retrait                 |

| motif           | VARCHAR(255)  | NULLABLE                   | Motif du retrait                   |

| date\_demande    | DATE          | NOT NULL                   | Date de la demande                 |

| date\_retrait    | DATE          | NULLABLE                   | Date effective du retrait          |

| reference       | VARCHAR(50)   | NULLABLE, UNIQUE           | Référence du retrait               |

| statut          | ENUM          | 'en\_attente','approuvé','rejeté','effectué' | Statut du retrait |

| approuvé\_par    | BIGINT        | NULLABLE, FK(users.id)     | Responsable ayant approuvé         |

| effectué\_par    | BIGINT        | NULLABLE, FK(users.id)     | Caissier ayant effectué            |

| date\_approbation| DATETIME      | NULLABLE                   | Date d'approbation                 |

| created\_at      | TIMESTAMP     | NULL                       | Date de création                   |

| updated\_at      | TIMESTAMP     | NULL                       | Date de modification               |



\*\*Relations :\*\*

\- `retraits.client\_id` → `clients.id` (belongsTo)

\- `retraits.cabinet\_id` → `cabinets.id` (belongsTo)

\- `retraits.approuvé\_par` → `users.id` (belongsTo)

\- `retraits.effectué\_par` → `users.id` (belongsTo)



\*\*Index :\*\*

\- Index sur `client\_id`

\- Index sur `cabinet\_id`

\- Index sur `statut`



\*\*Règles métier :\*\*

\- Un retrait doit être \*\*approuvé par un Administrateur/Responsable\*\*

\- Le montant ne peut excéder le \*\*solde disponible\*\* du client

\- Génération automatique de `reference` : `RET-YYYYMMDD-XXXXX`

\- \*\*Motifs possibles\*\* : Récupération, Échange d'appareil, Urgence, etc.



---



\### 2.8 Table : historique\_transactions

\*\*Description :\*\* Journal de toutes les transactions (traçabilité complète)



| Champ           | Type         | Contraintes               | Description                        |

|-----------------|--------------|---------------------------|------------------------------------|

| id              | BIGINT       | PK, AUTO\_INCREMENT        | Identifiant unique                 |

| type            | ENUM         | 'cotisation','retrait'    | Type de transaction                |

| client\_id       | BIGINT       | NOT NULL, FK(clients.id)  | Client concerné                    |

| cabinet\_id      | BIGINT       | NOT NULL, FK(cabinets.id) | Cabinet où l'opération a eu lieu   |

| montant         | DECIMAL(10,2)| NOT NULL                  | Montant de la transaction          |

| date\_operation  | DATETIME     | NOT NULL                  | Date et heure de l'opération       |

| effectué\_par    | BIGINT       | NULLABLE, FK(users.id)    | Utilisateur ayant effectué         |

| reference       | VARCHAR(50)  | NULLABLE                  | Référence de la transaction        |

| description     | TEXT         | NULLABLE                  | Description détaillée              |

| created\_at      | TIMESTAMP    | NULL                      | Date de création                   |



\*\*Relations :\*\*

\- `historique\_transactions.client\_id` → `clients.id` (belongsTo)

\- `historique\_transactions.cabinet\_id` → `cabinets.id` (belongsTo)

\- `historique\_transactions.effectué\_par` → `users.id` (belongsTo)



\*\*Index :\*\*

\- Index sur `client\_id`

\- Index sur `cabinet\_id`

\- Index sur `date\_operation`

\- Index sur `type`



\*\*Règles :\*\*

\- \*\*Immuable\*\* : Aucune suppression ou modification possible

\- Enregistrement automatique à chaque cotisation validée ou retrait effectué

\- Conservation : \*\*10 ans minimum\*\*



---



\## 3. FONCTIONNALITÉS PAR RÔLE



\### 3.1 Administrateur principal / Responsable



\*\*Gestion des utilisateurs :\*\*

\- ✅ Créer, modifier et supprimer des comptes d'agents

\- ✅ Attribuer des rôles et permissions

\- ✅ Suivi des activités des agents

\- ✅ Gérer les paramètres généraux (taux, frais, etc.)



\*\*Gestion des cabinets :\*\*

\- ✅ Ajout, édition et suppression des cabinets

\- ✅ Association des agents à un cabinet

\- ✅ Suivi des transactions par cabinet



\*\*Gestion des clients :\*\*

\- ✅ Enregistrement d'un client (génération auto de l'identifiant unique)

\- ✅ Consultation du solde et de l'historique

\- ✅ Modification des informations client



\*\*Opérations financières :\*\*

\- ✅ Approuver ou rejeter les retraits

\- ✅ Consulter les rapports globaux

\- ✅ Superviser toutes les opérations



---



\### 3.2 Caissier



\*\*Gestion des cotisations :\*\*

\- ✅ Valider ou rejeter une cotisation en espèces

\- ✅ Émettre un reçu numérique (PDF)

\- ✅ Clôturer la caisse en fin de journée



\*\*Gestion des retraits :\*\*

\- ✅ Enregistrer les retraits effectués (après approbation)

\- ✅ Valider les dépôts et retraits



\*\*Rapports :\*\*

\- ✅ Voir l'historique des transactions du cabinet



---



\### 3.3 Collecteur



\*\*Cotisations terrain :\*\*

\- ✅ Enregistrer les cotisations en espèces sur le terrain

\- ✅ Associer les dépôts aux clients

\- ✅ Consulter son historique de collecte



\*\*Consultation :\*\*

\- ✅ Voir les informations des clients

\- ✅ Consulter les montants à collecter



---



\### 3.4 Comptable



\*\*Analyse financière :\*\*

\- ✅ Consulter les rapports de tous les cabinets

\- ✅ Valider les bilans financiers

\- ✅ Exporter les états financiers (PDF/Excel)



\*\*Statistiques :\*\*

\- ✅ Voir les cotisations et retraits par période

\- ✅ Analyser et contrôler les mouvements financiers

\- ✅ Générer des graphiques et tableaux de bord



---



\### 3.5 Client



\*\*Consultation :\*\*

\- ✅ Consulter son solde

\- ✅ Voir l'historique de ses cotisations et retraits

\- ✅ Consulter ses reçus



\*\*Opérations :\*\*

\- ✅ Effectuer des cotisations en ligne (PayGate Global)

\- ✅ Demander un retrait (nécessite validation)



---



\## 4. PRINCIPALES FONCTIONNALITÉS



\### A. Gestion des utilisateurs

\- Création, modification et suppression des comptes

\- Attribution des rôles et permissions

\- Suivi des activités des agents



\### B. Gestion des cabinets

\- Ajout, édition et suppression des cabinets

\- Association des agents à un cabinet

\- Suivi des transactions par cabinet



\### C. Gestion des clients

\- Enregistrement d'un client (nom, contact, cabinet d'attache, etc.)

\- Consultation du solde et de l'historique

\- Génération automatique d'un identifiant unique



\### D. Gestion des cotisations

\- \*\*Cotisation en ligne via PayGate Global\*\* (Mobile Money)

\- \*\*Cotisation en espèces\*\* enregistrée par le collecteur

\- Gestion des dates de cotisation et des montants

\- Génération automatique de reçus numériques



\### E. Gestion des retraits / sorties

\- Enregistrement des retraits effectués par les clients

\- Mention du \*\*motif de sortie de fonds\*\* (récupération, échange d'appareil, etc.)

\- Validation par un caissier et approbation par un responsable



\### F. Historique et rapports

\- Historique des cotisations et retraits par client

\- Historique des opérations par agent et par cabinet

\- Rapports financiers (par période, par cabinet, par collecteur)

\- Export des rapports (PDF / Excel)



\### G. Notifications

\- SMS / Email de confirmation lors des cotisations en ligne

\- Notifications internes pour les agents sur certaines opérations



---



\## 5. RÈGLES MÉTIER



\### 5.1 Cotisations

1\. Un client ne peut cotiser qu'\*\*une fois par mois\*\*

2\. Le montant minimum de cotisation est de \*\*5000 FCFA\*\* (configurable)

3\. Une cotisation en espèces doit être \*\*validée par un Caissier\*\* avant d'être comptabilisée

4\. Une cotisation en ligne (Mobile Money) est automatiquement marquée comme validée après confirmation de paiement

5\. Génération automatique de la référence : `COT-YYYYMMDD-XXXXX`



\### 5.2 Retraits

1\. Un client ne peut retirer que si son \*\*solde disponible\*\* est suffisant

2\. Un retrait nécessite l'\*\*approbation d'un Administrateur/Responsable\*\*

3\. Un retrait approuvé doit être \*\*effectué par un Caissier\*\*

4\. Délai de traitement : \*\*3 jours ouvrables\*\* maximum

5\. Génération automatique de la référence : `RET-YYYYMMDD-XXXXX`

6\. Le \*\*motif\*\* du retrait doit être mentionné (récupération, échange, urgence, etc.)



\### 5.3 Calcul du solde client

```

Solde client = Total cotisations validées - Total retraits effectués

```

Le solde est mis à jour automatiquement après chaque transaction validée.



\### 5.4 Historique et traçabilité

\- Toute transaction (cotisation, retrait) est automatiquement enregistrée dans `historique\_transactions`

\- L'historique est \*\*immuable\*\* (pas de suppression possible)

\- Conservation : \*\*10 ans minimum\*\*

\- Chaque opération sensible (création, suppression, retrait) est journalisée avec l'utilisateur et l'horodatage



\### 5.5 Gestion multi-cabinets

\- Un client est rattaché à un \*\*cabinet principal\*\*

\- Un client peut effectuer des opérations dans \*\*n'importe quel cabinet\*\*

\- Un agent peut être associé à \*\*plusieurs cabinets\*\*

\- Les rapports peuvent être générés \*\*par cabinet\*\* ou \*\*globalement\*\*



---



\## 6. INTÉGRATION PAYGATE GLOBAL (MOBILE MONEY)



\### 6.1 Fonctionnement

\- Le client choisit de payer en ligne

\- Il saisit son numéro Mobile Money

\- Le système génère une demande de paiement via l'API PayGate Global

\- Le client valide le paiement sur son téléphone

\- Le système reçoit une confirmation (callback)

\- La cotisation est automatiquement enregistrée et validée



\### 6.2 Données à stocker

\- Référence de transaction PayGate

\- Statut du paiement (en\_attente, succès, échec)

\- Date et heure de la transaction



\### 6.3 Sécurité

\- Clés API stockées dans `.env` (jamais en base de données)

\- Validation des signatures des callbacks

\- Logs de toutes les tentatives de paiement



\### 6.4 Mode Sandbox

\- Tests en mode sandbox avant la mise en production

\- Basculement facile via configuration `.env`



---



\## 7. SÉCURITÉ



\### 7.1 Authentification

\- Connexion via \*\*email + mot de passe\*\*

\- Hachage des mots de passe avec \*\*bcrypt\*\*

\- Session expiration : \*\*2 heures d'inactivité\*\*

\- Possibilité de "Se souvenir de moi" (remember token)



\### 7.2 Autorisations

\- \*\*Middleware de vérification des rôles\*\* sur toutes les routes sensibles

\- Chaque rôle a des permissions spécifiques (via policies ou packages comme Spatie Permission)

\- \*\*Journalisation des actions importantes\*\* (qui a fait quoi, quand)



\### 7.3 Validation des données

\- \*\*Validation côté serveur obligatoire\*\* sur toutes les entrées (FormRequest Laravel)

\- Protection \*\*CSRF\*\* sur tous les formulaires

\- \*\*Sanitisation des données\*\* pour éviter les injections XSS

\- Validation stricte des montants (positifs, format décimal correct)



\### 7.4 Protection des données sensibles

\- Chiffrement des mots de passe (bcrypt)

\- \*\*Protection CSRF\*\* activée

\- \*\*Protection XSS\*\* (échappement automatique dans Blade)

\- Utilisation de requêtes préparées (Eloquent ORM)



---



\## 8. INTERFACES UTILISATEUR (Pages principales)



\### 8.1 Pages publiques

\- \*\*Page de connexion\*\* (login)

\- \*\*Mot de passe oublié\*\* (reset password)



\### 8.2 Dashboard (après connexion)

\- \*\*Statistiques générales\*\* (nombre de clients, total cotisations, total retraits)

\- \*\*Graphiques\*\* (cotisations/mois, retraits/mois, évolution du solde global)

\- \*\*Dernières transactions\*\* (liste des 10 dernières opérations)

\- \*\*Alertes\*\* (retraits en attente d'approbation, cotisations à valider)



\### 8.3 Gestion des cabinets (Admin uniquement)

\- Liste des cabinets

\- Ajouter un cabinet

\- Modifier un cabinet

\- Associer des agents à un cabinet



\### 8.4 Gestion des utilisateurs (Admin uniquement)

\- Liste des utilisateurs (agents)

\- Ajouter un utilisateur

\- Modifier un utilisateur

\- Attribuer un rôle



\### 8.5 Gestion des clients

\- \*\*Liste des clients\*\* (avec filtres : par cabinet, par statut)

\- \*\*Ajouter un client\*\* (formulaire avec génération auto de l'identifiant)

\- \*\*Modifier un client\*\*

\- \*\*Voir le détail d'un client\*\* (avec solde, historique complet des transactions)



\### 8.6 Gestion des cotisations

\- \*\*Liste des cotisations\*\* (avec filtres : par statut, par cabinet, par collecteur)

\- \*\*Enregistrer une cotisation\*\* (en espèces ou en ligne)

\- \*\*Valider/rejeter une cotisation\*\* (pour les caissiers)

\- \*\*Générer un reçu\*\* (PDF téléchargeable)



\### 8.7 Gestion des retraits

\- \*\*Liste des retraits\*\* (avec filtres : par statut, par cabinet)

\- \*\*Demander un retrait\*\* (formulaire avec motif)

\- \*\*Approuver/rejeter un retrait\*\* (pour les responsables)

\- \*\*Effectuer un retrait\*\* (pour les caissiers)



\### 8.8 Historique et traçabilité

\- \*\*Historique des transactions\*\* (toutes les opérations avec filtres avancés)

\- \*\*Logs des actions sensibles\*\* (qui a fait quoi)



\### 8.9 Rapports

\- \*\*Rapport mensuel\*\* (cotisations et retraits par mois)

\- \*\*Rapport par cabinet\*\*

\- \*\*Rapport par collecteur\*\*

\- \*\*Rapport par client\*\* (détail d'un client spécifique)

\- \*\*Export Excel/CSV\*\* (pour analyse externe)

\- \*\*Export PDF\*\* (pour impression)



---



\## 9. TECHNOLOGIES ET OUTILS



| Composant          | Technologie              | Justification                          |

|--------------------|--------------------------|----------------------------------------|

| Backend            | Laravel 12               | Framework PHP moderne et robuste       |

| Frontend           | Blade + Tailwind CSS     | Simple, rapide, responsive             |

| Base de données    | MariaDB 11.8             | Compatible MySQL, performant           |

| Authentication     | Laravel Breeze           | Simple et extensible avec rôles        |

| Gestion des rôles  | Spatie Permission        | Package Laravel robuste pour les permissions |

| PDF Generation     | barryvdh/laravel-dompdf  | Génération de reçus PDF                |

| Excel Export       | Maatwebsite/Laravel-Excel| Export de rapports en Excel/CSV        |

| Mobile Money API   | PayGate Global API       | Paiements en ligne                     |

| Tests              | Pest                     | Framework de tests moderne             |

| Version Control    | Git                      | Gestion des versions du code           |

| Déploiement        | PHP 8+, Apache/Nginx     | Hébergement standard (LWS, Hostinger)  |



---



\## 10. ARCHITECTURE DU PROJET



\### 10.1 Structure MVC Propre



app/

├── Models/

│   ├── Role.php

│   ├── User.php

│   ├── Cabinet.php

│   ├── Client.php

│   ├── Cotisation.php

│   ├── Retrait.php

│   └── HistoriqueTransaction.php

│

├── Http/

│   ├── Controllers/

│   │   ├── Auth/                  # Authentification (Breeze)

│   │   ├── DashboardController.php

│   │   ├── CabinetController.php

│   │   ├── UserController.php

│   │   ├── ClientController.php

│   │   ├── CotisationController.php

│   │   ├── RetraitController.php

│   │   ├── RapportController.php

│   │   └── PaymentController.php  # PayGate Global

│   │

│   ├── Requests/                  # Validation des formulaires

│   │   ├── StoreCotisationRequest.php

│   │   ├── StoreRetraitRequest.php

│   │   └── ...

│   │

│   ├── Middleware/

│   │   ├── CheckRole.php          # Vérification des rôles

│   │   └── LogActivity.php        # Journalisation des activités

│   │

│   └── Resources/                 # API Resources (si nécessaire)

│

├── Services/                      # Logique métier réutilisable

│   ├── CotisationService.php      # Calculs, règles métier cotisations

│   ├── RetraitService.php         # Calculs, règles métier retraits

│   ├── SoldeService.php           # Calcul et mise à jour des soldes

│   ├── PayGateService.php         # Intégration PayGate Global

│   └── PdfService.php             # Génération de PDF

│

├── Policies/                      # Autorisations

│   ├── CotisationPolicy.php

│   ├── RetraitPolicy.php

│   └── ...

│

└── Notifications/                 # Notifications SMS/Email

├── CotisationValideeNotification.php

└── RetraitApprouveNotification.php

database/

├── migrations/                    # Toutes les migrations

├── seeders/                       # Données de test

└── factories/                     # Génération de fausses données

resources/

├── views/

│   ├── layouts/

│   │   ├── app.blade.php          # Layout principal

│   │   └── guest.blade.php        # Layout pour invités

│   ├── dashboard.blade.php

│   ├── cabinets/

│   ├── clients/

│   ├── cotisations/

│   ├── retraits/

│   └── rapports/

│

└── js/                            # JavaScript (si nécessaire)

routes/

├── web.php                        # Routes web

├── auth.php                       # Routes d'authentification (Breeze)

└── api.php                        # Routes API (PayGate callbacks)

tests/

├── Feature/                       # Tests

└── fonctionnels

└── Unit/                          # Tests unitaires                         # Tests unitaires

config/

├── paygate.php                    # Configuration PayGate Global

└── tontine.php                    # Paramètres métier (montant min, etc.)

public/

├── css/                           # Tailwind CSS compilé

├── js/                            # JavaScript compilé

└── storage/                       # Fichiers publics (reçus PDF)



\### 10.2 Services et Repositories (Optionnel mais recommandé)



\*\*Avantages :\*\*

\- Code réutilisable

\- Testabilité accrue

\- Séparation des responsabilités



\*\*Exemple : CotisationService.php\*\*

```php

class CotisationService

{

&nbsp;   public function verifierCotisationMensuelle($clientId, $mois): bool

&nbsp;   {

&nbsp;       // Vérifier si le client a déjà cotisé ce mois

&nbsp;   }

&nbsp;   

&nbsp;   public function calculerSoldeClient($clientId): float

&nbsp;   {

&nbsp;       // Calculer le solde actuel

&nbsp;   }

&nbsp;   

&nbsp;   public function enregistrerCotisation(array $data): Cotisation

&nbsp;   {

&nbsp;       // Logique d'enregistrement

&nbsp;   }

}

```



---



\## 11. PLANNING DE DÉVELOPPEMENT (4-6 semaines)



\### Semaine 1 : Étude \& Conception ✅ (EN COURS)

| Jour | Tâche | Livrable |

|------|-------|----------|

| 1-2  | Analyse du cahier des charges | Document de spécifications ✅ |

| 3-4  | Conception de la base de données | Schémas \& maquettes |

| 5-7  | Création des migrations | Fichiers de migration |



\### Semaine 2 : Développement Backend (API et logique métier)

| Jour | Tâche | Livrable |

|------|-------|----------|

| 1    | Installation Breeze + configuration rôles | Authentification fonctionnelle |

| 2    | Création des modèles et relations | Models avec relations Eloquent |

| 3    | Seeders (données de test) | Base de données peuplée |

| 4    | CRUD Cabinets + association agents | Gestion des cabinets |

| 5    | CRUD Utilisateurs (agents) | Gestion des utilisateurs |

| 6-7  | CRUD Clients + génération identifiant | Gestion des clients |



\### Semaine 3 : Développement Backend (Suite)

| Jour | Tâche | Livrable |

|------|-------|----------|

| 1-2  | Gestion des cotisations (enregistrement, validation) | Module cotisations |

| 3    | Intégration PayGate Global (sandbox) | Paiement en ligne fonctionnel |

| 4-5  | Gestion des retraits (demande, approbation, exécution) | Module retraits |

| 6    | Service de calcul de solde | Calcul automatique des soldes |

| 7    | Historique et traçabilité | Logs automatiques |



\### Semaine 4 : Développement Frontend (Interface utilisateur)

| Jour | Tâche | Livrable |

|------|-------|----------|

| 1    | Dashboard avec statistiques et graphiques | Page d'accueil |

| 2    | Interfaces Cabinets et Utilisateurs | Pages de gestion |

| 3    | Interfaces Clients | Liste, détails, formulaires |

| 4    | Interfaces Cotisations | Enregistrement, validation |

| 5    | Interfaces Retraits | Demande, approbation |

| 6    | Historique et logs | Page de traçabilité |

| 7    | Responsive design (mobile, tablette) | Design adaptatif |



\### Semaine 5 : Fonctionnalités avancées

| Jour | Tâche | Livrable |

|------|-------|----------|

| 1-2  | Génération de reçus PDF | Reçus téléchargeables |

| 3-4  | Rapports financiers (Excel/PDF) | Module de rapports |

| 5    | Notifications SMS/Email | Système de notifications |

| 6    | Permissions et sécurité avancées | Policies, middleware |

| 7    | Optimisations et refactoring | Code propre et optimisé |



\### Semaine 6 : Tests \& Validation

| Jour | Tâche | Livrable |

|------|-------|----------|

| 1-2  | Tests unitaires et fonctionnels | Suite de tests Pest |

| 3-4  | Tests d'intégration PayGate (sandbox) | Paiements validés |

| 5    | Corrections de bugs | Application stable |

| 6    | Documentation technique | Guide développeur |

| 7    | Documentation utilisateur | Manuel utilisateur |



\### Post-développement (2 jours)

\- Préparation du déploiement

\- Formation des utilisateurs

\- Mise en production



---



\## 12. CRITÈRES DE VALIDATION



\### 12.1 Fonctionnalités essentielles

\- ✅ Toutes les fonctionnalités du cahier des charges sont opérationnelles

\- ✅ L'authentification fonctionne avec tous les rôles

\- ✅ Les permissions sont correctement appliquées



\### 12.2 Qualité de l'interface

\- ✅ L'interface est fluide et intuitive

\- ✅ Design responsive (fonctionne sur mobile, tablette, desktop)

\- ✅ Messages d'erreur et de succès clairs



\### 12.3 Transactions et données

\- ✅ Les transactions sont correctement enregistrées

\- ✅ Les calculs de solde sont exacts

\- ✅ L'historique est complet et immuable



\### 12.4 Rapports financiers

\- ✅ Les rapports financiers sont exacts

\- ✅ Export PDF et Excel fonctionnels

\- ✅ Graphiques et statistiques cohérents



\### 12.5 Intégration PayGate

\- ✅ L'intégration PayGate fonctionne (sandbox test OK)

\- ✅ Les callbacks sont correctement traités

\- ✅ Les transactions sont sécurisées



\### 12.6 Sécurité

\- ✅ Authentification obligatoire pour tout accès interne

\- ✅ Chiffrement des mots de passe (bcrypt)

\- ✅ Validation stricte des données

\- ✅ Journalisation des opérations sensibles

\- ✅ Protection CSRF et XSS



\### 12.7 Performance

\- ✅ Temps de chargement des pages < 2 secondes

\- ✅ Pagination sur les listes longues

\- ✅ Requêtes optimisées (pas de N+1)



---



\## 13. LIVRABLES ATTENDUS



\### 13.1 Code source

\- ✅ Code source complet sur \*\*GitHub / GitLab\*\*

\- ✅ Commits réguliers avec messages clairs

\- ✅ Branches pour chaque fonctionnalité majeure



\### 13.2 Base de données

\- ✅ Fichiers de migration complets

\- ✅ Seeders avec données de test

\- ✅ Script d'export de la structure



\### 13.3 Documentation

\- ✅ \*\*README.md\*\* : Installation, configuration, utilisation

\- ✅ \*\*Documentation d'installation\*\* : Guide pas à pas

\- ✅ \*\*Manuel d'utilisation\*\* : Guide pour chaque rôle

\- ✅ \*\*Documentation API\*\* : Endpoints PayGate (si nécessaire)



\### 13.4 Fichiers de configuration

\- ✅ Fichier `.env.example` avec toutes les variables

\- ✅ Configuration PayGate (sandbox et production)



---



\## 14. AMÉLIORATIONS FUTURES (Version 2.0)



Ces fonctionnalités ne sont \*\*pas prioritaires\*\* pour la version 1.0 mais peuvent être ajoutées plus tard :



\### 14.1 Notifications avancées

\- ✅ Notifications push (navigateur)

\- ✅ Rappels automatiques de cotisation

\- ✅ Alertes en temps réel (WebSockets)



\### 14.2 Application mobile

\- ✅ Application mobile native (Flutter/React Native)

\- ✅ Synchronisation avec l'application web



\### 14.3 Tableau de bord avancé

\- ✅ Graphiques interactifs (Chart.js, ApexCharts)

\- ✅ Prédictions et analyses (Machine Learning)

\- ✅ Exports automatiques programmés



\### 14.4 Gestion multi-tontines

\- ✅ Un client peut participer à plusieurs tontines

\- ✅ Gestion de différents types de tontines (mensuelle, hebdomadaire, etc.)



\### 14.5 API REST complète

\- ✅ API pour intégrations tierces

\- ✅ Documentation API (Swagger/OpenAPI)

\- ✅ Authentification API (Laravel Sanctum/Passport)



\### 14.6 Autres paiements en ligne

\- ✅ Intégration d'autres passerelles (Orange Money, MTN Money, etc.)

\- ✅ Paiements par carte bancaire



\### 14.7 Système de recommandation

\- ✅ Parrainage de nouveaux clients

\- ✅ Bonus de fidélité



\### 14.8 Backup automatique

\- ✅ Sauvegarde automatique de la base de données

\- ✅ Restauration en un clic



---



\## 15. CONFIGURATIONS ET PARAMÈTRES



\### 15.1 Variables d'environnement (.env)

```env

\# Application

APP\_NAME="Tontine Manager"

APP\_ENV=local

APP\_DEBUG=true

APP\_URL=http://localhost



\# Base de données

DB\_CONNECTION=mysql

DB\_HOST=127.0.0.1

DB\_PORT=3306

DB\_DATABASE=tontine\_manager

DB\_USERNAME=root

DB\_PASSWORD=votre\_mot\_de\_passe



\# PayGate Global

PAYGATE\_API\_URL=https://sandbox.paygate.com/api

PAYGATE\_API\_KEY=votre\_cle\_api

PAYGATE\_API\_SECRET=votre\_secret

PAYGATE\_CALLBACK\_URL=${APP\_URL}/api/paygate/callback

PAYGATE\_MODE=sandbox # ou production



\# Notifications

MAIL\_MAILER=smtp

MAIL\_HOST=smtp.mailtrap.io

MAIL\_PORT=2525

MAIL\_USERNAME=null

MAIL\_PASSWORD=null



SMS\_PROVIDER=twilio

SMS\_API\_KEY=votre\_cle\_sms

SMS\_API\_SECRET=votre\_secret\_sms



\# Paramètres métier

TONTINE\_MONTANT\_MIN=5000

TONTINE\_DELAI\_RETRAIT=3 # en jours

TONTINE\_SESSION\_TIMEOUT=120 # en minutes

```



\### 15.2 Fichier de configuration : config/tontine.php

```php

<?php



return \[

&nbsp;   'cotisation' => \[

&nbsp;       'montant\_minimum' => env('TONTINE\_MONTANT\_MIN', 5000),

&nbsp;       'frequence' => 'mensuelle', // mensuelle, hebdomadaire, quotidienne

&nbsp;       'penalite\_retard' => 500, // pénalité si retard

&nbsp;   ],



&nbsp;   'retrait' => \[

&nbsp;       'delai\_traitement' => env('TONTINE\_DELAI\_RETRAIT', 3), // en jours

&nbsp;       'motifs\_disponibles' => \[

&nbsp;           'recuperation' => 'Récupération de fonds',

&nbsp;           'echange\_appareil' => 'Échange d\\'appareil',

&nbsp;           'urgence' => 'Urgence',

&nbsp;           'autre' => 'Autre motif',

&nbsp;       ],

&nbsp;   ],



&nbsp;   'references' => \[

&nbsp;       'cotisation\_prefix' => 'COT',

&nbsp;       'retrait\_prefix' => 'RET',

&nbsp;       'client\_prefix' => 'CLI',

&nbsp;   ],



&nbsp;   'notifications' => \[

&nbsp;       'sms\_enabled' => true,

&nbsp;       'email\_enabled' => true,

&nbsp;   ],



&nbsp;   'rapports' => \[

&nbsp;       'formats' => \['pdf', 'excel', 'csv'],

&nbsp;   ],

];

```



---



\## 16. SÉCURITÉ AVANCÉE



\### 16.1 Protection contre les attaques courantes



\*\*Injection SQL :\*\*

\- ✅ Utilisation exclusive d'Eloquent ORM (requêtes préparées)

\- ✅ Pas de requêtes SQL brutes (sauf si nécessaire avec paramètres)



\*\*XSS (Cross-Site Scripting) :\*\*

\- ✅ Échappement automatique dans Blade : `{{ $variable }}`

\- ✅ Validation stricte des entrées utilisateur



\*\*CSRF (Cross-Site Request Forgery) :\*\*

\- ✅ Token CSRF sur tous les formulaires : `@csrf`

\- ✅ Vérification automatique par Laravel



\*\*Brute Force :\*\*

\- ✅ Limitation des tentatives de connexion (throttling)

\- ✅ Captcha après 3 tentatives échouées (optionnel)



\*\*Élévation de privilèges :\*\*

\- ✅ Vérification des rôles via middleware et policies

\- ✅ Logs de toutes les actions sensibles



\### 16.2 Journalisation (Logging)



\*\*Actions à logger :\*\*

\- Connexion/déconnexion

\- Création/suppression d'utilisateur

\- Validation de cotisation

\- Approbation de retrait

\- Modification de paramètres critiques



\*\*Format du log :\*\*



\[2025-11-13 14:35:22] User #5 (Caissier) a validé la cotisation COT-20251113-00042 pour le client CLI-20250105-00023



\### 16.3 Sauvegardes



\*\*Stratégie de backup :\*\*

\- Sauvegarde quotidienne automatique de la base de données

\- Conservation : 30 jours

\- Stockage externe (cloud ou serveur distant)



---



\## 17. TESTS



\### 17.1 Tests unitaires (Pest)



\*\*Exemples de tests :\*\*

```php

// tests/Unit/CotisationServiceTest.php

test('un client ne peut cotiser qu\\'une fois par mois', function() {

&nbsp;   $client = Client::factory()->create();

&nbsp;   

&nbsp;   // Première cotisation

&nbsp;   $cotisation1 = Cotisation::create(\[

&nbsp;       'client\_id' => $client->id,

&nbsp;       'montant' => 10000,

&nbsp;       'date\_cotisation' => now(),

&nbsp;   ]);

&nbsp;   

&nbsp;   // Deuxième cotisation le même mois (doit échouer)

&nbsp;   $result = app(CotisationService::class)

&nbsp;       ->verifierCotisationMensuelle($client->id, now()->format('Y-m'));

&nbsp;   

&nbsp;   expect($result)->toBeFalse();

});



test('le solde est calculé correctement', function() {

&nbsp;   $client = Client::factory()->create();

&nbsp;   

&nbsp;   // 3 cotisations

&nbsp;   Cotisation::factory()->count(3)->create(\[

&nbsp;       'client\_id' => $client->id,

&nbsp;       'montant' => 5000,

&nbsp;       'statut' => 'validé',

&nbsp;   ]);

&nbsp;   

&nbsp;   // 1 retrait

&nbsp;   Retrait::factory()->create(\[

&nbsp;       'client\_id' => $client->id,

&nbsp;       'montant' => 3000,

&nbsp;       'statut' => 'effectué',

&nbsp;   ]);

&nbsp;   

&nbsp;   $solde = app(SoldeService::class)->calculerSoldeClient($client->id);

&nbsp;   

&nbsp;   expect($solde)->toBe(12000.0); // 15000 - 3000

});

```



\### 17.2 Tests fonctionnels (Feature)



\*\*Exemples :\*\*

```php

// tests/Feature/CotisationTest.php

test('un collecteur peut enregistrer une cotisation', function() {

&nbsp;   $collecteur = User::factory()->create(\['role\_id' => 3]); // Collecteur

&nbsp;   $client = Client::factory()->create();

&nbsp;   

&nbsp;   $this->actingAs($collecteur)

&nbsp;       ->post('/cotisations', \[

&nbsp;           'client\_id' => $client->id,

&nbsp;           'montant' => 5000,

&nbsp;           'type\_paiement' => 'especes',

&nbsp;       ])

&nbsp;       ->assertStatus(302)

&nbsp;       ->assertSessionHas('success');

&nbsp;       

&nbsp;   $this->assertDatabaseHas('cotisations', \[

&nbsp;       'client\_id' => $client->id,

&nbsp;       'montant' => 5000,

&nbsp;   ]);

});



test('un caissier peut valider une cotisation', function() {

&nbsp;   $caissier = User::factory()->create(\['role\_id' => 2]); // Caissier

&nbsp;   $cotisation = Cotisation::factory()->create(\['statut' => 'en\_attente']);

&nbsp;   

&nbsp;   $this->actingAs($caissier)

&nbsp;       ->patch("/cotisations/{$cotisation->id}/valider")

&nbsp;       ->assertStatus(302);

&nbsp;       

&nbsp;   $this->assertDatabaseHas('cotisations', \[

&nbsp;       'id' => $cotisation->id,

&nbsp;       'statut' => 'validé',

&nbsp;   ]);

});

```



\### 17.3 Couverture des tests



\*\*Objectif :\*\* Minimum 80% de couverture de code



\*\*Commande pour exécuter les tests :\*\*

```bash

php artisan test

```



\*\*Avec couverture :\*\*

```bash

php artisan test --coverage

```



---



\## 18. DÉPLOIEMENT



\### 18.1 Pré-requis serveur

\- PHP 8.2+

\- MariaDB 10.5+ ou MySQL 8.0+

\- Composer

\- Node.js 18+ (pour compiler les assets)

\- Apache ou Nginx

\- Certificat SSL (Let's Encrypt recommandé)



\### 18.2 Checklist de déploiement



\*\*Avant le déploiement :\*\*

\- \[ ] Tous les tests passent

\- \[ ] `.env` configuré pour la production

\- \[ ] `APP\_DEBUG=false`

\- \[ ] Clés API PayGate en mode production

\- \[ ] Base de données créée sur le serveur



\*\*Commandes de déploiement :\*\*

```bash

\# 1. Cloner le projet

git clone https://github.com/votre-repo/tontine-manager.git

cd tontine-manager



\# 2. Installer les dépendances

composer install --optimize-autoloader --no-dev

npm install

npm run build



\# 3. Configuration

cp .env.example .env

php artisan key:generate



\# 4. Base de données

php artisan migrate --force

php artisan db:seed --class=RoleSeeder



\# 5. Optimisations

php artisan config:cache

php artisan route:cache

php artisan view:cache



\# 6. Permissions

chmod -R 775 storage bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

```



\### 18.3 Configuration Apache/Nginx



\*\*Apache (.htaccess déjà inclus dans Laravel)\*\*



\*\*Nginx :\*\*

```nginx

server {

&nbsp;   listen 80;

&nbsp;   server\_name tontine-manager.com;

&nbsp;   root /var/www/tontine-manager/public;



&nbsp;   add\_header X-Frame-Options "SAMEORIGIN";

&nbsp;   add\_header X-Content-Type-Options "nosniff";



&nbsp;   index index.php;



&nbsp;   charset utf-8;



&nbsp;   location / {

&nbsp;       try\_files $uri $uri/ /index.php?$query\_string;

&nbsp;   }



&nbsp;   location = /favicon.ico { access\_log off; log\_not\_found off; }

&nbsp;   location = /robots.txt  { access\_log off; log\_not\_found off; }



&nbsp;   error\_page 404 /index.php;



&nbsp;   location ~ \\.php$ {

&nbsp;       fastcgi\_pass unix:/var/run/php/php8.2-fpm.sock;

&nbsp;       fastcgi\_param SCRIPT\_FILENAME $realpath\_root$fastcgi\_script\_name;

&nbsp;       include fastcgi\_params;

&nbsp;   }



&nbsp;   location ~ /\\.(?!well-known).\* {

&nbsp;       deny all;

&nbsp;   }

}

```



---



\## 19. MAINTENANCE ET SUPPORT



\### 19.1 Mises à jour



\*\*Fréquence recommandée :\*\*

\- Mises à jour de sécurité Laravel : \*\*Immédiat\*\*

\- Mises à jour mineures : \*\*Mensuel\*\*

\- Mises à jour majeures : \*\*Trimestriel\*\*



\*\*Commandes :\*\*

```bash

composer update

php artisan migrate

php artisan config:clear

php artisan cache:clear

```



\### 19.2 Monitoring



\*\*Points à surveiller :\*\*

\- Temps de réponse des pages

\- Erreurs 500 dans les logs

\- Espace disque disponible

\- Charge du serveur

\- Transactions PayGate échouées



\*\*Outils recommandés :\*\*

\- Laravel Telescope (développement)

\- Sentry ou Bugsnag (production)

\- Google Analytics (trafic)



\### 19.3 Support utilisateur



\*\*Canaux de support :\*\*

\- Email : support@tontine-manager.com

\- Téléphone : +228 XX XX XX XX

\- Documentation en ligne



---



\## 20. GLOSSAIRE



| Terme | Définition |

|-------|------------|

| \*\*Cabinet\*\* | Point de service ou agence où les opérations sont effectuées |

| \*\*Collecteur\*\* | Agent qui collecte les cotisations en espèces sur le terrain |

| \*\*Caissier\*\* | Agent qui valide les transactions et gère la caisse du cabinet |

| \*\*Comptable\*\* | Agent qui analyse et contrôle les mouvements financiers |

| \*\*Responsable/Administrateur\*\* | Supervise toutes les opérations et a tous les droits |

| \*\*Client\*\* | Membre participant à la tontine |

| \*\*Cotisation\*\* | Versement effectué par un client |

| \*\*Retrait\*\* | Sortie de fonds demandée par un client |

| \*\*Solde\*\* | Montant disponible pour un client (cotisations - retraits) |

| \*\*Reçu\*\* | Document PDF attestant d'une transaction |

| \*\*Mobile Money\*\* | Paiement via téléphone portable (PayGate Global) |

| \*\*Callback\*\* | Notification de PayGate après un paiement |

| \*\*Sandbox\*\* | Environnement de test pour PayGate (mode simulation) |



---



\## 21. ANNEXES



\### Annexe A : Exemple de reçu de cotisation



═══════════════════════════════════════════

TONTINE MANAGER

Reçu de Cotisation

═══════════════════════════════════════════

Référence  : COT-20251113-00042

Date       : 13/11/2025 14:35

CLIENT

Nom        : AFOUDJI Kokou

Identifiant: CLI-20250105-00023

Téléphone  : +228 90 12 34 56

DÉTAILS

Montant    : 10,000 FCFA

Mode       : Mobile Money (PayGate)

Cabinet    : Agence Lomé Centre

Collecté par : ATSOU Yao (Collecteur)

Validé par   : KOFFI Amavi (Caissier)

═══════════════════════════════════════════

Merci pour votre confiance !

═══════════════════════════════════════════



\### Annexe B : Schéma de base de données (ERD)



\*À générer avec un outil comme dbdiagram.io ou MySQL Workbench\*



\### Annexe C : Flux de paiement PayGate Global



1.Client → Formulaire cotisation (choix Mobile Money)

2\.Application → Requête API PayGate (initier paiement)

3.PayGate → Notification au téléphone du client

4.Client → Validation sur téléphone (PIN)

5.PayGate → Callback vers application (succès/échec)

6.Application → Enregistrement cotisation + génération reçu

7.Application → Notification client (SMS/Email)





\## 22. SOFT DELETE GLOBAL



\### 22.1 Principe

\*\*TOUTES les suppressions\*\* dans l'application sont logiques (soft delete) :

\- Champ `deleted\_at` ajouté à \*\*TOUTES les tables\*\* (sauf historique\_transactions)

\- `deleted\_at = NULL` → \*\*Actif\*\* (visible sur l'interface)

\- `deleted\_at = date` → \*\*Inactif\*\* (caché de l'interface mais existe en base)



\### 22.2 Tables concernées

\- ✅ roles (actif/inactif)

\- ✅ users (actif/inactif)

\- ✅ cabinets (ouvert/fermé)

\- ✅ cabinet\_user (association active/inactive)

\- ✅ clients (actif/inactif)

\- ✅ cotisations (valide/annulée)

\- ✅ retraits (valide/annulé)

\- ❌ historique\_transactions (\*\*IMMUABLE\*\* - jamais de suppression)



\### 22.3 Avantages

\- ✅ Traçabilité complète

\- ✅ Récupération possible (restauration)

\- ✅ Conformité légale

\- ✅ Audit complet

\- ✅ Pas de perte de données



\### 22.4 Utilisation des IDs fixes pour les rôles

Pour faciliter les vérifications dans le code :

```php

// Au lieu de : if ($user->role->nom == 'Administrateur')

// On fait : if ($user->role\_id == 1)



// Ou mieux avec les constantes :

use App\\Constants\\RoleConstants;



if ($user->role\_id == RoleConstants::ADMINISTRATEUR) {

&nbsp;   // Code admin

}



---



\*\*FIN DU DOCUMENT DE SPÉCIFICATIONS TECHNIQUES\*\*



---



\*\*Document préparé par :KOLI Saïdatou-Agbandjala

\*\*Date de dernière modification :\*\* 13 Novembre 2025  

\*\*Version :\*\* 2.0  

\*\*Statut :\*\* ✅ Validé et prêt pour développement

