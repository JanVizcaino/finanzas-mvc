<?php
class Expense
{
    private $conn;
    private $table = "expenses"; 

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getByPlan($planId)
    {
        $query = "SELECT e.*, u.username, e.user_id 
                  FROM " . $this->table . " e 
                  JOIN users u ON e.user_id = u.id
                  WHERE plan_id = :plan_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plan_id", $planId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($planId, $userId, $title, $amount, $category, $receiptPath, $detail)
    {
        $query = "INSERT INTO " . $this->table . " (plan_id, user_id, title, amount, category, receipt_path, detail) 
                  VALUES (:plan_id, :user_id, :title, :amount, :category, :receipt_path, :detail)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":plan_id", $planId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":category", $category);
        $stmt->bindParam(":receipt_path", $receiptPath);
        $stmt->bindParam(":detail", $detail);

        return $stmt->execute();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $amount, $category, $receiptPath, $detail)
    {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      amount = :amount, 
                      category = :category, 
                      receipt_path = :receipt_path, 
                      detail = :detail
                  WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":category", $category);
            $stmt->bindParam(":receipt_path", $receiptPath);
            $stmt->bindParam(":detail", $detail);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
