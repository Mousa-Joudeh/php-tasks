<?php
declare(strict_types=1);

class Product
{
    public function __construct(private PDO $db) {}

    public function allWithCategories(): array
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id
                ORDER BY p.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO products (name, description, price, quantity, category_id, image)
                VALUES (:name, :description, :price, :quantity, :category_id, :image)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = "UPDATE products
                SET name = :name, description = :description, price = :price,
                    quantity = :quantity, category_id = :category_id, image = :image
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }
}
