<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QRify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans text-slate-200 bg-slate-950 flex flex-col md:flex-row antialiased">

    <?php include BASE_PATH . 'views/layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Gestión de Códigos QR</h2>
                    <p class="text-sm text-slate-400 mt-1">Crea, edita y monitorea tus enlaces dinámicos en tiempo real.
                    </p>
                </div>
            </div>

        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>