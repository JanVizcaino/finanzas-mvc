<?php
require_once '../config/Database.php';
require_once '../models/Expense.php';
require_once '../models/Plan.php';

class ExpenseController
{
    // URL de tu Webhook en n8n
    private $webhookUrl = "http://localhost:5678/webhook-test/odin_mvc";

    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $db = (new Database())->getConnection();
        $expenseModel = new Expense($db);

        // Modelos adicionales para el webhook
        $planModel = new Plan($db);
        $userModel = new User($db);

        if (isset($_POST['plan_id'], $_POST['title'], $_POST['amount'])) {

            $receiptPath = null;

            // Lógica de subida de archivo (se mantiene igual)
            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                $fileExtension = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('receipt_') . '.' . $fileExtension;
                $targetPath = $uploadDir . $fileName;
                $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
                if (in_array(strtolower($fileExtension), $allowedTypes)) {
                    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
                        $receiptPath = 'uploads/' . $fileName;
                    }
                }
            }

            // 1. CREAR EL GASTO
            $result = $expenseModel->create(
                $_POST['plan_id'],
                $_SESSION['user_id'],
                $_POST['title'],
                $_POST['amount'],
                $_POST['category'],
                $receiptPath,
                $_POST['detail']
            );

            // 2. DISPARAR WEBHOOK (Si se creó correctamente)
            if ($result) {
                // A. Obtener datos del Creador (Usuario actual)
                $creator = $userModel->getById($_SESSION['user_id']);
                $creatorName = $creator['username'];

                // B. Obtener Emails de los suscriptores de ESTE plan
                $recipients = $planModel->getSubscribers($_POST['plan_id']);

                // C. Enviar solo si hay destinatarios
                if (!empty($recipients)) {
                    $payload = [
                        "event" => "new_expense",
                        "timestamp" => date('c'),
                        "plan_id" => $_POST['plan_id'],
                        "creator_name" => $creatorName,
                        "expense_title" => $_POST['title'],
                        "amount" => $_POST['amount'],
                        "category" => $_POST['category'],
                        "recipients" => $recipients // Array de emails
                    ];

                    $this->sendWebhook($payload);
                }
            }

            header("Location: index.php?action=view_plan&id=" . $_POST['plan_id']);
            exit;
        } else {
            echo "Faltan datos para crear el gasto.";
        }
    }

    // Función auxiliar privada para cURL
    private function sendWebhook($data)
    {
        $json_data = json_encode($data);
        $ch = curl_init($this->webhookUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        // Timeout muy corto (1s) para no bloquear al usuario si n8n tarda
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ]);

        curl_exec($ch);
        curl_close($ch);
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // 1. IMPORTANTE: Usamos isset con $_POST, no con $_GET
        if (isset($_POST['id']) && isset($_POST['plan_id'])) {

            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            // 2. EXTRAEMOS LAS VARIABLES AQUÍ PARA USARLAS LUEGO
            $expenseId = $_POST['id'];
            $planId = $_POST['plan_id']; // <--- Aquí guardamos el ID del plan que viene del formulario

            // Verificar permisos usando la variable $planId
            $role = $planModel->getUserRole($planId, $_SESSION['user_id']);
            if ($role !== 'admin') {
                header("Location: index.php?action=view_plan&id=" . $planId);
                exit;
            }

            // Obtener gasto actual
            $currentExpense = $expenseModel->getById($expenseId);
            if (!$currentExpense) {
                die("Error: El gasto no existe.");
            }

            $receiptPath = $currentExpense['receipt_path'];

            // Lógica de archivo
            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                $fileExtension = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('receipt_') . '.' . $fileExtension;
                $targetPath = $uploadDir . $fileName;
                $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

                if (in_array(strtolower($fileExtension), $allowedTypes)) {
                    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
                        $receiptPath = 'uploads/' . $fileName;
                    }
                }
            }

            // Actualizar
            $expenseModel->update(
                $expenseId,
                $_POST['title'],
                $_POST['amount'],
                $_POST['category'],
                $receiptPath,
                $_POST['detail']
            );

            // 3. LA SOLUCIÓN A TU ERROR ESTÁ AQUÍ:
            // Usamos la variable $planId que definimos arriba. 
            // NO uses $_GET['plan_id'] ni $_POST['plan_id'] aquí directamente.
            header("Location: index.php?action=view_plan&id=" . $planId);
            exit;
        } else {
            // Si entra aquí, es que el formulario no está enviando los inputs hidden
            echo "Error: No llegaron los datos por POST.";
            var_dump($_POST);
        }
    }

    public function viewReceipt()
    {
        // 1. SEGURIDAD BÁSICA: ¿Está logueado?
        if (!isset($_SESSION['user_id'])) {
            die("Acceso denegado. Debes iniciar sesión.");
        }

        if (isset($_GET['id'])) {
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            // Obtenemos el gasto para saber su plan y el nombre del archivo
            $expense = $expenseModel->getById($_GET['id']);

            if (!$expense) {
                die("El gasto no existe.");
            }

            // 2. SEGURIDAD AVANZADA: ¿El usuario pertenece al plan de este gasto?
            // Reutilizamos tu lógica de roles para saber si es miembro
            $role = $planModel->getUserRole($expense['plan_id'], $_SESSION['user_id']);

            if (!$role) {
                // Si no tiene rol (no es admin ni miembro), prohibimos el acceso
                http_response_code(403);
                die("No tienes permiso para ver este recibo.");
            }

            // 3. SERVIR EL ARCHIVO
            // Construimos la ruta real en el servidor. 
            // IMPORTANTE: Ajusta '../uploads/' según donde esté tu carpeta real respecto al index.php

            // Si en la BBDD guardaste 'uploads/archivo.jpg', y el script corre en /public
            // necesitamos salir un nivel: '../' + 'uploads/archivo.jpg'
            $filePath = '../' . $expense['receipt_path'];

            if (file_exists($filePath)) {
                // Detectamos qué tipo de archivo es (imagen, pdf, etc.)
                $mimeType = mime_content_type($filePath);

                // Enviamos las cabeceras correctas al navegador
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
                header('Content-Length: ' . filesize($filePath));

                // Leemos el archivo y lo enviamos al output
                readfile($filePath);
                exit;
            } else {
                http_response_code(404);
                die("El archivo físico no se encuentra en el servidor.");
            }
        }
    }
    public function delete()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (isset($_GET['id']) && isset($_GET['plan_id'])) {
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            $role = $planModel->getUserRole($_GET['plan_id'], $_SESSION['user_id']);

            if ($role === 'admin') {
                $expenseModel->delete($_GET['id']);
            } else {
            }

            header("Location: index.php?action=view_plan&id=" . $_GET['plan_id']);
            exit;
        }
    }
}
