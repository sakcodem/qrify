<?php
namespace Controllers;

use Models\QrCode;
use Models\Scan;

class RedirectController
{
    public function handleRedirect($code)
    {
        $qr = QrCode::findByCode($code);

        if ($qr) {
            // Registrar métrica
            Scan::record(
                $qr['id'],
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $_SERVER['REMOTE_ADDR'] ?? null
            );

            // Redirección 302 hacia el destino del QR
            header("Location: " . $qr['target_url'], true, 302);
            exit();
        }

        http_response_code(404);

        require_once BASE_PATH . 'views/errors/error.php';

        exit();
    }
}