<?php
class User {
    private $db;
    private $table = "users";
    
    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function register($name, $email, $password){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO " . $this->table . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword 
        ]);
    }
    
    public function login($email, $password){
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])){
            return $user;
        }
        return false;
    }

    public function getAllUsers(){
        $sql = "SELECT id, name, email FROM " . $this->table; 
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function deleteUser($id){
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getUserById($id){
        $sql = "SELECT id, name, email FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateUser($id, $name, $email, $password) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE " . $this->table . " SET name = :name, email = :email, password = :password WHERE id = :id";
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':id' => $id
            ];
        } else {
            $sql = "UPDATE " . $this->table . " SET name = :name, email = :email WHERE id = :id";
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':id' => $id
            ];
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
?>