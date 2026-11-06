<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - QRify</title>
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
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Términos y Condiciones de Uso</h1>
            <p class="text-sm text-slate-400 mt-2">Última actualización: <?= date('d/m/Y') ?></p>
        </div>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">1. Aceptación de los Términos</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                Al solicitar acceso, registrarse o utilizar los servicios de gestión de códigos QR dinámicos proporcionados por <strong>QRify</strong> (en adelante, "la plataforma"), el usuario acepta cumplir y estar sujeto a los presentes Términos y Condiciones. Si no está de acuerdo con estos términos, le solicitamos que no utilice el servicio.
            </p>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">2. Registro de Cuentas y Periodo de Prueba</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                El acceso a la plataforma se realiza mediante la creación manual de cuentas por parte de la administración tras una solicitud previa de contacto. Cada cuenta nueva cuenta con un <strong>periodo de prueba gratuito de 30 días</strong> a partir de su fecha de activación. Durante este periodo, el usuario podrá administrar sus códigos QR bajo las especificaciones de su plan.
            </p>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">3. Pagos, Renovaciones y Suspensión del Servicio</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                Finalizado el periodo de prueba o el ciclo mensual correspondiente, el usuario deberá actualizar su contraprestación económica mediante los canales de comunicación y pago establecidos con la administración. 
            </p>
            <ul class="list-disc list-inside text-sm space-y-2 text-slate-400 pl-2">
                <li><strong>Periodo de Tolerancia:</strong> Vencida la fecha de pago mensual, el usuario dispondrá de un plazo de <strong>tolerancia de 2 (dos) días naturales</strong> para regularizar su situación.</li>
                <li><strong>Suspensión de Cuenta:</strong> Si transcurrido el periodo de tolerancia no se ha efectuado el pago correspondiente, la cuenta y sus redirecciones de códigos QR asociados serán <strong>pausadas temporalmente</strong> hasta que se confirme la recepción del pago pendiente.</li>
            </ul>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">4. Uso Adecuado y Responsabilidad del Contenido</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                Cada usuario (Administrador de cuenta) es el único responsable de la información, textos, nombres comerciales y, fundamentalmente, de las URLs de destino asociadas a sus códigos QR. 
                Queda estrictamente prohibido redirigir los códigos QR a sitios web que contengan malware, material fraudulento, estafas, contenido ilegal, violaciones a derechos de autor o cualquier actividad maliciosa. QRify se reserva el derecho de suspender de forma definitiva aquellas cuentas que incumplan esta directriz.
            </p>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-white">5. Privacidad y Aislamiento de Datos</h2>
            <p class="text-sm leading-relaxed text-slate-400">
                La plataforma garantiza la confidencialidad de los datos de cada cliente. Ningún usuario externo o administrador de otra cuenta tendrá acceso a los códigos QR o métricas de terceros. El superadministrador cuenta únicamente con privilegios de supervisión técnica de solo lectura, sin facultades de manipulación o alteración de los contenidos del usuario.
            </p>
        </section>

    </main>

    <footer class="border-t border-slate-800/80 py-8 text-center text-xs text-slate-500 mt-20">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-300"><a href="<?= BASE_URL . 'home' ?>">QRify</a></span> &copy; <?= date('Y') ?>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL . 'home' ?>" class="hover:text-slate-300 transition-colors">inicio</a>
                <a href="<?= BASE_URL . 'privacidad' ?>" class="hover:text-slate-300 transition-colors">Política de Privacidad</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>