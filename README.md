# 🖧 Network & Audiovisual Equipment Management

Web application for managing a network and audiovisual equipment fleet: clients, equipment catalogue, stock, technical interventions and support requests, all in one centralized tool that replaces manual tracking.

> Developed during a one-month web development internship at **ProAVTech** (Casablanca, April 2025).

## ✨ Features

| Module | Description |
|---|---|
| **Authentication** | Login, registration and password reset (Laravel UI). All pages except login are protected |
| **Dashboard** | Key figures: total clients, equipment and interventions |
| **Clients** | Create, edit, view and archive clients (sector, status active / archived) |
| **Equipment catalogue** | Equipment models linked to a type (network, audiovisual…) and a manufacturer reference, with a stock alert threshold |
| **Equipment** | Individual units tracked by serial number, firmware and warranty date. Statuses: *in stock, assigned, in intervention, faulty, scrapped*. Stock location or client / site assignment is required depending on the status |
| **Stocks** | Manage storage locations |
| **Interventions** | Planned interventions linked to a client and site, with priority, status, description, planned date, time spent and assigned technicians |
| **Support requests** | Client support tickets, optionally linked to an equipment unit, with a status workflow |

### Data model

The database is designed around these tables:

```
roles ── users
clients ── contacts_client
        └─ sites_client
types_equipement ─┐
references_constructeur ─┴─ catalogue_equipement ── equipements ── stocks
                                                         │
interventions ── priorites_intervention / statuts_intervention
      ├─ intervention_technicien (users)
      ├─ intervention_equipement
      └─ comptes_rendus ── pieces_jointes_cr
demandes_support ── statuts_support
mouvements_stock, journalisation
```

## 🛠️ Tech stack

- **Back-end:** PHP 8.1+, Laravel 10, Laravel UI (authentication scaffolding), Laravel Sanctum
- **Front-end:** Blade templates, Bootstrap 5 (+ Bootswatch theme), JavaScript, Sass, Axios
- **Build tool:** Vite
- **Database:** MySQL (Eloquent ORM, migrations)
- **Methodology:** V-Model

## 📁 Project structure

```
├── app/
│   ├── Http/Controllers/     # Client, Equipement, Intervention, Stock,
│   │                         # CatalogueEquipement, DemandeSupport, Home, Auth/*
│   └── Models/               # Eloquent models (19)
├── database/migrations/      # Full schema
├── resources/views/          # Blade views (clients, equipements, interventions,
│                             # stocks, catalogue, support, auth, layouts)
├── routes/web.php            # Authenticated resource routes
└── public/
```

## 🚀 Installation

Requirements: PHP 8.1+, Composer, Node.js, MySQL.

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 3. Create the database, then run the migrations
php artisan migrate

# 4. Build assets and start the server
npm run build        # or: npm run dev
php artisan serve
```

Open http://127.0.0.1:8000.

### Initial data

The database seeder is empty, so the reference tables must be filled before using the forms (for example with `php artisan tinker` or a seeder):

- `roles`: Admin, Technicien, Commercial
- `statuts_intervention`: À planifier, Planifiée, En cours, Clôturée, Archivée
- `priorites_intervention`: Haute, Moyenne, Basse
- `statuts_support`: Ouverte, En cours, Résolue, Fermée
- `types_equipement`, `references_constructeur`, `stocks`: your own data

## 🗺️ Roadmap

Planned or partially implemented (schema and models exist, interface still to do):

- [ ] Client contacts and sites management screens
- [ ] Intervention reports with rich text, attachments and client signature
- [ ] Automatic stock movement history when equipment changes location
- [ ] Action log (journalisation)
- [ ] Role-based access (Admin / Technicien / Commercial) on routes
- [ ] Stock alerts when quantity falls below the catalogue threshold
- [ ] Pagination and search on list pages

## 👤 Author

**Afrah Mouhsine**, Full Stack Developer
📧 afrahmohssine@gmail.com · 🐙 [GitHub](https://github.com/Afrah-Mouhsine)

