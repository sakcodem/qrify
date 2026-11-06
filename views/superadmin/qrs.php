<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qrs - QRify</title>
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

<body x-data="{ 
        openPreviewModal: false, 
        previewCode: '', 
        previewName: '',
        searchQuery: '',
        copiedCode: '',
    }" class="h-full font-sans text-slate-200 bg-slate-950 flex flex-col md:flex-row antialiased">

    <?php include BASE_PATH . '/views/layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <i data-lucide="globe" class="w-7 h-7 text-indigo-500"></i> Monitoreo de QRs Globales
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">Supervisa, audita y analiza los enlaces creados por todos los clientes del SaaS en tiempo real.</p>
                </div>
            </div>

            <?php include BASE_PATH . '/views/layouts/alerts.php'; ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div
                    class="p-6 bg-slate-900/60 border border-slate-800/80 rounded-2xl flex items-center gap-4 shadow-xl backdrop-blur-sm transition-all hover:border-slate-700">
                    <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total QRs Creados</p>
                        <h3 class="text-2xl font-black text-white mt-1"><?= $total_qrs_count ?? 0 ?></h3>
                    </div>
                </div>
                <div class="p-6 bg-slate-900/60 border border-slate-800/80 rounded-2xl flex items-center gap-4 shadow-xl backdrop-blur-sm transition-all hover:border-slate-700">
                    <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
                        <i data-lucide="scan" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Escaneos Totales</p>
                        <h3 class="text-2xl font-black text-white mt-1"><?= number_format($total_scans_count ?? 0) ?>
                        </h3>
                    </div>
                </div>
                <div class="p-6 bg-slate-900/60 border border-slate-800/80 rounded-2xl flex items-center gap-4 shadow-xl backdrop-blur-sm transition-all hover:border-slate-700">
                    <div class="p-3 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Clientes Activos</p>
                        <h3 class="text-2xl font-black text-white mt-1"><?= $total_users_count ?? 0 ?></h3>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
                <div
                    class="p-5 border-b border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-950/30">
                    <div class="relative w-full sm:w-80">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" x-model="searchQuery" placeholder="Buscar por cliente, código o URL..."
                            class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="text-xs text-slate-400">
                        Mostrando registros de la plataforma
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-800 bg-slate-950/50 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <th class="p-4">Cliente / Dueño</th>
                                <th class="p-4">Nombre Cliente (Ficha)</th>
                                <th class="p-4">Código Corto</th>
                                <th class="p-4">URL Destino</th>
                                <th class="p-4 text-center">Escaneos</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
                            <?php if (empty($global_qrs)): ?>
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-slate-500 font-medium">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div
                                                class="p-4 bg-slate-950 rounded-full border border-slate-800 text-slate-600">
                                                <i data-lucide="folder-open" class="w-8 h-8"></i>
                                            </div>
                                            <span>No se han encontrado códigos QR registrados en la plataforma.</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($global_qrs as $qr): ?>
                                    <tr class="hover:bg-slate-800/30 transition-colors group" x-show="window.matchSearch(searchQuery, 
                                            '<?= addslashes($qr['owner_username'] ?? '') ?>', 
                                            '<?= addslashes($qr['client_name'] ?? '') ?>', 
                                            '<?= addslashes($qr['code'] ?? '') ?>', 
                                            '<?= addslashes($qr['target_url'] ?? '') ?>'
                                        )">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="font-medium text-slate-200"><?= htmlspecialchars($qr['owner_username'] ?? '') ?></span>
                                            </div>
                                        </td>

                                        <td class="p-4 font-semibold text-white">
                                            <?= htmlspecialchars($qr['client_name'] ?? 'Sin nombre') ?>
                                        </td>

                                        <td class="p-4 font-mono text-indigo-400 text-xs">
                                            <div
                                                class="inline-flex items-center gap-1.5 bg-indigo-950/40 border border-indigo-900/50 px-2.5 py-1 rounded-lg">
                                                <span><?= htmlspecialchars($qr['code'] ?? '') ?></span>
                                                <button @click="window.copyUrl($data, '<?= htmlspecialchars($qr['code']) ?>')"
                                                    class="text-indigo-400 hover:text-white transition-colors"
                                                    title="Copiar enlace corto">
                                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                            <span x-show="copiedCode === '<?= htmlspecialchars($qr['code']) ?>'" x-cloak
                                                class="block text-[10px] text-emerald-400 mt-0.5">¡Copiado!</span>
                                        </td>

                                        <td class="p-4 max-w-xs truncate text-slate-400">
                                            <a href="<?= htmlspecialchars($qr['target_url'] ?? '#') ?>" target="_blank"
                                                class="hover:text-indigo-400 transition-colors flex items-center gap-1 truncate"
                                                title="<?= htmlspecialchars($qr['target_url'] ?? '') ?>">
                                                <span class="truncate"><?= htmlspecialchars($qr['target_url'] ?? '') ?></span>
                                                <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0 opacity-60"></i>
                                            </a>
                                        </td>

                                        <td class="p-4 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-white border border-slate-700">
                                                <?= number_format($qr['total_scans'] ?? 0) ?>
                                            </span>
                                        </td>

                                        <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                            <button
                                                @click="window.showQR($data, '<?= htmlspecialchars($qr['code']) ?>', '<?= htmlspecialchars(addslashes($qr['client_name'] ?? 'QR')) ?>')"
                                                class="text-slate-400 hover:text-indigo-400 p-2 hover:bg-indigo-500/10 rounded-xl transition-all"
                                                title="Ver e Imprimir QR">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <a href="/qrify/superadmin/delete-qr?id=<?= $qr['id'] ?>"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar este código QR de forma permanente? No se podrá recuperar.')"
                                                class="inline-block text-slate-400 hover:text-red-400 p-2 hover:bg-red-500/10 rounded-xl transition-all"
                                                title="Eliminar QR de la plataforma">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div x-show="openPreviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div x-show="openPreviewModal" @click="openPreviewModal = false"
            class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" x-transition></div>

        <div x-show="openPreviewModal"
            class="relative bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-sm sm:p-6 p-4 shadow-2xl space-y-6 text-center overflow-hidden"
            x-transition>

            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <h3 class="text-lg font-bold text-white truncate max-w-[240px]" x-text="previewName"></h3>
                <button @click="openPreviewModal = false" class="text-slate-400 hover:text-white focus:outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="bg-white p-5 rounded-2xl inline-block mx-auto shadow-xl border border-slate-200">
                <div id="qrcode-canvas" class="flex justify-center"></div>
            </div>

            <div class="space-y-1 bg-slate-950/50 p-3 rounded-xl border border-slate-800">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Código Identificador</p>
                <p class="text-sm font-mono text-indigo-400 font-bold" x-text="previewCode"></p>
            </div>

            <div class="flex gap-3 pt-2">
                <button @click="openPreviewModal = false"
                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-all">
                    Cerrar
                </button>
                <button @click="window.printQR()"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-lg shadow-indigo-600/20">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Imprimir
                </button>
            </div>
        </div>
    </div>

    <script>
        window.printQR = function () {
            let qrImage = document.querySelector('#qrcode-canvas img');
            if (!qrImage) {
                alert('El código QR no se ha terminado de generar.');
                return;
            }

            let printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Imprimir Código QR - QRify</title>
                    <style>
                        body { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif; margin: 0; background: #fff; }
                        img { width: 280px; height: 280px; margin-bottom: 16px; }
                        h1 { font-size: 20px; color: #0f172a; margin: 0; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <img src="${qrImage.src}" />
                    <h1>Escanea para acceder</h1>
                    <script>window.onload = function() { window.print(); window.close(); }</s`+ `cript>
                </body>
                </html>
            `);
            printWindow.document.close();
        };

        window.matchSearch = function (query, owner, client, code, target) {
            if (!query) return true;
            const q = query.toLowerCase();
            return owner.toLowerCase().includes(q) ||
                client.toLowerCase().includes(q) ||
                code.toLowerCase().includes(q) ||
                target.toLowerCase().includes(q);
        };

        window.copyUrl = function (alpineData, code) {
            const fullUrl = window.location.origin + '/qrify/' + code;
            navigator.clipboard.writeText(fullUrl).then(() => {
                // Modificamos la variable reactiva de Alpine directamente
                alpineData.copiedCode = code;
                setTimeout(() => { alpineData.copiedCode = ''; }, 2000);
            });
        };

        window.showQR = function (alpineData, code, name) {
            alpineData.previewCode = code;
            alpineData.previewName = name;
            alpineData.openPreviewModal = true;

            alpineData.$nextTick(() => {
                let container = document.getElementById('qrcode-canvas');
                container.innerHTML = ''; // Limpiar QRs anteriores

                new QRCode(container, {
                    text: window.location.origin + '/qrify/' + code,
                    width: 200,
                    height: 200,
                    colorDark: '#0f172a',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });

            });
        };

        lucide.createIcons();
    </script>
</body>

</html>