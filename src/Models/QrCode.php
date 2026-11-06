<?php
namespace Models;

use PDO;
use Config\Database;
require_once __DIR__ . '/../../config/App.php';
require_once BASE_PATH . '/config.local.php';


class QrCode
{
    //obtiene los qrs por user guardados 
    public static function getAllWithScans($userId)
    {
        $db = Database::getConnection();

        $query = "
            SELECT q.*, COUNT(s.id) AS total_scans 
            FROM qr_codes q
            LEFT JOIN qr_scans s ON q.id = s.qr_id 
            WHERE q.user_id = :user_id
            GROUP BY q.id 
            ORDER BY q.id DESC
        ";

        $stmt = $db->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll();
    }

    // Obtiene un QR por su código(para redirección)
    public static function findByCode($code)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE code = :code LIMIT 1");
        $stmt->execute(['code' => trim($code)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtiene un QR por su ID y el ID del usuario
    public static function findByIdAndUser($id, $userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->fetch();
    }

    // Obtiene múltiples QRs por sus IDs
    public static function findByIds(array $ids)
    {
        if (empty($ids))
            return [];

        $db = Database::getConnection();
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE id IN ($in)");
        $stmt->execute($ids);

        return $stmt->fetchAll();
    }

    // Crea un nuevo QR en la base de datos
    public static function create($code, $userId, $client_name, $target_url, $color_foreground, $color_background, $color_text )
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO qr_codes (code, user_id, client_name, target_url, color_foreground, color_background, color_text) VALUES (:code, :user_id, :client_name, :target_url, :color_foreground, :color_background, :color_text)");
        return $stmt->execute([
            'code' => $code,
            'user_id' => $userId,
            'client_name' => $client_name,
            'target_url' => $target_url,
            'color_foreground' => $color_foreground,
            'color_background' => $color_background,
            'color_text' => $color_text
        ]);
    }

    public static function update($id, $userId, $code, $client_name, $target_url, $color_foreground, $color_background, $color_text)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE qr_codes SET code = :code, client_name = :client_name, target_url = :target_url, color_foreground = :color_foreground, color_background = :color_background, color_text = :color_text WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'code' => $code,
            'client_name' => $client_name,
            'target_url' => $target_url,
            'color_foreground' => $color_foreground,
            'color_background' => $color_background,
            'color_text' => $color_text
        ]);
    }

    public static function deleteByIdAndUser($id, $userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM qr_codes WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }


    //generar un código aleatorio de 6 caracteres, evitando caracteres visualmente similares
    public static function generateRandomCode()
    {
        $db = Database::getConnection();

        // Alfabeto optimizado: se eliminan caracteres visualmente idénticos (O, 0, I, l, 1)
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $charsLength = strlen($chars);

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[rand(0, $charsLength - 1)];
            }

            // Verificamos de forma global en la base de datos si ya existe ese código corto
            $stmt = $db->prepare("SELECT COUNT(*) FROM qr_codes WHERE code = :code");
            $stmt->execute([':code' => $code]);
            $exists = $stmt->fetchColumn() > 0;

        } while ($exists); // Si coincide con uno viejo, repite el ciclo y crea otro

        return $code;
    }

    /**
     * Obtiene todos los QRs de la plataforma con el nombre del dueño
     * y el conteo de escaneos totales (Exclusivo para el Superadmin).
     */
    public static function getAllGlobalWithScans()
    {
        $db = Database::getConnection();

        $query = "
        SELECT 
            q.id,
            q.code,
            q.client_name,
            q.target_url,
            q.created_at,
            u.username AS owner_username,
            COUNT(s.id) AS total_scans
        FROM qr_codes q
        INNER JOIN users u ON q.user_id = u.id
        LEFT JOIN qr_scans s ON q.id = s.qr_id
        GROUP BY q.id
        ORDER BY q.id DESC
    ";

        $stmt = $db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene métricas rápidas globales para los contadores del Dashboard SaaS.
     */
    public static function getGlobalMetrics()
    {
        $db = Database::getConnection();

        // Contar QRs totales
        $qrs = $db->query("SELECT COUNT(*) FROM qr_codes")->fetchColumn();

        // Contar Escaneos totales
        $scans = $db->query("SELECT COUNT(*) FROM qr_scans")->fetchColumn();

        // Contar Clientes administradores totales
        $users = $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

        return [
            'total_qrs' => $qrs,
            'total_scans' => $scans,
            'total_users' => $users
        ];
    }
}