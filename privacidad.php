
<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - QRify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="h-full font-sans text-slate-300 bg-slate-950 antialiased selection:bg-indigo-500 selection:text-white">

    <header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-600/30">
                    <i data-lucide="qr-code" class="w-6 h-6"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">QRify</span>
            </div>
            <a href="<?= BASE_URL . 'home' ?>" class="text-xs sm:text-sm font-semibold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
                <i data-lucide="chevron-left" class="w-4 h-4"></i> Volver al inicio
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
        
        <div class="border-b border-slate-800 pb-6">
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Política de Privacidad</h1>
            <p class="text-sm text-slate-400 mt-2">Última actualización: <?= date('d/m/Y') ?></p>
        </div>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">1. Información que Recopilamos a través de la Landing Page</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                Cuando un visitante utiliza el formulario de contacto o solicitud de prueba gratuita en nuestra página principal, recopilamos datos básicos de contacto (nombre, correo electrónico, teléfono y mensaje opcional). 
                <strong>Aclaración importante:</strong> En el momento del envío inicial, dichos datos no se almacenan automáticamente en una base de datos pública o persistente de registros comerciales; son recibidos de forma directa vía correo electrónico por el administrador con el único fin de establecer comunicación comercial directa.
            </p>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">2. Datos de Cuenta en el SaaS</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                Si el usuario decide avanzar y contratar o iniciar su periodo de prueba en la plataforma, la administración procederá a dar de alta su cuenta registrando datos de identificación esenciales (nombre, apellidos, correo electrónico y teléfono) necesarios para la gestión administrativa del servicio, facturación y soporte técnico.
            </p>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">3. Datos de Escaneo y Analítica de Códigos QR</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                Para el correcto funcionamiento de las estadísticas de los códigos QR creados por los usuarios, cada vez que un tercero escanea un código QR dinámico, la plataforma registra de forma automatizada y anónima los siguientes datos técnicos:
            </p>
            <ul class="list-disc list-inside text-sm space-y-2 text-slate-400 pl-2">
                <li><strong>Dirección IP:</strong> Utilizada para estimar métricas geográficas y de conteo de visitas únicas.</li>
                <li><strong>User Agent:</strong> Información del navegador y tipo de dispositivo móvil (iOS, Android, etc.) empleado por el visitante.</li>
            </ul>
            <p class="text-sm leading-relaxed text-slate-400 mt-2">
                Estos registros analíticos son estrictamente privados y accesibles únicamente por el propietario del código QR correspondiente para medir el rendimiento de sus campañas.
            </p>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">4. Seguridad y Confidencialidad</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                QRify implementa medidas de seguridad técnicas orientadas a proteger la base de datos y evitar accesos no autorizados. Los datos de tus clientes y URLs de destino se encuentran protegidos bajo estrictos esquemas de aislamiento por usuario, asegurando que ninguna entidad ajena pueda visualizar o manipular tu información.
            </p>
        </section>

    </main>

    <footer class="border-t border-slate-800/80 py-8 text-center text-xs text-slate-500 mt-20">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-300"><a href="<?= BASE_URL . 'home' ?>">QRify</a></span> &copy; <?= date('Y') ?>.
            </div>
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL . 'home' ?>" class="hover:text-slate-300 transition-colors">inicio</a>
                <a href="<?= BASE_URL . 'terminos' ?>" class="hover:text-slate-300 transition-colors">Términos y Condiciones</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>