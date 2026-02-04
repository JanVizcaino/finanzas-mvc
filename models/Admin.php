<?php
class Admin
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllUsers()
    {
        $query = "SELECT 
                        u.id, 
                        u.username, 
                        u.role,
                        COALESCE(STRING_AGG(DISTINCT pm.notification_email, ', '), 'Sin configurar') as connection_email
                    FROM users u
                    LEFT JOIN plan_members pm ON u.id = pm.user_id
                    GROUP BY u.id, u.username, u.role
                    ORDER BY u.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPlans()
    {
        $query = "SELECT p.*, u.username as owner_name 
                 FROM financial_plans p
                 JOIN users u ON p.created_by = u.id
                 ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUserAsAdmin($userId)
    {
        $query = "DELETE FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        return $stmt->execute();
    }

    public function deletePlanAsAdmin($planId)
    {
        $query = "DELETE FROM financial_plans WHERE id = :planId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":planId", $planId);
        return $stmt->execute();
    }
}
