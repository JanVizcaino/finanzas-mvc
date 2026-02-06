<?php

require_once '../config/Database.php';
require_once '../models/Admin.php';
require_once '../models/User.php';

class AdminController
{
    private $db;
    private $adminModel;
    private $userModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->adminModel = new Admin($this->db);
        $this->userModel = new User($this->db);
    }

    private function checkAdminAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=dashboard");
            exit;
        }
    }

    public function index()
    {
        $this->checkAdminAuth();

        Logger::safeRun(function() {
            $users = $this->adminModel->getAllUsers();
            $plans = $this->adminModel->getAllPlans();

            require '../views/layout/header.php';
            require '../views/admin/dashboard.php';
            require '../views/layout/footer.php';
            
        }, "index.php?action=dashboard", "Admin Dashboard Load");
    }

    public function storeUser()
    {
        $this->checkAdminAuth();

        if (!isset($_POST['username'], $_POST['password'], $_POST['role'])) {
            header("Location: index.php?action=admin_panel&error=missing_fields");
            exit;
        }

        $username = Security::clean($_POST['username']);
        $role     = Security::clean($_POST['role']);
        $password = $_POST['password']; 

        Logger::safeRun(function() use ($username, $password, $role) {
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $result = $this->userModel->create($username, $passwordHash, $role);
            
            if (!$result) {
                throw new Exception("Error al crear usuario en BD.");
            }

            header("Location: index.php?action=admin_panel&msg=user_created");
            exit;

        }, "index.php?action=admin_panel", "Admin Store User");
    }

    public function updateUser()
    {
        $this->checkAdminAuth();

        if (!isset($_POST['id'], $_POST['username'], $_POST['role'])) {
            header("Location: index.php?action=admin_panel&error=missing_fields");
            exit;
        }

        $id       = Security::cleanInt($_POST['id']); 
        $username = Security::clean($_POST['username']);
        $role     = Security::clean($_POST['role']);
        
        $password = !empty($_POST['password']) ? $_POST['password'] : null;

        Logger::safeRun(function() use ($id, $username, $role, $password) {

            $passwordHash = null;
            if ($password) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            }

            $result = $this->userModel->update($id, $username, $role, $passwordHash);

            if (!$result) {
                throw new Exception("Error al actualizar usuario ID: $id");
            }

            header("Location: index.php?action=admin_panel&msg=user_updated");
            exit;

        }, "index.php?action=admin_panel", "Admin Update User");
    }

    public function deleteUser()
    {
        $this->checkAdminAuth();

        if (isset($_GET['id'])) {
            $id = Security::cleanInt($_GET['id']);

            if ($id == $_SESSION['user_id']) {
                header("Location: index.php?action=admin_panel&error=self_delete");
                exit;
            }

            Logger::safeRun(function() use ($id) {
                
                $this->userModel->delete($id);
                
                header("Location: index.php?action=admin_panel&msg=user_deleted");
                exit;

            }, "index.php?action=admin_panel", "Admin Delete User");
        } else {
            header("Location: index.php?action=admin_panel");
        }
    }

    public function deletePlan()
    {
        $this->checkAdminAuth();

        if (isset($_GET['id'])) {
            $id = Security::cleanInt($_GET['id']);

            Logger::safeRun(function() use ($id) {
                
                $this->adminModel->deletePlanAsAdmin($id);
                
                header("Location: index.php?action=admin_panel&msg=plan_deleted");
                exit;

            }, "index.php?action=admin_panel", "Admin Delete Plan");
        } else {
            header("Location: index.php?action=admin_panel");
        }
    }
}