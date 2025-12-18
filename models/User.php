<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registro (Igual que antes)
    public function register($username, $email, $password) {
        $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password) RETURNING id";
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        
        if($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['id']; // Devolvemos el ID creado
        }
        return false;
    }

    // NUEVO: Login
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findByEmail($email) {
        $query = "SELECT id, username FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>