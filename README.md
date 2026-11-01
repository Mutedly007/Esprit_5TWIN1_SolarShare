# ☀️ SolarShare — Plateforme de Partage & Location d'Énergies Renouvelables

> **Sujet n°2 : SolarShare**  
> Plateforme de location et de partage entre particuliers de petits équipements liés aux énergies renouvelables, tels que des panneaux solaires portables, des batteries ou de petits équipements éoliens. Elle permet de faciliter l’accès à ces ressources, de favoriser leur utilisation collective et d’encourager une consommation énergétique plus responsable.

---

## 🎨 Liens des Templates Exigés (Évaluation)

Conformément aux exigences de la grille de notation concernant la justification et l'attribution des templates :

| Composant | Nom du Template | Lien Source Exigé | Auteur / Distributeur |
| :--- | :--- | :--- | :--- |
| **Front Office** | **Solartec** - Renewable Energy Website Template | [https://htmlcodex.com/renewable-energy-website-template](https://htmlcodex.com/renewable-energy-website-template) | [HTML Codex](https://htmlcodex.com) / [ThemeWagon](https://themewagon.com) |
| **Back Office** | **FreeDash** - Bootstrap 5 Admin Template | [https://adminmart.com/product/freedash-bootstrap-5-admin-template/](https://adminmart.com/product/freedash-bootstrap-5-admin-template/) | [AdminMart](https://adminmart.com) |

---

## 🏗️ Architecture & Héritage Blade

Les templates (Front & Back) ont été entièrement convertis en architecture modulaire **Laravel Blade** en respectant scrupuleusement les règles d'évaluation :

### 1. Front Office (Solartec)
* **Layout Maître :** [`resources/views/layouts/solartec.blade.php`](resources/views/layouts/solartec.blade.php)  
  * Directives utilisées : `@yield('title')`, `@yield('meta_description')`, `@yield('content')`, `@stack('styles')`, `@stack('scripts')`.
* **Partials (`@include`) :**
  * Navbar dynamique (avec gestion `@auth`, détection badge/lien Admin et `@guest`) : [`resources/views/partials/solartec/navbar.blade.php`](resources/views/partials/solartec/navbar.blade.php)
  * Footer (avec attributions légales et liens du template) : [`resources/views/partials/solartec/footer.blade.php`](resources/views/partials/solartec/footer.blade.php)
* **Vue Principale Front :** [`resources/views/pages/front/solartec_home.blade.php`](resources/views/pages/front/solartec_home.blade.php)  
  * Directives : `@extends('layouts.solartec')`, `@section('title')`, `@section('content')`.
* **Sauvegarde de l'Ancien Design :** Archivé de manière autonome dans le dossier [`old_design_backup/`](old_design_backup/).

### 2. Back Office (FreeDash Admin)
* **Layout Maître :** [`resources/views/layouts/admin.blade.php`](resources/views/layouts/admin.blade.php)
  * Directives utilisées : `@yield('title')`, `@yield('content')`, `@stack('styles')`, `@stack('scripts')`.
* **Partials (`@include`) :**
  * Topbar Header : [`resources/views/partials/admin/header.blade.php`](resources/views/partials/admin/header.blade.php)
  * Left Sidebar (avec les 4 modules métiers CRUD SolarShare) : [`resources/views/partials/admin/sidebar.blade.php`](resources/views/partials/admin/sidebar.blade.php)
  * Footer (avec attribution FreeDash exigée) : [`resources/views/partials/admin/footer.blade.php`](resources/views/partials/admin/footer.blade.php)
* **Vue Tableau de Bord Admin :** [`resources/views/pages/admin/dashboard.blade.php`](resources/views/pages/admin/dashboard.blade.php)
  * Directives : `@extends('layouts.admin')`, `@section('title')`, `@section('content')`.
* **Sécurité & Accès :**
  * Rôle `admin` configurable directement dans la base de données (ex: via **phpMyAdmin** en définissant `role = 'admin'` ou `solar_role = 'admin'`).
  * Middleware de protection : [`app/Http/Middleware/EnsureAdmin.php`](app/Http/Middleware/EnsureAdmin.php) bloquant toute tentative non-admin par un code HTTP `403`.
  * Raccourci intelligent dans le menu utilisateur : si l'utilisateur connecté est administrateur, l'option **Dashboard** mène directement au tableau de bord administrateur `/admin`.

---

## 👥 Répartition des Modules CRUD (Équipe de 4 personnes)

Chaque membre prend en charge 2 entités liées par une relation `1-N` ou `N-N` (hors entité `User`) :

1. **Développeur 1 — Catalogue d'Équipements & Catégories :**
   * Entités : `Category` (1) ── (N) `Equipment`
   * Rôle : Gestion des familles de matériel (panneaux pliables, batteries nomades, micro-éoliennes) et gestion des annonces de prêt/location.
2. **Développeur 2 — Réservations & États des Lieux :**
   * Entités : `Rental` (1) ── (N) `InspectionReport`
   * Rôle : Contrats de location, cautions et constats numériques (check-in / check-out avec état de charge et photos).
3. **Développeur 3 — Packs Solaires Clé-en-main :**
   * Entités : `EnergyKit` (N) ── (N) `Equipment`
   * Rôle : Création de kits d'équipements combinés (ex: Pack Vanlife, Pack Chantier Autonome) avec remises tarifaires.
4. **Développeur 4 — Hubs de Collecte Locaux & Créneaux Horaires :**
   * Entités : `CollectionHub` (1) ── (N) `OperatingSlot`
   * Rôle : Points relais physiques de quartier et gestion des plages de permanence de dépôt/retrait.

---

## 🚀 Démarrage Rapide

```bash
# 1. Cloner le projet
git clone https://github.com/Mutedly007/SolarShare.git
cd SolarShare

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Exécuter les migrations
php artisan migrate

# 5. Démarrer le serveur
php artisan serve
test
```
