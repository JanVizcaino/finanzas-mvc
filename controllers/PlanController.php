<?php
require_once '../config/Database.php';
require_once '../models/Plan.php';
require_once '../models/Expense.php';
require_once '../models/User.php';

class PlanController
{

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login");
        $db = (new Database())->getConnection();
        $planModel = new Plan($db);
        $plans = $planModel->getPlansByUser($_SESSION['user_id']);
        require '../views/layout/header.php';
        require '../views/plans/dashboard.php';
        require '../views/layout/footer.php';
    }

    public function view()
    {
        if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login");

        $planId = $_GET['id'];
        $db = (new Database())->getConnection();

        $planModel = new Plan($db);
        $expenseModel = new Expense($db);

        $globalRole = $_SESSION['role'] ?? 'user';
        $planRole = $planModel->getUserRole($planId, $_SESSION['user_id']);
        $plan = $planModel->getPlanById($planId);

        if (!$plan) {
            die("El plan no existe.");
        }

        if (!$planRole && $globalRole !== 'admin') {
            die("Acceso denegado. No eres miembro de este plan.");
        }

        $members = $planModel->getMembers($planId);
        $expenses = $expenseModel->getByPlan($planId);

        $relatedPlans = $planModel->getRelatedPlans($_SESSION['user_id'], $planId, $plan['name']);

        require '../views/layout/header.php';
        require '../views/plans/show.php';
        require '../views/layout/footer.php';
    }

    public function viewSettings()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $planId = $_GET['id'];

        $db = (new Database())->getConnection();
        $planModel = new Plan($db);
        $expenseModel = new Expense($db);

        $plan = $planModel->getPlanDetails($planId, $_SESSION['user_id']);

        if (!$plan) {
            die("Acceso denegado o el plan no existe.");
        }

        $memberData = $planModel->getMemberDetails($planId, $_SESSION['user_id']);

        $currentUserRole = $memberData['role']; 
        $currentUserSubscription = [
            'email' => $memberData['notification_email'] ?? '',
            'terms' => $memberData['terms_accepted'] ?? false
        ];

        $members = $planModel->getMembers($planId);
        $expenses = $expenseModel->getByPlan($planId);
        $totalExpenses = 0;
        foreach ($expenses as $e) {
            $totalExpenses += $e['amount'];
        }

        require '../views/layout/header.php';
        require '../views/plans/plan_settings.php';
        require '../views/layout/footer.php';
    }

    public function updateSubscription()
    {
        if (!isset($_SESSION['user_id']) || !isset($_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $db = (new Database())->getConnection();
        $planModel = new Plan($db);

        $planId = $_POST['plan_id'];
        $userId = $_SESSION['user_id'];

        $email = trim($_POST['notification_email']);
        $terms = isset($_POST['terms_accepted']) ? true : false;

        if ($planModel->updateMemberSubscription($planId, $userId, $email, $terms)) {
            header("Location: index.php?action=plan_settings&id=" . $planId . "&success=1");
        } else {
            echo "Error al guardar preferencias.";
        }
    }

    public function update()
    {
        if (!isset($_SESSION['user_id']) || !isset($_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $db = (new Database())->getConnection();
        $planModel = new Plan($db);

        $role = $planModel->getUserRole($_POST['plan_id'], $_SESSION['user_id']);

        if ($role === 'admin') {
            $planModel->update($_POST['plan_id'], $_POST['name'], $_POST['detail'], $_POST['currency']);
        }

        header("Location: index.php?action=plan_settings&id=" . $_POST['plan_id']);
    }

    public function store()
    {
        $db = (new Database())->getConnection();
        $planModel = new Plan($db);
        $planModel->create($_POST['name'], $_SESSION['user_id'], $_POST['detail']);
        header("Location: index.php?action=dashboard");
    }

    public function storeMember()
    {
        $db = (new Database())->getConnection();
        $user = new User($db);
        $plan = new Plan($db);

        $planId = $_POST['plan_id'];

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';

        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos para añadir gente.");

        $existingUser = $user->findByUsername($username);

        if ($existingUser) {
            $targetUserId = $existingUser['id'];
            try {
                $plan->addMember($planId, $targetUserId, 'member');
            } catch (Exception $e) {
            }
            header("Location: index.php?action=plan_settings&id=" . $planId);
        } else {
            die("Error: El usuario <b>" . htmlspecialchars($username) . "</b> no existe en el sistema.");
        }
    }

    public function removeMember()
    {
        $db = (new Database())->getConnection();
        $plan = new Plan($db);

        $planId = $_GET['plan_id'];
        $targetUserId = $_GET['user_id'];

        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos.");

        if ($targetUserId == $_SESSION['user_id']) die("No puedes borrarte a ti mismo si eres admin.");

        $plan->removeMember($planId, $targetUserId);
        header("Location: index.php?action=plan_settings&id=" . $planId);
    }

    public function delete()
    {
        $db = (new Database())->getConnection();
        $planId = $_GET['id'];
        $planModel = new Plan($db);
        $planModel->delete($planId);
        header("Location: index.php?action=dashboard");
    }
 
}
