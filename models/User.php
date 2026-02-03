<?php
class User
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- FUNCIONES PÚBLICAS (Login / Registro) ---

    // REGISTRO: Solo Username y Password
    public function register($username, $password)
    {
        $query = "INSERT INTO " . $this->table . " (username, password) VALUES (:username, :password) RETURNING id";
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }
        return false;
    }

    // LOGIN: Autenticación por Username
    public function login($username, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Validar si existe usuario (útil para no duplicar nombres)
    public function findByUsername($username)
    {
        $query = "SELECT id, username, role FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- FUNCIONES DE ADMINISTRACIÓN (CRUD) ---
    
    // 1. CREAR (Limpio: sin email)
    public function create($username, $passwordHash, $role)
    {
        $query = "INSERT INTO " . $this->table . " (username, password, role) 
                  VALUES (:username, :password, :role)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $passwordHash);
            $stmt->bindParam(":role", $role);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
    
    // 2. ACTUALIZAR (Limpio: sin email)
    public function update($id, $username, $role, $passwordHash = null)
    {
         if ($passwordHash) {
            $query = "UPDATE " . $this->table . " 
                      SET username = :username, role = :role, password = :password 
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET username = :username, role = :role 
                      WHERE id = :id";
        }

         try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":id", $id);
            
            if ($passwordHash) {
                $stmt->bindParam(":password", $passwordHash);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    // 3. ELIMINAR
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
}
?>