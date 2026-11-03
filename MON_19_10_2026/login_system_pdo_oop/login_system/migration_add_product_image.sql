-- Run once in phpMyAdmin on the existing login_system database.
-- The supplied project already has the products.category_id foreign key.
ALTER TABLE products
ADD COLUMN image VARCHAR(255) NULL AFTER quantity;
