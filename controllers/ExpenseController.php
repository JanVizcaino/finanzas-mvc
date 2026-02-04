<?php
require_once '../config/Database.php';
require_once '../models/Expense.php';
require_once '../models/Plan.php';
require_once '../models/User.php'; 

class ExpenseController
{
    private $webhookUrl = "http://host.docker.internal:5678/webhook/odin_mvc";

    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $db = (new Database())->getConnection();
        $expenseModel = new Expense($db);
        $planModel = new Plan($db);
        $userModel = new User($db);

        if (isset($_POST['plan_id'], $_POST['title'], $_POST['amount'])) {

            $receiptPath = null;

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

            $result = $expenseModel->create(
                $_POST['plan_id'],
                $_SESSION['user_id'],
                $_POST['title'],
                $_POST['amount'],
                $_POST['category'],
                $receiptPath, 
                $_POST['detail'] 
            );

            if ($result) {
                $creator = $userModel->getById($_SESSION['user_id']);
                $creatorName = $creator['username'];
                $recipients = $planModel->getSubscribers($_POST['plan_id']);

                if (!empty($recipients)) {
                    $amountFormatted = number_format($_POST['amount'], 2) . "€";
                    $planLink = "http://localhost:8081/index.php?action=view_plan&id=" . $_POST['plan_id'];

                    foreach ($recipients as $emailTo) {
                        $payload = [
                            "type" => "Email",
                            "event" => "new_expense",
                            "recipient_email" => $emailTo,
                            "template_title" => "Nuevo Gasto: " . $_POST['title'],
                            "template_description" => "Hola, <b>$creatorName</b> acaba de registrar un gasto de <b>$amountFormatted</b> en la categoría " . $_POST['category'] . ".",
                            "template_link" => $planLink,
                            "raw_data" => [
                                "plan_id" => $_POST['plan_id'],
                                "amount" => $_POST['amount'],
                                "creator" => $creatorName
                            ]
                        ];
                        $this->sendWebhook($payload);
                    }
                }
            }

            header("Location: index.php?action=view_plan&id=" . $_POST['plan_id']);
            exit;
        } else {
            echo "Faltan datos.";
        }
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (isset($_POST['id']) && isset($_POST['plan_id'])) {
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            $expenseId = $_POST['id'];
            $planId = $_POST['plan_id'];

            $currentExpense = $expenseModel->getById($expenseId);
            $planRole = $planModel->getUserRole($planId, $_SESSION['user_id']);

            if ($planRole !== 'admin' && $currentExpense['user_id'] != $_SESSION['user_id']) {
                header("Location: index.php?action=view_plan&id=" . $planId);
                exit;
            }

            $receiptPath = $currentExpense['receipt_path'];

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

            $expenseModel->update(
                $expenseId,
                $_POST['title'],
                $_POST['amount'],
                $_POST['category'],
                $receiptPath,
                $_POST['detail']
            );

            header("Location: index.php?action=view_plan&id=" . $planId);
            exit;
        }
    }

    public function delete()
    {
        if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login");

        if (isset($_GET['id']) && isset($_GET['plan_id'])) {
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            $expenseId = $_GET['id'];
            $planId = $_GET['plan_id'];
            $userId = $_SESSION['user_id'];

            $expense = $expenseModel->getById($expenseId);

            if ($expense) {
                $planRole = $planModel->getUserRole($planId, $userId);

                if ($planRole === 'admin' || $expense['user_id'] == $userId) {

                    if (!empty($expense['receipt_path']) && file_exists('../' . $expense['receipt_path'])) {
                        unlink('../' . $expense['receipt_path']);
                    }
                    $expenseModel->delete($expenseId);
                }
            }
            header("Location: index.php?action=view_plan&id=" . $planId);
            exit;
        }
    }

    public function viewReceipt()
    {
        if (!isset($_SESSION['user_id'])) die("Acceso denegado.");

        if (isset($_GET['id'])) {
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            $expense = $expenseModel->getById($_GET['id']);
            if (!$expense) die("Gasto no encontrado.");

            $role = $planModel->getUserRole($expense['plan_id'], $_SESSION['user_id']);
            if (!$role) {
                http_response_code(403);
                die("No tienes permiso.");
            }

            $filePath = '../' . $expense['receipt_path'];
            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            } else {
                http_response_code(404);
                die("Archivo no encontrado.");
            }
        }
    }

    private function sendWebhook($data)
    {
        $json_data = json_encode($data);
        $ch = curl_init($this->webhookUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 400);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
