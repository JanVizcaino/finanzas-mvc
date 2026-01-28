<?php
class User
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- FUNCIONES PÚBLICAS (Login / Registro normal) ---

    public function register($username, $email, $password)
    {
        // El registro normal asigna rol 'user' por defecto en la BBDD
        $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password) RETURNING id";
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }
        return false;
    }

    public function login($email, $password)
    {
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

    public function findByEmail($email)
    {
        $query = "SELECT id, username, role FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
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

    // 1. CREAR (Permite definir el rol explícitamente)
    public function create($username, $email, $passwordHash, $role)
    {
        $query = "INSERT INTO " . $this->table . " (username, email, password, role) 
                  VALUES (:username, :email, :password, :role)";

        try {
            $stmt = $this->conn->prepare($query);

            // Limpieza básica
            $username = htmlspecialchars(strip_tags($username));
            $email = htmlspecialchars(strip_tags($email));
            $role = htmlspecialchars(strip_tags($role));

            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $passwordHash); // Ya viene hasheada del controller
            $stmt->bindParam(":role", $role);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    // 2. ACTUALIZAR (Maneja cambio de password opcional)
    public function update($id, $username, $email, $role, $passwordHash = null)
    {
        // Si $passwordHash tiene valor, actualizamos la contraseña. Si es null, no.
        if ($passwordHash) {
            $query = "UPDATE " . $this->table . " 
                      SET username = :username, email = :email, role = :role, password = :password 
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET username = :username, email = :email, role = :role 
                      WHERE id = :id";
        }

        try {
            $stmt = $this->conn->prepare($query);

            // Bindings comunes
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":id", $id);

            // Binding condicional
            if ($passwordHash) {
                $stmt->bindParam(":password", $passwordHash);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // 3. ELIMINAR
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>