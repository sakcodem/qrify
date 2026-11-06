<?php
namespace Controllers;

use Models\User;

class AuthController {
    public function showLogin($error = '') {
        if(isset($_SESSION['user_id'])) {
            $redirectUrl = $_SESSION['role'] === 'superadmin' ? 'superadmin' : 'admin';
            header("Location: " . $redirectUrl);
            exit();

        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function login() {
        // Opcional: Si intenta enviar el POST estando ya logueado, se redirige
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            $redirectUrl = ($_SESSION['role'] === 'superadmin') ? 'superadmin' : 'admin';
            header("Location: " . $redirectUrl);
            exit();
        }
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: " . ($user['role'] === 'superadmin' ? 'superadmin' : 'admin'));
            exit();
        }

        $this->showLogin("Usuario o contraseña incorrectos");
    }

    public function authCheck() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: login");
        exit();
    }
}