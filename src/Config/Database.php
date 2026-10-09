<?php
namespace Config;

use PDO;
use PDOException;
require_once __DIR__ . '/../../config/App.php';
include_once BASE_PATH . '/config.local.php';

class Database {
    private static $host = DB_HOST;
    private static $dbname = DB_NAME;
    private static $user = DB_USER;
    private static $pass = DB_PASS;
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            date_default_timezone_set('UTC');

            try {
                self::$pdo = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$user,
                    self::$pass,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false
                    ]
                );

                self::$pdo->exec("SET time_zone = '+00:00';");

            } catch (PDOException $e) {
                die("Error en la conexión DB: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}