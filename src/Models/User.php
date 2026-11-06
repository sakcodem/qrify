<?php
namespace Models;

use Config\Database;

class User {

    // Obtiene un usuario por su ID
    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Obtiene todos los usuarios con el rol de 'admin'
    public static function getAllClients() {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE role = 'admin'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Busca un usuario por su nombre de usuario
    public static function findByUsername($username) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    // Crea un nuevo usuario con el rol de 'admin'
    public static function createClient($username, $email, $passwordHash) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'admin')");
        return $stmt->execute(['username' => $username, 'email' => $email, 'password' => $passwordHash]);
    }

    // Actualiza un usuario existente, opcionalmente actualizando la contraseña
    public static function update($id, $username, $email, $passwordHash = null) {
        $db = Database::getConnection();
        if ($passwordHash) {
            $stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
            return $stmt->execute(['username' => $username, 'email' => $email, 'password' => $passwordHash, 'id' => $id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
            return $stmt->execute(['username' => $username, 'email' => $email, 'id' => $id]);
        }
    }
}