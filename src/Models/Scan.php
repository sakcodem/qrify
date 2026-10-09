<?php
namespace Models;

use Config\Database;

class Scan {
    public static function record($qr_id, $user_agent, $ip_address) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO qr_scans (qr_id, user_agent, ip_address) VALUES (:qr_id, :ua, :ip)");
        return $stmt->execute([
            'qr_id' => $qr_id,
            'ua'    => $user_agent,
            'ip'    => $ip_address
        ]);
    }
}