<?php
require_once '../config/Database.php';
require_once '../models/Admin.php';

class AdminController
{
    private $db;
    private $adminModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->adminModel = new Admin($this->db);
    }

    // Middleware de seguridad: Si no es admin, lo expulsa
    private function checkAdminAuth() {
        // Asegurarse de que la sesión esté iniciada (ya lo hace index.php, pero por seguridad)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificamos si existe la variable de sesión 'role' y si es 'admin'
        // Nota: Debes asegurarte de guardar el 'role' en $_SESSION cuando el usuario hace login en UserController
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            // Si no es admin, redirigir al dashboard normal o logout
            header("Location: index.php?action=dashboard"); 
            exit;
        }
    }

    public function index()
    {
        // 1. Verificamos permisos
        $this->checkAdminAuth();

        $users = $this->adminModel->getAllUsers();
        $plans = $this->adminModel->getAllPlans();

        require '../views/layout/header.php';
        require '../views/admin/dashboard.php';
        require '../views/layout/footer.php';        
    }

    public function createUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deleteUserAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
    }

    public function storeUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deleteUserAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
    }

    public function editUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deleteUserAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
    }

    public function updateUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deleteUserAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
    }


    public function deleteUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deleteUserAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
    }

    public function deletePlan()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deletePlanAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
    }

    
}
?>