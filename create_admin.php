<?php
// Incluir el autoloader para poder usar las clases
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $srcPath = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($srcPath)) {
            require_once $srcPath;
        }
    }
});

use Config\Database;

// DATOS DEL NUEVO ADMINISTRADOR (Modifícalos según tus preferencias)
$username = 'saul';
$password = 'saul123';

try {
    $db = Database::getConnection();

    // 1. Encriptar la contraseña con el algoritmo bcrypt
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // 2. Insertar el usuario en la base de datos
    $stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
    $stmt->execute([
        'username'      => $username,
        'password_hash' => $passwordHash
    ]);

    echo "<div style='font-family: system-ui; padding: 20px; background: #e6fffa; border: 1px solid #38b2ac; border-radius: 8px; max-width: 500px; margin: 40px auto;'>";
    echo "<h3 style='color: #234e52; margin-top: 0;'>¡Usuario Administrador Creado!</h3>";
    echo "<p><strong>Usuario:</strong> " . htmlspecialchars($username) . "</p>";
    echo "<p><strong>Contraseña:</strong> " . htmlspecialchars($password) . "</p>";
    echo "<p style='color: #e53e3e; font-weight: bold;'>⚠️ IMPORTANTE: Elimina el archivo <code>create_admin.php</code> de tu servidor inmediatamente por razones de seguridad.</p>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<div style='font-family: system-ui; padding: 20px; background: #fff5f5; border: 1px solid #feb2b2; border-radius: 8px; max-width: 500px; margin: 40px auto;'>";
    echo "<h3 style='color: #9b2c2c; margin-top: 0;'>Error al crear el usuario</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}