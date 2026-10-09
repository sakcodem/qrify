<?php
namespace Controllers;

use Models\QrCode;
use Models\Scan;

class RedirectController {
    public function handleRedirect($code) {
        $qr = QrCode::findByCode($code);

        if ($qr) {
            // Registrar métrica
            Scan::record(
                $qr['id'], 
                $_SERVER['HTTP_USER_AGENT'] ?? null, 
                $_SERVER['REMOTE_ADDR'] ?? null
            );

            // Redirección 302
            header("Location: " . $qr['target_url'], true, 302);
            exit();
        }

        http_response_code(404);
        echo "<h1>404 - Código QR no encontrado</h1>";
        echo "<p>El código <strong>" . htmlspecialchars($code) . "</strong> no existe.</p>";
    }
}