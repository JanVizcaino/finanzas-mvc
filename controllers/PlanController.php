<?php
require_once '../config/Database.php';
require_once '../models/Plan.php';
require_once '../models/Expense.php';

class PlanController {
    
    public function dashboard() {
        if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login");
        $db = (new Database())->getConnection();
        $planModel = new Plan($db);
        $plans = $planModel->getPlansByUser($_SESSION['user_id']);
        require '../views/layout/header.php';
        require '../views/plans/dashboard.php';
        require '../views/layout/footer.php';
    }

    public function view() {
        if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login");
        
        $planId = $_GET['id'];
        $db = (new Database())->getConnection();
        
        $planModel = new Plan($db);
        $expenseModel = new Expense($db);

        // Verificar acceso básico
        $plan = $planModel->getPlanDetails($planId, $_SESSION['user_id']);
        if (!$plan) die("Acceso denegado");

        // NUEVO: Obtener datos extra para permisos
        $currentUserRole = $planModel->getUserRole($planId, $_SESSION['user_id']); // 'admin' o 'member'
        $members = $planModel->getMembers($planId); // Lista de gente para borrar
        $expenses = $expenseModel->getByPlan($planId);

        require '../views/layout/header.php';
        require '../views/plans/show.php';
        require '../views/layout/footer.php';
    }

    public function store() {
        $db = (new Database())->getConnection();
        $planModel = new Plan($db);
        $planModel->create($_POST['name'], $_SESSION['user_id']);
        header("Location: index.php?action=dashboard");
    }

    public function storeExpense() {
        // ... (Tu código existente para guardar gasto) ...
        // Este no cambia, todos pueden añadir
        $db = (new Database())->getConnection();
        $expenseModel = new Expense($db);
        $expenseModel->create($_POST['plan_id'], $_SESSION['user_id'], $_POST['title'], $_POST['amount'], $_POST['category']);
        header("Location: index.php?action=view_plan&id=" . $_POST['plan_id']);
    }
}
?>