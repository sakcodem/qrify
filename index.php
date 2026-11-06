<?php
session_start();
require_once __DIR__ . '/config/App.php';

// CARGAR LIBRERÍAS DE COMPOSER (Endroid, Dompdf, etc.)
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Autoload para cargar clases desde src/ (incluyendo Config)
spl_autoload_register(function ($class) {
    // Convierte el namespace en ruta de archivo (ej: Config\Database -> Config/Database.php)
    $file = str_replace('\\', '/', $class) . '.php';

    // 1. Buscar en la estructura src/ (respetando mayúsculas)
    $path = __DIR__ . '/src/' . $file;
    if (file_exists($path)) {
        require_once $path;
        return;
    }

    // 2. Respaldo insensible a mayúsculas para Linux (por si acaso)
    $pathLower = __DIR__ . '/src/' . strtolower($file);
    if (file_exists($pathLower)) {
        require_once $pathLower;
        return;
    }
});

use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\SuperAdminController;
use Controllers\RedirectController;

$route = $_GET['route'] ?? '';
$route = rtrim($route, '/');

// Enrutador Front Controller
switch ($route) {
    case '':
    case 'home':
           require_once __DIR__ . '/home.php';
        break;
    case 'terminos':
        require_once __DIR__ . '/terminos.php';
        break;
    case 'privacidad':
        require_once __DIR__ . '/privacidad.php'; 
         break;
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'admin':
        $controller = new AdminController();
        $controller->index();
        break;

    case 'admin-save':
        $controller = new AdminController();
        $controller->save();
        break;
    case 'admin-pdf':
        $controller = new AdminController();
        $controller->generatePdf();
        break;
    case 'admin-png':
        $controller = new AdminController();
        $controller->generatePng();
        break;
    case 'admin-delete':
        $controller = new AdminController();
        $controller->delete();
        break;
    case 'superadmin':
        $controller = new SuperAdminController();
        $controller->index();
        break;
    case 'superadmin/users':
        $controller = new SuperAdminController();
        $controller->users();
        break;
    case 'superadmin/save-user':
        $controller = new SuperAdminController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->saveUser();
        } else {
            http_response_code(404);
            require_once BASE_PATH . 'views/errors/error.php';
        }
        break;

    case 'superadmin/get-user':
        (new SuperAdminController())->getUserJson();
        break;

    case 'superadmin/update-user':
        (new SuperAdminController())->updateUser();
        break;

    case 'superadmin/qrs':
        $controller = new SuperAdminController();
        $controller->qrs();
        break;

    default:
        // Cualquier otra ruta corta (ej: /re-0001) procesa la redirección
        $controller = new RedirectController();
        $controller->handleRedirect($route);
        break;
}
?>
