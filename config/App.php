<?php

// 1. Ruta absoluta del sistema de archivos (disco duro)
define('BASE_PATH', dirname(__DIR__) . '/');

// 2. Determinar el protocolo (http:// o https://)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';

// 3. Evaluar el host/dominio actual
$host = $_SERVER['HTTP_HOST'];

// Detectar si estamos trabajando en Localhost o en Producción
if ($host === 'localhost' || $host === '127.0.0.1') {
    // Entorno Local (Laragon)
    define('BASE_URL', $protocol . $host . '/bodebase/public/');
} else {
    // Entorno de Producción (Servidor Real)
    define('BASE_URL', $protocol . $host . '/');
}
