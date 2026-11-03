<?php
declare(strict_types=1);

class Category
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        return $this->db->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return (bool)$stmt->fetchColumn();
    }

    public function nameExists(string $name, ?int $excludeId = null): bool
    {
        $sql = "SELECT 1 FROM categories WHERE name = :name";
        $params = ['name' => $name];
        if ($excludeId !== null) {
            $sql .= " AND id <> :id";
            $params['id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function create(string $name): bool
    {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare("UPDATE categories SET name = :name WHERE id = :id");
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    public function productCount(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
