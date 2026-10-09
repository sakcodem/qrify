<?php
namespace Controllers;

use Models\User;

class AuthController {
    public function showLogin($error = '') {
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function login() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: admin");
            exit();
        }

        $this->showLogin("Usuario o contraseña incorrectos");
    }

    public function logout() {
        session_destroy();
        header("Location: login");
        exit();
    }
}