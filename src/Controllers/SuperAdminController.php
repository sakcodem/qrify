<?php
namespace Controllers;

use Models\QrCode as QrCodeModel;
use Models\User as UserModel;
use Controllers\AuthController as AuthController;

require_once __DIR__ . '/../../config/App.php';

class SuperAdminController
{
    private $userId;
    public function __construct()
    {
        $authController = new AuthController();
        $authController->authCheck();

        // 2. Si está logueado pero NO es un cliente ('superadmin'), bloquear acceso
        if ($_SESSION['role'] !== 'superadmin') {
            http_response_code(404);
            require_once BASE_PATH . 'views/errors/error.php';
            exit();
        }
        $this->userId = $_SESSION['user_id'];
    }

    public function index()
    {
        $edit_id = $_GET['edit'] ?? null;
        $edit_qr = $edit_id ? QrCodeModel::findByIdAndUser($edit_id, $this->userId) : null;
        $qrs = QrCodeModel::getAllWithScans($this->userId);
        $total_qrs = count($qrs);

        require_once BASE_PATH . 'views/superadmin/index.php';
    }

    public function users()
    {
        $users = UserModel::getAllClients(); // Obtener todos los clientes (admins)
        $total_users = count($users);
        require_once BASE_PATH . 'views/superadmin/users.php';
    }

    //Procesa la creación de un nuevo cliente (admin) desde el panel de Superadmin

    public function saveUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password) || empty($email)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: users");
                exit();
            }

            // Validar si el usuario ya existe en la base de datos
            $existingUser = UserModel::findByUsername($username);
            if ($existingUser) {
                $_SESSION['error'] = "El nombre de usuario $username ya está registrado.";
                header("Location:  users");
                exit();
            }

            // Crear el nuevo usuario forzando el rol 'admin' (tu cliente)
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            UserModel::createClient($username, $email, $passwordHash);

            $_SESSION['success'] = "Administrador creado con éxito.";
        }

        header("Location: users");
        exit();
    }

    // Obtiene los datos de un usuario específico en formato JSON para edición
    public function getUserJson()
    {
        // Aseguramos que sea una petición GET y traiga un ID
        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no provisto']);
            exit();
        }

        $user = UserModel::findById($id); // Buscas al usuario en la BD

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            exit();
        }

        // Devolvemos solo los datos necesarios por seguridad (nunca mandes el hash de la contraseña)
        header('Content-Type: application/json');
        echo json_encode([
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'] ?? ''
        ]);
        exit();
    }

    // Actualiza un usuario existente (admin) desde el panel de Superadmin
    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // Opcional, solo si desea cambiarla

            if (!$id || empty($username) || empty($email)) {
                $_SESSION['error'] = "Campos obligatorios vacíos.";
                exit();
            }

            // Si envió contraseña, la hasheamos, si no, pasamos null para mantener la vieja
            $passwordHash = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;

            // Tu modelo se encargará de hacer el UPDATE
            UserModel::update($id, $username, $email, $passwordHash);
            $_SESSION['success'] = "Administrador actualizado con éxito.";
            header("Location: users");
            exit();
        }
    }

    public function qrs()
    {
        $metrics = QrCodeModel::getGlobalMetrics();
        $total_qrs_count = number_format($metrics['total_qrs']);
        $total_scans_count = number_format($metrics['total_scans']);
        $total_users_count = number_format($metrics['total_users']);

        $global_qrs = QrCodeModel::getAllGlobalWithScans();

        //header("Location: qrs");
        require_once BASE_PATH . 'views/superadmin/qrs.php';
    }


}