<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QRify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans text-slate-200 bg-slate-950 flex flex-col md:flex-row antialiased"
    x-data="{ selected: [], loadingPdf: false}">

    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Gestión de Códigos QR</h2>
                    <p class="text-sm text-slate-400 mt-1">Crea, edita y monitorea tus enlaces dinámicos en tiempo real.
                    </p>
                </div>
            </div>

            <?php
            $total_qrs = count($qrs);
            $total_scans = array_sum(array_column($qrs, 'total_scans'));
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Total de QRs Activos</p>
                        <p class="text-2xl font-bold text-white mt-1"><?= $total_qrs ?></p>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
                        <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Escaneos Totales</p>
                        <p class="text-2xl font-bold text-white mt-1"><?= number_format($total_scans) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
                <div class="flex items-center gap-2 mb-6">
                    <i data-lucide="<?= $edit_qr ? 'edit-3' : 'plus-circle' ?>" class="w-5 h-5 text-indigo-400"></i>
                    <h3 class="text-lg font-semibold text-white">
                        <?= $edit_qr ? 'Editar Código QR' : 'Agregar Nuevo Código QR' ?>
                    </h3>
                </div>

                <form method="POST" action="admin-save" class="space-y-5">
                    <?php if ($edit_qr): ?>
                        <input type="hidden" name="id" value="<?= $edit_qr['id'] ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Código Identificador</label>
                            <input type="text" name="code" placeholder="Ej: skm-0001 (Dejar vacío para autogenerar)"
                                value="<?= htmlspecialchars($edit_qr['code'] ?? '') ?>"
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Cliente / Referencia</label>
                            <input type="text" name="client_name" placeholder="Ej: Menú Restaurante Sol"
                                value="<?= htmlspecialchars($edit_qr['client_name'] ?? '') ?>"
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">URL de Destino</label>
                        <input type="url" name="target_url" placeholder="https://ejemplo.com/menu"
                            value="<?= htmlspecialchars($edit_qr['target_url'] ?? '') ?>" required
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <?= $edit_qr ? 'Guardar Cambios' : 'Registrar QR' ?>
                        </button>
                        <?php if ($edit_qr): ?>
                            <a href="admin"
                                class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium px-5 py-2.5 rounded-xl text-sm transition-all">
                                Cancelar
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <form method="POST" action="admin-pdf" target="_blank">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                    <div
                        class="p-6 border-b border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-white">Listado de Códigos QR</h3>

                        <button type="button" x-show="selected.length > 0" x-cloak :disabled="loadingPdf"
                            @click="loadingPdf = true; printDirectPdf(selected).finally(() => loadingPdf = false)"
                            class="bg-red-600 hover:bg-red-500 disabled:bg-red-400 text-white font-medium px-4 py-2 rounded-xl text-xs transition-all shadow-lg shadow-red-600/20 flex items-center gap-2 min-h-[36px]">

                            <!-- CONTENEDOR DE ICONOS -->
                            <span class="flex items-center justify-center w-4 h-4">
                                <!-- Icono normal (Se oculta al cargar) -->
                                <i data-lucide="file-text" class="w-4 h-4" x-show="!loadingPdf"></i>

                                <!-- Spinner de carga (Se muestra al cargar) -->
                                <svg class="animate-spin w-4 h-4 text-white" x-show="loadingPdf" xmlns="http://w3.org"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>

                            <!-- TEXTO DEL BOTÓN -->
                            <span x-show="!loadingPdf">Generar PDF (<span x-text="selected.length"></span>)</span>
                            <span x-show="loadingPdf">Generando...</span>
                        </button>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead
                                class="bg-slate-950/60 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                                <tr>
                                    <th class="px-4 py-4 text-center w-10">
                                        <input type="checkbox"
                                            @change="selected = $event.target.checked ? [<?= implode(',', array_column($qrs, 'id')) ?>] : []"
                                            class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                                    </th>
                                    <th class="px-6 py-4">Código</th>
                                    <th class="px-6 py-4">Cliente</th>
                                    <th class="px-6 py-4">Destino</th>
                                    <th class="px-6 py-4 text-center">Escaneos</th>
                                    <th class="px-6 py-4 text-center">Vista QR</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                <?php foreach ($qrs as $qr): ?>
                                    <tr class="hover:bg-slate-800/30 transition-colors">
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="qr_ids[]" value="<?= $qr['id'] ?>"
                                                x-model.number="selected"
                                                class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-indigo-400">
                                            <?= htmlspecialchars($qr['code']) ?>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-200">
                                            <?= htmlspecialchars($qr['client_name'] ?: '—') ?>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">
                                            <a href="<?= htmlspecialchars($qr['target_url']) ?>" target="_blank"
                                                class="text-slate-400 hover:text-indigo-400 flex items-center gap-1 transition-colors">
                                                <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0"></i>
                                                <span class="truncate"><?= htmlspecialchars($qr['target_url']) ?></span>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-white">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <?= $qr['total_scans'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-block bg-white p-2 rounded-xl shadow-sm">
                                                <div id="qr-<?= $qr['id'] ?>"></div>
                                            </div>
                                            <script>
                                                new QRCode(document.getElementById("qr-<?= $qr['id'] ?>"), {
                                                    text: window.location.origin + "/<?= $qr['code'] ?>",
                                                    width: 56,
                                                    height: 56
                                                });
                                            </script>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="admin?edit=<?= $qr['id'] ?>"
                                                class="inline-flex items-center gap-1 text-slate-300 hover:text-indigo-400 bg-slate-800 hover:bg-slate-700 px-3 py-1.5 rounded-lg transition-all text-xs font-medium">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                Editar
                                            </a>
                                            <a href="admin-delete?delete=<?= $qr['id'] ?>"
                                                onclick="return confirm('¿Eliminar este código QR?')"
                                                class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 bg-red-500/10 hover:bg-red-500/20 px-3 py-1.5 rounded-lg transition-all text-xs font-medium border border-red-500/20">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($qrs)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                            No hay códigos QR registrados todavía.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>

    <script>
        function printDirectPdf(selectedIds) {
            if (selectedIds.length === 0) return Promise.resolve();

            let nombreArchivo = 'qrs-seleccionados.pdf';    

            return fetch('admin-pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ qr_ids: selectedIds })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Error al generar el PDF");

                    if (response.headers.get('X-Filename')) {
                        nombreArchivo = response.headers.get('X-Filename');
                    }

                    return response.blob();
                })
                .then(blob => {
                    const pdfUrl = URL.createObjectURL(blob);

                    const downloadLink = document.createElement('a');
                    downloadLink.style.display = 'none';
                    downloadLink.href = pdfUrl;
                    downloadLink.download = nombreArchivo;

                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    setTimeout(() => URL.revokeObjectURL(pdfUrl), 100);
                })
                .catch(error => {
                    alert("Ocurrió un error al generar el PDF para descarga.");
                    console.error(error);
                });
        }

    </script>
</body>

</html>