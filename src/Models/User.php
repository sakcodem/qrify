<?php
namespace Models;

use Config\Database;

class User {
    public static function findByUsername($username) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}