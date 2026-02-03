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

    private function checkAdminAuth() {
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
        $users = $this->adminModel->getAllUsers();
        $plans = $this->adminModel->getAllPlans();
        
        require '../views/layout/header.php';
        require '../views/admin/dashboard.php';
        require '../views/layout/footer.php';        
    }

    public function storeUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
$this->userModel->create($username, $passwordHash, $role);        }
        
        header("Location: index.php?action=admin_panel");
        exit;
    }

    public function updateUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_POST['id'], $_POST['username'], $_POST['role'])) {
            $id = $_POST['id'];
            $username = trim($_POST['username']);
            $role = $_POST['role'];
            
            $passwordHash = null;
            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $this->userModel->update($id, $username, $role, $passwordHash);
        }
        
        header("Location: index.php?action=admin_panel");
        exit;
    }

    public function deleteUser()
    {
        $this->checkAdminAuth();
        if (isset($_GET['id'])) {
            if ($_GET['id'] == $_SESSION['user_id']) {
                header("Location: index.php?action=admin_panel");
                exit;
            }
            $this->userModel->delete($_GET['id']);
        }
        header("Location: index.php?action=admin_panel");
        exit;
    }

    public function deletePlan()
    {
        $this->checkAdminAuth();
        if (isset($_GET['id'])) {
            $this->adminModel->deletePlanAsAdmin($_GET['id']);
        }
        header("Location: index.php?action=admin_panel");
        exit;
    }
}
?>