# AMN International

A responsive PHP/MySQL website and administration panel for AMN International.

## Features

### Public website

- Home page with categories, featured/new/popular products, services, articles, and settings
- Product catalog with search, category filtering, sorting, and pagination
- Product details, related products, specifications, and price information
- Services and import-process pages
- Gallery and published news/articles
- Contact and quotation forms
- Newsletter subscription

### Administration panel

- Dashboard and site settings
- Categories, products, services, import steps, gallery, articles, and users CRUD
- Product image uploads, primary images, specifications, and publication flags
- Inquiry inbox with new/read/replied status
- Subscriber management
- User roles and password management
- CSRF protection, validation, image validation, and prepared database queries

## Requirements

- PHP 8.0 or newer (PHP 8.2 recommended)
- MySQL or MariaDB
- Apache with URL rewriting, or the `?p=/path` fallback
- XAMPP on Windows is supported

## Installation on XAMPP

1. Copy the project to `C:\xampp\htdocs\AMN_INTERNATIONAL`.
2. Copy `config\.env.example` to `config\.env` and update the database and application values:

   ```env
   APP_URL=http://localhost/AMN_INTERNATIONAL
   APP_DEBUG=true
   URL_REWRITE=true

   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=amn_international
   DB_USER=root
   DB_PASS=
   DB_CHARSET=utf8mb4
   ```

3. Create the MySQL database:

   ```sql
   CREATE DATABASE IF NOT EXISTS amn_international
   CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. Import the complete database dump:

   ```pwsh
   mysql -u root -p amn_international < database\amn_international.sql
   ```

   Alternatively, import `database\amn_international.sql` from phpMyAdmin.

5. Start Apache and MySQL from the XAMPP Control Panel.
6. Open:
   - Public website: `http://localhost/AMN_INTERNATIONAL/`
   - Administration login: `http://localhost/AMN_INTERNATIONAL/admin/login`

## Default Admin Account

After importing the database dump, use the credentials configured in the database seed data.
Change the password immediately after the first successful login.

## Project Structure

```text
app/
├── Controllers/       Public and admin controllers
├── Core/              App, router, database, session, CSRF, uploads, and validation
├── Helpers/           Shared helper functions and translations
├── Models/            Database models
└── Views/             Public/admin PHP templates and partials
assets/                CSS, JavaScript, images, and Bootstrap files
config/                Environment and application configuration
database/              Complete MySQL database dump
storage/logs/          Application logs
uploads/               Uploaded images grouped by module
tools/                 Development utilities
index.php              Front controller
```

## Routes and AJAX

| Method | Path | Description |
|--------|------|-------------|
| GET | `/` | Home page |
| GET | `/products` | Public product catalog |
| GET | `/ajax/products` | Product search/filter AJAX response |
| GET | `/services` | Public services |
| GET | `/import-process` | Import process |
| GET | `/gallery` | Public gallery |
| GET | `/news` | Published news/articles |
| POST | `/ajax/contact` | Contact/quote submission |
| POST | `/ajax/newsletter` | Newsletter subscription |
| POST | `/admin/{module}` | Create an admin record |
| POST | `/admin/{module}/{id}` | Update an admin record |
| POST | `/admin/{module}/{id}/delete` | Delete an admin record |

The legacy `_method=DELETE` form convention is also routed defensively to the dedicated `/delete` endpoint.

## Admin CRUD Notes

- Product availability values include `in_stock`, `out_of_stock`, `made_to_order`, and `unavailable`.
- Product, category, article, service, gallery, and user create/edit operations validate CSRF tokens and submitted fields.
- Creating or editing a gallery item does not require selecting a new image unless the image is being replaced.
- Services use the `title` database column; legacy `name` references remain supported in views.
- Gallery records use the `path` column for the stored image.
- Article records use `body` and `seo_description`.
- Inquiry detail pages resolve the selected product through the product relation rather than relying on a `product_name` database column.
- `created_at` and `updated_at` are supplied explicitly where required by database constraints.

## Public Product Catalog Fixes

The product catalog now returns the active product rows through the correct product-grid partial:

- Initial server-rendered product lists receive the `$products` variable used by the product card partial.
- AJAX product filtering uses `partials/product-grid`.
- Active products with a valid category or product relation are displayed normally.
- Missing fields are rendered with safe fallbacks instead of PHP array-key warnings.

## Development and Troubleshooting

### Verify PHP files

```pwsh
Get-ChildItem app, index.php -Recurse -Filter *.php | ForEach-Object {
    & C:\xampp\php\php.exe -l $_.FullName
}
```

### Database connection

Review `config\.env` and confirm that the database exists, credentials are correct, and MySQL is running.

### Uploads

Ensure the project `uploads\` directory is writable by Apache. Uploaded files are stored outside PHP source files under module-specific directories such as `uploads/products`, `uploads/services`, and `uploads/gallery`.

### Logs

Runtime errors are written to:

```text
storage/logs/app.log
```

### Array-key warnings

Views should use fallbacks for compatibility with legacy database schemas, for example:

```php
$s['title'] ?? $s['name'] ?? ''
$product['name'] ?? '-'
$product['name'] ?? '-'
```

## Security Notes

- CSRF validation is required for state-changing POST requests.
- Passwords must use `password_hash()` and `password_verify()`.
- Image uploads are checked by MIME type and file-size limits.
- Database writes use PDO prepared statements.
- Sessions and authentication cookies use secure application session settings.
- Set `APP_DEBUG=false` in production.

## Deployment Checklist

1. Set `APP_DEBUG=false`.
2. Set the production `APP_URL` and database credentials in `config\.env`.
3. Import `database\amn_international.sql`.
4. Ensure `uploads\` and `storage\logs\` are writable by the web server.
5. Enable HTTPS and secure cookies/headers as supported by the hosting environment.
6. Change the default administrator password.
7. Verify public pages, admin CRUD operations, uploads, settings, and email/newsletter flows.

## Technology Stack

- PHP and PDO
- MySQL/MariaDB
- Bootstrap 5
- Vanilla JavaScript and Fetch API
- Apache URL rewriting
- Server-rendered PHP views
