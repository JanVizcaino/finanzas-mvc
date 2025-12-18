<?php
class Plan {
    private $conn;
    private $table = "financial_plans";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear plan (Asigna al creador como ADMIN)
    public function create($name, $userId) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table . " (name, created_by) VALUES (:name, :created_by) RETURNING id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":created_by", $userId);
            $stmt->execute();
            $planId = $stmt->fetch(PDO::FETCH_COLUMN);

            // El creador es ADMIN
            $this->addMember($planId, $userId, 'admin');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Obtener planes del usuario
    public function getPlansByUser($userId) {
        $query = "SELECT p.*, pm.role FROM financial_plans p 
                  JOIN plan_members pm ON p.id = pm.plan_id 
                  WHERE pm.user_id = :user_id ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlanDetails($planId, $userId) {
        $query = "SELECT p.* FROM financial_plans p 
                  JOIN plan_members pm ON p.id = pm.plan_id 
                  WHERE p.id = :plan_id AND pm.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NUEVO: Obtener el rol de un usuario en un plan
    public function getUserRole($planId, $userId) {
        $query = "SELECT role FROM plan_members WHERE plan_id = :plan_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchColumn(); // Devuelve 'admin' o 'member'
    }

    // NUEVO: Obtener lista de miembros (para poder borrarlos)
    public function getMembers($planId) {
        $query = "SELECT u.id, u.username, u.email, pm.role FROM users u 
                  JOIN plan_members pm ON u.id = pm.user_id 
                  WHERE pm.plan_id = :plan_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizado: acepta rol (por defecto member)
    public function addMember($planId, $userId, $role = 'member') {
        $query = "INSERT INTO plan_members (plan_id, user_id, role) VALUES (:plan_id, :user_id, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":role", $role);
        return $stmt->execute();
    }

    // NUEVO: Eliminar miembro
    public function removeMember($planId, $userId) {
        $query = "DELETE FROM plan_members WHERE plan_id = :plan_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        return $stmt->execute();
    }
}
?>