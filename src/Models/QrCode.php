<?php
namespace Models;

use Config\Database;
require_once __DIR__ . '/../../config/App.php';
require_once BASE_PATH . '/config.local.php';


class QrCode
{
    //obtiene los qrs guardados
    public static function getAllWithScans()
    {
        $db = Database::getConnection();
        $query = "
            SELECT q.*, COUNT(s.id) AS total_scans 
            FROM qr_codes q 
            LEFT JOIN qr_scans s ON q.id = s.qr_id 
            GROUP BY q.id 
            ORDER BY q.id DESC
        ";
        return $db->query($query)->fetchAll();
    }

    public static function findByCode($code)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE code = :code");
        $stmt->execute(['code' => $code]);
        return $stmt->fetch();
    }

    public static function findById($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function findByIds(array $ids)
    {
        if (empty($ids))
            return [];

        $db = Database::getConnection();
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE id IN ($in)");
        $stmt->execute($ids);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function create($code, $client_name, $target_url)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO qr_codes (code, client_name, target_url) VALUES (:code, :client_name, :target_url)");
        return $stmt->execute(['code' => $code, 'client_name' => $client_name, 'target_url' => $target_url]);
    }

    public static function update($id, $code, $client_name, $target_url)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE qr_codes SET code = :code, client_name = :client_name, target_url = :target_url WHERE id = :id");
        return $stmt->execute(['id' => $id, 'code' => $code, 'client_name' => $client_name, 'target_url' => $target_url]);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM qr_codes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    //Validar si un código ya existe
    public static function existsCode($code, $excludeId = null)
    {
        $db = Database::getConnection();
        if ($excludeId) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM qr_codes WHERE code = :code AND id != :id");
            $stmt->execute(['code' => $code, 'id' => $excludeId]);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM qr_codes WHERE code = :code");
            $stmt->execute(['code' => $code]);
        }
        return $stmt->fetchColumn() > 0;
    }

    // 2. Generar el siguiente código autoincrementable con el prefijo
    public static function generateNextCode($prefix = DEFAULT_QR_PREFIX)
    {
        $db = Database::getConnection();
        // Busca los códigos que inicien con el prefijo especificado
        $stmt = $db->prepare("SELECT code FROM qr_codes WHERE code LIKE :prefix ORDER BY id DESC LIMIT 1");
        $stmt->execute(['prefix' => $prefix . '%']);
        $lastCode = $stmt->fetchColumn();

        if ($lastCode) {
            // Extrae el numero del codigo (ej. "skm-0005" -> 5)
            $number = (int) str_replace($prefix, '', $lastCode);
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1;
        }

        // Retorna el prefijo seguido del número formateado a 4 dígitos (skm-0001)
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function generatePdf()
    {
        require_once __DIR__ . '/../../views/admin/admin-pdf.php';
    }
}