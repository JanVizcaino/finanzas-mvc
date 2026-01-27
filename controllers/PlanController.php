<?php
require_once '../config/Database.php';
require_once '../models/Plan.php';
require_once '../models/Expense.php';

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

        $isGlobalAdmin = ($_SESSION['role'] ?? '') === 'admin';

        if ($isGlobalAdmin) {
            // CASO 1: Es Admin Global -> Le dejamos ver el plan aunque no sea miembro
            $plan = $planModel->getPlanById($planId);

            // Forzamos el rol visual a 'admin' para que vea los botones de editar
            $currentUserRole = 'admin';
        } else {
            // CASO 2: Usuario Normal -> Usamos la función restrictiva
            $plan = $planModel->getPlanDetails($planId, $_SESSION['user_id']);

            // Obtenemos su rol real dentro del plan
            $currentUserRole = $planModel->getUserRole($planId, $_SESSION['user_id']);
        }

        // Si después de todo esto no hay plan, entonces sí denegamos acceso
        if (!$plan) {
            die("El plan no existe o no tienes permiso para verlo.");
        }

        $currentUserRole = $planModel->getUserRole($planId, $_SESSION['user_id']);
        $members = $planModel->getMembers($planId);
        $expenses = $expenseModel->getByPlan($planId);

        require '../views/layout/header.php';
        require '../views/plans/show.php';
        require '../views/layout/footer.php';
    }
    public function viewSettings()
    {
        // 1. Seguridad: Verificar sesión
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // 2. Obtener ID del Plan
        if (!isset($_GET['id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $planId = $_GET['id'];

        // 3. Conexión y Modelos
        $db = (new Database())->getConnection();
        $planModel = new Plan($db);
        // Necesitamos el modelo de gastos solo para calcular el total si lo muestras en el header
        $expenseModel = new Expense($db);

        // 4. Obtener Datos del Plan (Validar acceso)
        $plan = $planModel->getPlanDetails($planId, $_SESSION['user_id']);
        if (!$plan) {
            // Podrías redirigir con un error o mostrar una página 403
            die("Acceso denegado o el plan no existe.");
        }

        // 5. Obtener Rol, Miembros y Gastos (para el total del header)
        $currentUserRole = $planModel->getUserRole($planId, $_SESSION['user_id']);
        $members = $planModel->getMembers($planId);

        // Calculamos el total gastado para mostrarlo en el header (-150€)
        $expenses = $expenseModel->getByPlan($planId);
        $totalExpenses = 0;
        foreach ($expenses as $e) {
            $totalExpenses += $e['amount'];
        }



        // 6. Cargar la Vista
        require '../views/layout/header.php';
        require '../views/plans/plan_settings.php';
        require '../views/layout/footer.php';
    }

    public function update()
    {
        if (!isset($_SESSION['user_id']) || !isset($_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $db = (new Database())->getConnection();
        $planModel = new Plan($db);

        // Verificar si el usuario es admin del plan antes de actualizar
        $role = $planModel->getUserRole($_POST['plan_id'], $_SESSION['user_id']);

        if ($role === 'admin') {
            // Asumiendo que tengas un método update en tu modelo Plan
            $planModel->update($_POST['plan_id'], $_POST['name'], $_POST['detail'], $_POST['currency']);

            // Si no tienes el método update aún, tendrás que crearlo en el Modelo Plan.php
        }

        // Redirigir de vuelta a settings
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
        $email = $_POST['email'];

        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos para añadir gente.");

        $existingUser = $user->findByEmail($email);

        if ($existingUser) {
            $targetUserId = $existingUser['id'];

            try {
                $plan->addMember($planId, $targetUserId, 'member');
            } catch (Exception $e) {
            }

            header("Location: index.php?action=plan_settings&id=" . $planId);
        } else {
            die("Error: El usuario con el email <b>$email</b> no está registrado en el sistema. Pídele que se registre antes de añadirlo.");
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
