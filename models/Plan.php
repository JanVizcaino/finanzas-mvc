<?php
class Plan
{
    private $conn;
    private $table = "financial_plans";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name, $userId, $detail)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table . " (name, created_by, detail) VALUES (:name, :created_by, :detail) RETURNING id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":created_by", $userId);
            $stmt->bindParam(":detail", $detail);
            $stmt->execute();
            $planId = $stmt->fetch(PDO::FETCH_COLUMN);

            $this->addMember($planId, $userId, 'admin');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($planId, $name, $detail, $currency)
    {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, 
                      detail = :detail, 
                      currency = :currency 
                  WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":detail", $detail);
            $stmt->bindParam(":currency", $currency);
            $stmt->bindParam(":id", $planId);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPlansByUser($userId)
    {
        $query = "SELECT p.*, pm.role FROM financial_plans p 
                  JOIN plan_members pm ON p.id = pm.plan_id 
                  WHERE pm.user_id = :user_id ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlanDetails($planId, $userId)
    {
        $query = "SELECT p.* FROM financial_plans p 
                  JOIN plan_members pm ON p.id = pm.plan_id 
                  WHERE p.id = :plan_id AND pm.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserRole($planId, $userId)
    {
        $query = "SELECT role FROM plan_members WHERE plan_id = :plan_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getMembers($planId)
    {
        $query = "SELECT u.id, u.username, pm.notification_email AS connection_email, pm.role 
                  FROM users u 
                  JOIN plan_members pm ON u.id = pm.user_id 
                  WHERE pm.plan_id = :plan_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMember($planId, $userId, $role = 'member')
    {
        $query = "INSERT INTO plan_members (plan_id, user_id, role) VALUES (:plan_id, :user_id, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":role", $role);
        return $stmt->execute();
    }

    public function removeMember($planId, $userId)
    {
        $query = "DELETE FROM plan_members WHERE plan_id = :plan_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        return $stmt->execute();
    }

    public function delete($planId)
    {
        $query = "DELETE FROM financial_plans WHERE id = :plan_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        return $stmt->execute();
    }

    public function getPlanById($planId)
    {
        $query = "SELECT * FROM financial_plans WHERE id = :plan_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMemberDetails($planId, $userId)
    {
       
        $query = "SELECT * FROM plan_members 
                  WHERE plan_id = :plan_id 
                  AND user_id = :user_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMemberSubscription($planId, $userId, $email, $terms)
    {
        $query = "UPDATE plan_members 
                  SET notification_email = :email, terms_accepted = :terms 
                  WHERE plan_id = :plan_id AND user_id = :user_id";
        try {
            $stmt = $this->conn->prepare($query);
            $emailVal = !empty($email) ? $email : null;
            $stmt->bindParam(":email", $emailVal);
            $stmt->bindParam(":terms", $terms, PDO::PARAM_BOOL);
            $stmt->bindParam(":plan_id", $planId);
            $stmt->bindParam(":user_id", $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getSubscribers($planId)
    {
        $query = "SELECT notification_email 
                  FROM plan_members 
                  WHERE plan_id = :plan_id 
                  AND notification_email IS NOT NULL 
                  AND notification_email != '' 
                  AND terms_accepted = TRUE";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
