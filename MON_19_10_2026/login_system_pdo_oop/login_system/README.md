# Login System — PDO + OOP

## Requirements

- PHP 8.0+ (PHP 8.1+ recommended)
- MySQL/MariaDB
- PHP extensions: PDO, pdo_mysql, fileinfo
- XAMPP or equivalent local PHP server

## Setup

1. Put the `login_system` folder inside `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. If setting up from scratch, import `login_system.sql` in phpMyAdmin.
4. If you already have the original `login_system` database and want to preserve its data, select that database in phpMyAdmin and run `migration_add_product_image.sql` once instead of importing the full SQL dump.
5. Check database credentials in `config/Database.php` (XAMPP defaults are root with an empty password).
6. Open `http://localhost/login_system/login.php`.

## Product image behavior

- Images are saved under `uploads/products/`; only the relative path is stored in the database.
- Supported types: JPEG, PNG, GIF, WEBP. Maximum size: 5 MB.
- During edit, leaving the image field empty keeps the current image.
- Existing products without images display `No image` until an image is uploaded.
- Deleting a product removes its uploaded image file.

## Structure

- `config/Database.php`: PDO connection class.
- `models/`: OOP data access for products, categories, and users.
- `classes/ImageUploader.php`: image validation, upload, and cleanup.
- `includes/bootstrap.php`: initializes the database and models.
- `includes/helpers.php`: output escaping and access guards.
- `csrf.php`: CSRF token verification.
- Page files: request handling and HTML views.

## Security notes

- Queries use PDO prepared statements for variable data.
- Passwords are hashed with `password_hash()` and verified using `password_verify()`.
- Admin-only operations require a logged-in admin and state-changing forms use CSRF tokens.
- This is a learning project, not a production-ready deployment. Configure environment-based secrets, HTTPS, stricter error handling, and a least-privilege database user before production.
