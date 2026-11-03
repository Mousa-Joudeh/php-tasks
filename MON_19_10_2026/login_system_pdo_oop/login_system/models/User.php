<?php
declare(strict_types=1);

class User
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        return $this->db->query("SELECT id, name, email FROM users ORDER BY id DESC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, password, is_admin FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT 1 FROM users WHERE email = :email";
        $params = ['email' => $email];
        if ($excludeId !== null) {
            $sql .= " AND id <> :id";
            $params['id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function create(string $name, string $email, string $password, bool $isAdmin = false): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, is_admin) VALUES (:name, :email, :password, :is_admin)"
        );
        return $stmt->execute([
            'name' => $name, 'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'is_admin' => $isAdmin ? 1 : 0,
        ]);
    }

    public function update(int $id, string $name, string $email): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        return $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
