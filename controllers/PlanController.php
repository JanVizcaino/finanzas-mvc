<?php

require_once '../config/Database.php';
require_once '../models/Plan.php';
require_once '../models/Expense.php';
require_once '../models/User.php';

class PlanController
{
    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        Logger::safeRun(function() {
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);
            
            $plans = $planModel->getPlansByUser($_SESSION['user_id']);
            
            require '../views/layout/header.php';
            require '../views/plans/dashboard.php';
            require '../views/layout/footer.php';

        }, "index.php?action=login", "Plan Dashboard");
    }

    public function view()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $planId = Security::cleanInt($_GET['id']);
        $userId = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $userId) {
            
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);
            $expenseModel = new Expense($db);

            $plan = $planModel->getPlanById($planId);
            if (!$plan) throw new Exception("Plan no encontrado ID: $planId");

            $globalRole = $_SESSION['role'] ?? 'user';
            $planRole = $planModel->getUserRole($planId, $userId);

            if (!$planRole && $globalRole !== 'admin') {
                throw new Exception("Acceso denegado al plan $planId por usuario $userId");
            }

            $members = $planModel->getMembers($planId);
            $expenses = $expenseModel->getByPlan($planId);
            
            $relatedPlans = $planModel->getRelatedPlans($userId, $planId, $plan['name']);

            require '../views/layout/header.php';
            require '../views/plans/show.php';
            require '../views/layout/footer.php';

        }, "index.php?action=dashboard", "View Plan");
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

        $planId = Security::cleanInt($_GET['id']);
        $userId = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $userId) {

            $db = (new Database())->getConnection();
            $planModel = new Plan($db);
            $expenseModel = new Expense($db);

            $plan = $planModel->getPlanDetails($planId, $userId);
            if (!$plan) throw new Exception("Acceso denegado o plan inexistente en Settings");

            $memberData = $planModel->getMemberDetails($planId, $userId);

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

        }, "index.php?action=dashboard", "View Plan Settings");
    }

    public function updateSubscription()
    {
        if (!isset($_SESSION['user_id'], $_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $planId = Security::cleanInt($_POST['plan_id']);
        $userId = $_SESSION['user_id'];
        $email  = Security::clean($_POST['notification_email']);

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
             header("Location: index.php?action=plan_settings&id=$planId&error=invalid_email");
             exit;
        }
        $terms  = isset($_POST['terms_accepted']);

        Logger::safeRun(function() use ($planId, $userId, $email, $terms) {
            
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);

            if ($planModel->updateMemberSubscription($planId, $userId, $email, $terms)) {
                header("Location: index.php?action=plan_settings&id=" . $planId . "&success=1");
                exit;
            } else {
                throw new Exception("Error al actualizar suscripción.");
            }

        }, "index.php?action=plan_settings&id=" . $planId, "Update Subscription");
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'], $_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $planId   = Security::cleanInt($_POST['plan_id']);
        $name     = Security::clean($_POST['name']);
        $detail   = Security::clean($_POST['detail']);
        $currency = Security::clean($_POST['currency']);
        $userId   = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $userId, $name, $detail, $currency) {
            
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);

            $role = $planModel->getUserRole($planId, $userId);

            if ($role === 'admin') {
                $planModel->update($planId, $name, $detail, $currency);
            } else {
                throw new Exception("Intento no autorizado de editar plan $planId por usuario $userId");
            }

            header("Location: index.php?action=plan_settings&id=" . $planId);
            exit;

        }, "index.php?action=plan_settings&id=" . $planId, "Update Plan");
    }

    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (empty($_POST['name'])) {
            header("Location: index.php?action=dashboard&error=missing_name");
            exit;
        }

        $name   = Security::clean($_POST['name']);
        $detail = Security::clean($_POST['detail']);
        $userId = $_SESSION['user_id'];

        Logger::safeRun(function() use ($name, $detail, $userId) {
            
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);
            
            if (!$planModel->create($name, $userId, $detail)) {
                throw new Exception("Fallo en la creación del plan (Transaction rollback).");
            }
            
            header("Location: index.php?action=dashboard&msg=created");
            exit;

        }, "index.php?action=dashboard", "Store Plan");
    }

    public function storeMember()
    {
        if (!isset($_SESSION['user_id'], $_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $planId   = Security::cleanInt($_POST['plan_id']);
        $username = Security::clean($_POST['username']);
        $userId   = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $username, $userId) {
            
            $db = (new Database())->getConnection();
            $userModel = new User($db);
            $planModel = new Plan($db);

            $role = $planModel->getUserRole($planId, $userId);
            if ($role !== 'admin') throw new Exception("Permiso denegado para añadir miembros.");

            $existingUser = $userModel->findByUsername($username);

            if ($existingUser) {
                try {
                    $planModel->addMember($planId, $existingUser['id'], 'member');
                } catch (Exception $e) {
                }
                header("Location: index.php?action=plan_settings&id=" . $planId . "&msg=member_added");
                exit;
            } else {
                header("Location: index.php?action=plan_settings&id=" . $planId . "&error=user_not_found");
                exit;
            }

        }, "index.php?action=plan_settings&id=" . $planId, "Add Member");
    }

    public function removeMember()
    {
        if (!isset($_SESSION['user_id'], $_GET['plan_id'], $_GET['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $planId       = Security::cleanInt($_GET['plan_id']);
        $targetUserId = Security::cleanInt($_GET['user_id']);
        $userId       = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $targetUserId, $userId) {
            
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);

            $role = $planModel->getUserRole($planId, $userId);
            if ($role !== 'admin') throw new Exception("Permiso denegado para expulsar miembros.");

            if ($targetUserId == $userId) throw new Exception("Admin intentó borrarse a sí mismo.");

            $planModel->removeMember($planId, $targetUserId);
            
            header("Location: index.php?action=plan_settings&id=" . $planId . "&msg=member_removed");
            exit;

        }, "index.php?action=plan_settings&id=" . $planId, "Remove Member");
    }

    public function delete()
    {
        if (!isset($_SESSION['user_id'], $_GET['id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $planId = Security::cleanInt($_GET['id']);
        $userId = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $userId) {
            
            $db = (new Database())->getConnection();
            $planModel = new Plan($db);

            $role = $planModel->getUserRole($planId, $userId);
            
            if ($role === 'admin') {
                $planModel->delete($planId);
            } else {
                throw new Exception("Intento de borrar plan $planId sin ser admin.");
            }
            
            header("Location: index.php?action=dashboard&msg=plan_deleted");
            exit;

        }, "index.php?action=dashboard", "Delete Plan");
    }
}