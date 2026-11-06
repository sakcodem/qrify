<?php
namespace Controllers;

use Models\QrCode as QrCodeModel;
use Controllers\AuthController as AuthController;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Color\Color;

require_once __DIR__ . '/../../config/App.php';
require_once __DIR__ . '/../../config.local.php';

class AdminController
{
    private $userId;
    public function __construct()
    {
        $authController = new AuthController();
        $authController->authCheck();

        //Si está logueado pero NO es un cliente ('admin'), bloquear acceso
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(404);
            require_once BASE_PATH . 'views/errors/error.php';
            exit();
        }

        $this->userId = $_SESSION['user_id'];
    }

    public function index()
    {
        $edit_id = $_GET['edit'] ?? null;
        $edit_qr = $edit_id ? QrCodeModel::findByIdAndUser($edit_id, $this->userId) : null;
        if ($edit_id && !$edit_qr) {
            $_SESSION['error'] = "El recurso no existe.";
            header("Location: admin");
            exit();
        }
        $qrs = QrCodeModel::getAllWithScans($this->userId);

        require_once BASE_PATH . 'views/admin/index.php';
    }

    public function save()
    {
        $id = $_POST['id'] ?? null;
        $client_name = trim($_POST['client_name'] ?? '');
        $target_url = trim($_POST['target_url'] ?? '');
        $color_foreground = $_POST['color_foreground'] ?? '#FFFFFF';
        $color_background = $_POST['color_background'] ?? '#000000';
        $color_text = $_POST['color_text'] ?? '#FFFFFF';

        // Validación básica: la URL de destino es obligatoria
        if (empty($target_url)) {
            $_SESSION['error'] = "La URL de destino no puede estar vacía.";
            header("Location: admin" . ($id ? "?edit=$id" : ""));
            exit();
        }

        if ($id) {
            // Al EDITAR: Buscamos el QR primero para asegurarnos de que le pertenece al usuario
            $existingQr = QrCodeModel::findByIdAndUser($id, $this->userId);

            if (!$existingQr) {
                $_SESSION['error'] = "El recurso no existe.";
                header("Location: admin");
                exit();
            }

            // Actualizamos manteniendo el mismo $code que ya tenía
            QrCodeModel::update($id, $this->userId, $existingQr['code'], $client_name, $target_url, $color_foreground, $color_background, $color_text);
            $_SESSION['success'] = "El recurso ha sido actualizado.";
        } else {
            // Al CREAR: El modelo se encarga de generar el código aleatorio y único
            $code = QrCodeModel::generateRandomCode();
            QrCodeModel::create($code, $this->userId, $client_name, $target_url, $color_foreground, $color_background, $color_text);
            $_SESSION['success'] = "El recurso ha sido creado.";
        }

        header("Location: admin");
        exit();
    }



    public function delete()
    {
        $qrId = $_GET['delete'] ?? null;
        if ($qrId) {
            QrCodeModel::deleteByIdAndUser($qrId, $this->userId);
        }
        header("Location: admin");
        exit();
    }

    public static function generatePdf()
    {
        // Leer el payload JSON enviado por fetch()
        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['qr_ids'] ?? [];

        if (empty($ids)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se seleccionaron QRs']);
            exit();
        }

        // 1. Obtener los QRs de la base de datos a través del modelo
        $qrs = QrCodeModel::findByIds($ids);

        $writer = new PngWriter();

        // 3. Construir la estructura HTML dentro de la memoria del controlador
        $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: sans-serif; margin: 0; padding: 10px; }
                    .grid { width: 100%; border-collapse: collapse; }
                    .card-qr {
                        width: 20%;
                        display: inline-block;
                        border: 1px dashed #ccc;
                        border-radius: 6px;
                        padding: 6px;
                        margin: 1%;
                        text-align: center;
                        box-sizing: border-box;
                        vertical-align: top;
                    }
                    .card-qr img { width: 100px; height: 100px; }
                    .code-label { font-size: 14px; font-weight: bold; margin-top: 6px; }
                    .client-label { font-size: 11px; color: #555; margin-top: 2px; }
                </style>
            </head>
            <body>
            <div style="width: 100%;">';

        foreach ($qrs as $qr) {
            $url = BASE_URL . "/" . $qr['code'];

            $qrCode = new QrCode(
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 300,
                margin: 5
            );

            $result = $writer->write($qrCode);
            $base64DataUri = $result->getDataUri();

            $clientName = !empty($qr['client_name']) ? htmlspecialchars($qr['client_name']) : '';

            $html .= '
                    <div class="card-qr">
                        <img src="' . $base64DataUri . '" alt="QR">
                        <div class="code-label">' . htmlspecialchars($qr['code']) . '</div>';
            if ($clientName) {
                $html .= '<div class="client-label">' . $clientName . '</div>';
            }
            $html .= '</div>';
        }

        $html .= '
            </div>
            </body>
            </html>';

        // 4. Convertir la plantilla HTML a PDF usando Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 5. Emitir el PDF directamente hacia el cliente
        date_default_timezone_set(TIMEZONE);

        $fechaActual = date('Ymd-His');
        $nombrePdf = "qrs-impresion-{$fechaActual}.pdf";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nombrePdf . '"');
        header('Content-Length: ' . strlen($dompdf->output()));

        header('Access-Control-Expose-Headers: X-Filename');
        header('X-Filename: ' . $nombrePdf);

        echo $dompdf->output();
        exit();
    }

    private static function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        return array_map('hexdec', str_split($hex, 2));
    }

    public static function generatePng()
    {
        $fontPath = BASE_PATH . 'assets/fonts/Roboto-Regular.ttf';
        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['qr_ids'] ?? [];

        if (empty($ids)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se seleccionaron QRs']);
            exit();
        }

        $qrs = QrCodeModel::findByIds($ids);

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseUrl = $protocol . "://" . $_SERVER['HTTP_HOST'];

        $writer = new PngWriter();
        $tempDir = __DIR__ . '/../../temp_qrs/';

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $generatedFiles = [];

        foreach ($qrs as $qr) {
            $url = $baseUrl . "/" . $qr['code'];
            $codeText = $qr['code'];
            $rgbFore = self::hexToRgb($qr['color_foreground'] ?? '#FFFFFF');
            $rgbBack = self::hexToRgb($qr['color_background'] ?? '#000000');
            $rgbText = self::hexToRgb($qr['color_text'] ?? '#FFFFFF');

            // Generar QR con Endroid
            $qrCode = new QrCode(
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                //Ancho y alto en píxeles
                size: 500,
                //Espacio en blanco alrededor del QR interno
                margin: 5,
                //COLOR DE LOS PUNTOS (Foreground): Color RGB (Negro por defecto)
                foregroundColor: new Color($rgbFore[0], $rgbFore[1], $rgbFore[2]),
                //COLOR DE FONDO (Background): Color RGB (Blanco por defecto)
                backgroundColor: new Color($rgbBack[0], $rgbBack[1], $rgbBack[2])

            );
            $result = $writer->write($qrCode);
            $qrImageBinary = $result->getString();

            // Procesar con GD para poner la leyenda abajo
            $qrImage = imagecreatefromstring($qrImageBinary);
            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);

            $padding = 1;// Margen exterior alrededor de toda la etiqueta combinada
            $footerHeight = 40;// Altura del espacio inferior reservado para la leyenda de texto
            $finalWidth = $qrWidth + ($padding * 2);
            $finalHeight = $qrHeight + $footerHeight + $padding;

            $finalImage = imagecreatetruecolor($finalWidth, $finalHeight);
            $white = imagecolorallocate($finalImage, 255, 255, 255);
            $black = imagecolorallocate($finalImage, 0, 0, 0);
            $colorRectangle = imagecolorallocate($finalImage, $rgbBack[0], $rgbBack[1], $rgbBack[2]);
            imagefilledrectangle($finalImage, 0, 0, $finalWidth, $finalHeight, $colorRectangle);
            $textColorId = imagecolorallocate($finalImage, $rgbText[0], $rgbText[1], $rgbText[2]);

            imagecopy($finalImage, $qrImage, $padding, $padding, 0, 0, $qrWidth, $qrHeight);

            $fontSize = 30;
            // 3. Calcular el ancho y alto exacto que ocupará el texto con esa fuente
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $codeText);
            $textWidth = abs($bbox[4] - $bbox[0]);
            $textHeight = abs($bbox[5] - $bbox[1]);

            // 4. Calcular la posición para centrar el texto debajo del QR
            $textX = ($finalWidth - $textWidth) / 2;
            $textY = $qrHeight + $padding + 30; // Ajusta este valor si necesitas más separación vertical

            // 5. Dibujar el texto en la imagen usando la fuente TTF
            imagettftext(
                $finalImage,  // El lienzo de la imagen
                $fontSize,    // Tamaño de la letra libre
                0,            // Ángulo de rotación (0 para texto horizontal normal)
                $textX,       // Posición horizontal centrada
                $textY,       // Posición vertical
                $textColorId, // Color del texto
                $fontPath,    // Ruta al archivo .ttf
                $codeText     // El texto del código (ej: skm-0001)
            );

            $fileName = "qr-" . $codeText . ".png";
            $filePath = $tempDir . $fileName;
            imagepng($finalImage, $filePath);

            imagedestroy($qrImage);
            imagedestroy($finalImage);

            $generatedFiles[] = $filePath;
        }

        // CASO 1: Solo 1 QR seleccionado -> Descargar PNG directo
        if (count($generatedFiles) === 1) {
            $fileToDownload = $generatedFiles[0];
            $fileName = basename($fileToDownload);

            // Limpiar cualquier búfer previo para evitar corrupción del archivo
            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: image/png');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Access-Control-Expose-Headers: Content-Disposition');
            header('Content-Length: ' . filesize($fileToDownload));
            readfile($fileToDownload);
            unlink($fileToDownload);
            exit();
        }

        // CASO 2: Múltiples QRs -> Empaquetar en ZIP
        date_default_timezone_set(TIMEZONE);
        $fechaActual = date('Ymd-His');
        $zipName = "qr-codes-export-" . $fechaActual . ".zip";
        $zipPath = $tempDir . $zipName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($generatedFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // Limpiar cualquier búfer previo para evitar corrupción del archivo
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);

        foreach ($generatedFiles as $file) {
            @unlink($file);
        }
        @unlink($zipPath);
        exit();
    }
}