# AMN International

A complete, production-ready, responsive PHP/MySQL MVC website for **AMN International** — a distributor of electrostatic discharge (ESD) control products and solutions.

## Features

- **Public site** — Home, About Us, Products catalog with search/filter/sort + pagination, Product Details, Services, Import Process, Image Gallery, News/Articles, Contact Us, Request a Quote.
- **Product pricing** — USD + automatically calculated AFN using a DB-managed exchange rate (`exchange_rates.usd_to_afn`).
- **Quote/contact flows** — Client & server validation, AJAX submission, DB storage, inbox with status management (new/read/replied).
- **Newsletter** — AJAX signup with duplicate handling.
- **Admin panel** — Dashboard, settings (general/contact/home/currency/SEO/footer), categories, products (CRUD + image upload + specs + publish/unpublish), services, import steps, gallery, news/articles, homepage sections, banners, exchange rate, inquiries, newsletter subscribers, SEO settings, users, profile password change.
- **Authentication** — Session-based admin login, CSRF protection, brute-force lockout (5 tries / 5 min), password hashing.
- **Responsive UI** — Bootstrap 5, vanilla JS, focus-state polish, SVG icons, lazy image fallbacks.
- **Language-ready** — PHP translation helper + string keys organized for future Persian i18n.

## Requirements

- **PHP** 8.0+ (tested with PHP 8.2)
- **MySQL / MariaDB** 5.7+ (tested with MariaDB)
- **Apache** with `mod_rewrite` (strongly recommended) or fallback `?p=/path` mode
- **XAMPP** (Windows) — Apache + PHP + MySQL

## Admin Credentials

After importing `seed.sql`, the default admin account is:

| Field | Value |
|-------|-------|
| **Email** | `admin@amn-international.com` |
| **Password** | `password` |

⚠️ **Change the password after first login.**

## Quick Start (XAMPP)

1. **Copy the project** into your XAMPP `htdocs` folder:
   ```pwsh
   cp -r AMN_INTERNATIONAL c:\xampp\htdocs\
   ```

2. **Create the database** in phpMyAdmin (or via CLI):
   ```sql
   CREATE DATABASE IF NOT EXISTS amn_international CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
## Development Workflow

1. Copy `config/.env.example` → `config/.env`
2. Run `schema.sql` then `seed.sql` in your database
3. Start Apache + MySQL
4. Visit `/` and `/admin/login`

## Architecture

```
public/            # Front controller + .htaccess routing
config/            # .env, config.php, .htaccess
app/
  bootstrap.php    # Autoloader, error handlers, session
## API Endpoints (AJAX)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/ajax/products` | Product search/filter/sort/paginate |
| POST | `/ajax/contact` | Contact form / quote |
| POST | `/ajax/newsletter` | Newsletter signup |
| POST | `/admin/products` | Create product |
| POST | `/admin/products/{id}` | Update product |
| POST | `/admin/products/{id}/delete` | Delete product |
| POST | `/admin/products/images/{id}/primary` | Set primary image |
| POST | `/admin/categories/{id}/toggle` | Toggle category active |
| ... | (see routes) | Many more |

All AJAX responses return JSON: `{ success: bool, message: string, data?: mixed }`.

## Security Notes

- **CSRF tokens** on all POST forms.
- **Password hashing** with `password_hash()` / `password_verify()`.
- **Upload validation** — MIME type check via `finfo`, `getimagesize()`, file size limits, random filenames.
- **Prepared statements** everywhere.
- `.env` and `config/` are protected by `.htaccess` (deny access).
- `uploads/` only serves via `upload_url()` which prevents path traversal.
- Session is regenerated on login and after privilege changes.

## Deployment

1. Set `APP_DEBUG=false`.
2. Set `APP_URL` to the production domain.
3. Ensure `uploads/` and `storage/logs/` are writable by the web server.
4. Disable `URL_REWRITE` only if your host doesn't support it (falls back to `?p=/path`).
5. Remove `config/.env.example` documentation references.
6. Run `database/schema.sql` + `seed.sql` on the production DB.

  Core/            # App, Router, Controller, Database, View, Session, Auth, Validator, Upload, Csrf, Logger
  Models/          # Product, Category, Service, Article, GalleryImage, Inquiry, Subscriber, User, Setting, etc.
  Views/           # Blade-style PHP templates (layouts, partials, public pages, admin)
  Controllers/     # Public controllers + Admin controllers
assets/            # Bootstrap, CSS, JS, images, SVG icons
uploads/           # Product & site uploads (images)
database/          # schema.sql + seed.sql
storage/logs/      # Application log
```

## Key Technologies

- **PHP 8.2** — typed properties, null-safe operator, `str_contains`, `match` expressions
- **PDO** — prepared statements, emulated prepares disabled
- **Bootstrap 5** — responsive UI
- **vanilla JS** — Fetch API for AJAX
- **Apache mod_rewrite** — clean URLs (with fallback)

   ```

3. **Import the schema and seed data**:
   ```pwsh
   # In phpMyAdmin:
   #   - Import database/schema.sql
   #   - Import database/seed.sql
   #
   # Or via CLI:
   #   mysql -u root -p amn_international < database/schema.sql
   #   mysql -u root -p amn_international < database/seed.sql
   ```

4. **Configure environment**:
   Copy `config/.env.example` to `config/.env` (already done in this repo) and adjust:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
   - `APP_URL` (e.g., `http://localhost/AMN_INTERNATIONAL`)
   - `APP_DEBUG` (set to `false` in production)

5. **Ensure upload directories are writable**:
   ```pwsh
   icacls c:\xampp\htdocs\AMN_INTERNATIONAL\uploads /grant "IIS_IUSRS:(OI)(CI)M" /T
   # or for Apache on Windows:
   icacls c:\xampp\htdocs\AMN_INTERNATIONAL\uploads /grant "Everyone:(OI)(CI)M" /T
   ```

6. **Start Apache + MySQL** in XAMPP Control Panel.

7. **Visit the site**:
   - Public: http://localhost/AMN_INTERNATIONAL/
   - Admin: http://localhost/AMN_INTERNATIONAL/admin/login
