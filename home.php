<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRify - Códigos QR Dinámicos, Analíticas y Personalización</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="h-full font-sans text-slate-200 bg-slate-950 antialiased selection:bg-indigo-500 selection:text-white"
    x-data="{ successMessage: false }">

    <header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-600/30">
                    <i data-lucide="qr-code" class="w-6 h-6"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">QRify</span>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400">
                <a href="#caracteristicas" class="hover:text-white transition-colors">Características</a>
                <a href="#como-funciona" class="hover:text-white transition-colors">¿Cómo funciona?</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="login"
                    class="text-sm font-semibold text-slate-300 hover:text-white transition-colors px-3 py-2">
                    Iniciar Sesión
                </a>
                <a href="#contacto"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs sm:text-sm px-4 py-2 rounded-xl transition-all shadow-lg shadow-indigo-600/20">
                    Probar Gratis
                </a>
            </div>
        </div>
    </header>

    <section class="relative pt-20 pb-16 md:pt-32 md:pb-24 overflow-hidden">
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/20 via-slate-950 to-slate-950 -z-10">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <span
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-6">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> La plataforma SaaS definitiva para tus códigos QR
            </span>

            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight max-w-4xl mx-auto leading-tight">
                Gestiona tus códigos QR 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-emerald-400">dinámicos sin límites</span>
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto">
                Edita los enlaces de destino en tiempo real sin volver a imprimir. Personaliza colores, descarga
                imágenes en alta calidad y analiza métricas de escaneo al instante.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#contacto"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-3.5 rounded-xl text-base transition-all shadow-xl shadow-indigo-600/25 flex items-center justify-center gap-2">
                    Probar Gratis <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="#caracteristicas"
                    class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 font-semibold px-8 py-3.5 rounded-xl text-base transition-all flex items-center justify-center">
                    Ver Funcionalidades
                </a>
            </div>

            <div
                class="mt-16 relative max-w-5xl mx-auto rounded-2xl border border-slate-800 bg-slate-900/60 p-4 shadow-2xl backdrop-blur-sm">
                <div
                    class="bg-slate-950 rounded-xl border border-slate-800/80 p-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex items-center gap-4">
                        <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl">
                            <i data-lucide="qr-code" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">QRs Dinámicos</p>
                            <p class="text-xl font-bold text-white">Personalizados</p>
                        </div>
                    </div>
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                            <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Escaneos Totales</p>
                            <p class="text-xl font-bold text-white">Métricas en Vivo</p>
                        </div>
                    </div>
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex items-center gap-4">
                        <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl">
                            <i data-lucide="download" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Exportación</p>
                            <p class="text-xl font-bold text-white">PNG / ZIP</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="caracteristicas" class="py-20 bg-slate-900/40 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl font-extrabold text-white tracking-tight">Todo lo que necesitas para gestionar tus
                    QRs</h2>
                <p class="mt-3 text-slate-400">Diseñado especialmente para negocios, restaurantes y agencias que buscan
                    flexibilidad total.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition-all shadow-xl">
                    <div
                        class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20 flex items-center justify-center mb-5">
                        <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">QRs Dinámicos</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Cambia la URL de destino las veces que quieras sin alterar la imagen del QR o volver a imprimir
                        mantelería o folletos.
                    </p>
                </div>

                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition-all shadow-xl">
                    <div
                        class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20 flex items-center justify-center mb-5">
                        <i data-lucide="palette" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Personalización de Colores</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Ajusta los tonos del código QR para alinearlos a la identidad visual de tu marca o negocio.
                    </p>
                </div>

                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition-all shadow-xl">
                    <div
                        class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20 flex items-center justify-center mb-5">
                        <i data-lucide="file-image" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Exportación PNG y ZIP</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Descarga individualmente tus QRs en alta definición o genera archivos masivos comprimidos en
                        ZIP.
                    </p>
                </div>

                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition-all shadow-xl">
                    <div
                        class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20 flex items-center justify-center mb-5">
                        <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Estadísticas en Tiempo Real</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Monitorea cuántas personas escanean cada código QR y analiza el rendimiento de tus campañas.
                    </p>
                </div>

                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition-all shadow-xl">
                    <div
                        class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20 flex items-center justify-center mb-5">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Gestión SaaS Segura</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Cada cliente tiene un panel privado e independiente para administrar su catálogo de códigos
                        dinámicos.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="como-funciona" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl font-extrabold text-white tracking-tight">¿Cómo funciona QRify?</h2>
                <p class="mt-3 text-slate-400">Crea, imprime y actualiza tus códigos en tres sencillos pasos.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 text-center relative">
                    <div
                        class="w-10 h-10 bg-indigo-600 text-white font-bold rounded-full flex items-center justify-center mx-auto mb-4 text-sm shadow-lg shadow-indigo-600/30">
                        1</div>
                    <h3 class="text-lg font-bold text-white">Crea tu QR Dinámico</h3>
                    <p class="mt-2 text-sm text-slate-400">Genera tu código asignándole un enlace de destino inicial y
                        personaliza su diseño con tus colores corporativos.</p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 text-center relative">
                    <div
                        class="w-10 h-10 bg-indigo-600 text-white font-bold rounded-full flex items-center justify-center mx-auto mb-4 text-sm shadow-lg shadow-indigo-600/30">
                        2</div>
                    <h3 class="text-lg font-bold text-white">Imprímelo una sola vez</h3>
                    <p class="mt-2 text-sm text-slate-400">Descarga tu QR en PNG y colócalo en tu local, menús,
                        folletos o empaques sin preocuparte por reimprimir.</p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 text-center relative">
                    <div
                        class="w-10 h-10 bg-indigo-600 text-white font-bold rounded-full flex items-center justify-center mx-auto mb-4 text-sm shadow-lg shadow-indigo-600/30">
                        3</div>
                    <h3 class="text-lg font-bold text-white">Edita cuando quieras</h3>
                    <p class="mt-2 text-sm text-slate-400">Si cambias de menú, de sitio web o de promoción, actualiza el
                        enlace en tu panel y el código físico seguirá funcionando.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-20 bg-slate-900/40 border-t border-slate-800/80">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white tracking-tight">Solicita tu acceso de prueba</h2>
                <p class="mt-3 text-slate-400">Llena el formulario y nos pondremos en contacto contigo para darte de
                    alta en QRify de forma gratuita.</p>
            </div>

            <div class="mt-12 bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">

                <?php
                $enviado = false;
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contacto_submit'])) {
                    $nombre = htmlspecialchars($_POST['nombre']);
                    $email = htmlspecialchars($_POST['email']);
                    $telefono = htmlspecialchars($_POST['telefono']);
                    $mensaje = htmlspecialchars($_POST['mensaje']);

                    $tu_correo = "sak.code25@gmail.com";
                    $asunto = "Nuevo interesado en Probar Gratis - QRify";
                    $cuerpo = "Has recibido una nueva solicitud de acceso:\n\nNombre: $nombre\nCorreo: $email\nTeléfono: $telefono\nMensaje:\n$mensaje";

                    $headers = "From: contacto@qrify.sakdev.com\r\n";

                    @mail($tu_correo, $asunto, $cuerpo, $headers);
                    $enviado = true;
                }
                ?>

                <?php if ($enviado): ?>
                    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-6 rounded-2xl text-center space-y-2">
                        <i data-lucide="check-circle" class="w-10 h-10 mx-auto"></i>
                        <h3 class="text-lg font-bold text-white">¡Solicitud enviada con éxito!</h3>
                        <p class="text-sm">Hemos recibido tus datos correctamente. Nos pondremos en contacto contigo muy
                            pronto.</p>
                    </div>
                <?php else: ?>
                    <form action="#contacto" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-2">Nombre completo</label>
                                <input type="text" name="nombre" required placeholder="Ej. Juan Pérez"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-2">Correo electrónico</label>
                                <input type="email" name="email" required placeholder="juan@ejemplo.com"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Teléfono / WhatsApp</label>
                            <input type="text" name="telefono" placeholder="+52 123 456 7890"
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Cuéntanos sobre tu negocio
                                (Opcional)</label>
                            <textarea name="mensaje" rows="3" placeholder="¿Cuántos QRs estimas utilizar?"
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <button type="submit" name="contacto_submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-indigo-600/25 flex items-center justify-center gap-2">
                            Enviar Solicitud Gratis <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <footer class="border-t border-slate-800/80 py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-300"><a href="<?= BASE_URL . 'home' ?>">QRify</a></span> &copy;
                <?= date('Y') ?>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL . 'login' ?>" class="hover:text-slate-300 transition-colors">Acceso Clientes</a>
                <a href="<?= BASE_URL . 'privacidad' ?>" class="hover:text-slate-300 transition-colors">Política de
                    Privacidad</a>
                <a href="<?= BASE_URL . 'terminos' ?>" class="hover:text-slate-300 transition-colors">Términos y
                    Condiciones</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>