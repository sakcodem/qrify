<?php
namespace Controllers;

use Models\QrCode as QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../config/App.php';

class AdminController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }
    }

    public function index()
    {
        $edit_id = $_GET['edit'] ?? null;
        $edit_qr = $edit_id ? QrCodeModel::findById($edit_id) : null;
        $qrs = QrCodeModel::getAllWithScans();

        require_once __DIR__ . '/../../views/admin/index.php';
    }

    public function save()
    {
        $id = $_POST['id'] ?? null;
        $code = trim($_POST['code']);
        $client_name = trim($_POST['client_name']);
        $target_url = trim($_POST['target_url']);

        // Autogenerar código si viene vacío
        if (empty($code)) {
            $code = QrCodeModel::generateNextCode();
        }

        // Validar que el código sea único
        if (QrCodeModel::existsCode($code, $id)) {
            // Redirige o muestra error si el código ya existe
            $_SESSION['error'] = "El código '$code' ya está en uso. Por favor elige otro.";
            header("Location: admin" . ($id ? "?edit=$id" : ""));
            exit();
        }

        if ($id) {
            QrCodeModel::update($id, $code, $client_name, $target_url);
        } else {
            QrCodeModel::create($code, $client_name, $target_url);
        }

        header("Location: admin");
        exit();
    }


    public function delete()
    {
        $id = $_GET['delete'] ?? null;
        if ($id) {
            QrCodeModel::delete($id);
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
        date_default_timezone_set('America/Merida');

        $fechaActual = date('Y-m-d');
        $nombrePdf = "qrs-impresion-{$fechaActual}.pdf";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nombrePdf . '"');
        header('Content-Length: ' . strlen($dompdf->output()));

        header('Access-Control-Expose-Headers: X-Filename'); // Permite que JavaScript lea esta cabecera
        header('X-Filename: ' . $nombrePdf);

        echo $dompdf->output();
        exit();
    }
}