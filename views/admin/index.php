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

<body class="h-full font-sans text-slate-200 bg-slate-950 flex flex-col md:flex-row antialiased" x-data="{ selected: [], loadingPng: false, loadingPdf: false, openFormModal: false,
        modalTitle: 'Agregar Nuevo Código QR',
        modalIcon: 'plus-circle',
        formId: '',
        formCode: '',
        formClientName: '',
        formTargetUrl: '',
        formColorForeground: '#000000',
        formColorBackground: '#FFFFFF',
        formColorText: '#000000',
        formSubmitText: 'Registrar QR',
        
        openQRForm(qr = null) {
            if (qr) {
                  this.modalTitle = 'Editar Código QR';
                  this.modalIcon = 'edit-3';
                  this.formId = qr.id;
                  this.formCode = qr.code;
                  this.formClientName = qr.client_name;
                  this.formTargetUrl = qr.target_url;
                  this.formColorForeground = qr.color_foreground || '#000000';
                  this.formColorBackground = qr.color_background || '#FFFFFF';
                  this.formColorText = qr.color_text || '#000000';
                  this.formSubmitText = 'Guardar Cambios';
              } else {
                  this.modalTitle = 'Agregar Nuevo Código QR';
                  this.modalIcon = 'plus-circle';
                  this.formId = '';
                  this.formCode = '';
                  this.formClientName = '';
                  this.formTargetUrl = '';
                  this.formColorForeground = '#000000';
                  this.formColorBackground = '#FFFFFF';
                  this.formColorText = '#000000';
                  this.formSubmitText = 'Registrar QR';
              }
              this.openFormModal = true;
        }

        }">

    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <i data-lucide="qr-code" class="w-7 h-7 text-indigo-600"></i> Gestión de Códigos QR
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">Crea, edita y monitorea tus enlaces dinámicos en tiempo real.
                    </p>
                </div>

                <button @click="openQRForm()" type="button"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Nuevo QR
                </button>
            </div>
            <?php
            $total_qrs = count($qrs);
            $total_scans = array_sum(array_column($qrs, 'total_scans'));
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                    class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Total de QRs Activos</p>
                            <p class="text-xl font-bold text-white"><?= $total_qrs ?></p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
                            <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Escaneos Totales</p>
                            <p class="text-xl font-bold text-white"><?= number_format($total_scans) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php include BASE_PATH . '/views/layouts/alerts.php'; ?>

            <div class="space-y-4">

                <div
                    class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-slate-900/60 border border-slate-800/80 p-4 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <input type="checkbox"
                            @change="selected = $event.target.checked ? [<?= implode(',', array_column($qrs, 'id')) ?>] : []"
                            class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                        <span class="text-sm font-semibold text-white">Seleccionar todos</span>
                    </div>

                    <div class="flex flex-row items-center gap-2" x-show="selected.length > 0" x-transition x-cloak>
                        <span class="text-xs text-slate-400 mr-2"><span
                                x-text="selected.length"></span>seleccionados:</span>

                        <button type="button" :disabled="loadingPng || loadingPdf"
                            @click="loadingPng = true; processExport('admin-png', selected).finally(() => loadingPng = false)"
                            class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                            <i data-lucide="file-image" class="w-3.5 h-3.5 text-emerald-400" x-show="!loadingPng"></i>
                            <svg class="animate-spin w-3.5 h-3.5 text-slate-300" x-show="loadingPng" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>PNG</span>
                        </button>

                        <button type="button" :disabled="loadingPdf || loadingPng"
                            @click="loadingPdf = true; processExport('admin-pdf', selected).finally(() => loadingPdf = false)"
                            class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-red-400" x-show="!loadingPdf"></i>
                            <svg class="animate-spin w-3.5 h-3.5 text-slate-300" x-show="loadingPdf" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>PDF</span>
                        </button>
                    </div>
                </div>

                <?php if (empty($qrs)): ?>
                    <div
                        class="p-12 bg-slate-900 border border-slate-800 rounded-2xl text-center text-slate-500 shadow-xl flex flex-col items-center justify-center gap-3">
                        <div class="p-4 bg-slate-950 rounded-full border border-slate-800 text-slate-600">
                            <i data-lucide="folder-open" class="w-8 h-8"></i>
                        </div>
                        <span class="font-medium text-slate-400">No hay códigos QR registrados todavía.</span>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <?php foreach ($qrs as $qr): ?>
                            <div
                                class="relative bg-slate-900/60 border border-slate-800/80 hover:border-slate-700 rounded-2xl p-4 shadow-lg transition-all flex flex-col justify-between group">

                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <input type="checkbox" name="qr_ids[]" value="<?= $qr['id'] ?>"
                                            x-model.number="selected"
                                            class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500 w-4 h-4 shrink-0 cursor-pointer">
                                        <div class="min-w-0">
                                            <h4 class="font-semibold text-white text-sm truncate"
                                                title="<?= htmlspecialchars($qr['client_name'] ?: 'Sin nombre') ?>">
                                                <?= htmlspecialchars($qr['client_name'] ?: '—') ?>
                                            </h4>
                                            <p class="font-mono text-indigo-400 text-[11px] mt-0.5 tracking-wider">
                                                <?= htmlspecialchars($qr['code']) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shrink-0">
                                        <i data-lucide="scan" class="w-3 h-3 mr-1 opacity-80"></i>
                                        <?= number_format($qr['total_scans'] ?? 0) ?>
                                    </span>
                                </div>

                                <div class="my-4 flex items-center gap-3 bg-slate-950/50 rounded-xl border border-slate-800/60">
                                    <div class="p-1 rounded-lg shadow-sm shrink-0 flex items-center justify-center"
                                        :style="{ backgroundColor: '<?= htmlspecialchars($qr['color_background'] ?? '#FFFFFF') ?>' }">
                                        <div id="qr-<?= $qr['id'] ?>"></div>
                                    </div>
                                    <script>
                                        new QRCode(document.getElementById("qr-<?= $qr['id'] ?>"), {
                                            text: window.location.origin + "/<?= $qr['code'] ?>",
                                            width: 48,
                                            height: 48,
                                            colorDark: '<?= htmlspecialchars($qr['color_foreground'] ?? '#000000') ?>',
                                            colorLight: '<?= htmlspecialchars($qr['color_background'] ?? '#FFFFFF') ?>',
                                            correctLevel: QRCode.CorrectLevel.M
                                        });
                                    </script>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider">URL Destino
                                        </p>
                                        <a href="<?= htmlspecialchars($qr['target_url'] ?? '#') ?>" target="_blank"
                                            class="text-xs text-slate-400 hover:text-indigo-400 flex items-center gap-1 transition-colors mt-0.5"
                                            title="<?= htmlspecialchars($qr['target_url'] ?? '') ?>">
                                            <span class="truncate block"><?= htmlspecialchars($qr['target_url'] ?? '') ?></span>
                                            <i data-lucide="external-link" class="w-3 h-3 shrink-0 opacity-60"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800/60">
                                    <button type="button" @click="openQRForm({ 
                                        id: '<?= $qr['id'] ?>',
                                        code: '<?= htmlspecialchars(addslashes($qr['code'] ?? '')) ?>',
                                        client_name: '<?= htmlspecialchars(addslashes($qr['client_name'] ?? '')) ?>', 
                                        target_url: '<?= htmlspecialchars(addslashes($qr['target_url'] ?? '')) ?>',
                                        color_foreground: '<?= htmlspecialchars(addslashes($qr['color_foreground'] ?? '#000000')) ?>',
                                        color_background: '<?= htmlspecialchars(addslashes($qr['color_background'] ?? '#FFFFFF')) ?>',
                                        color_text: '<?= htmlspecialchars(addslashes($qr['color_text'] ?? '#000000')) ?>'
                                    })"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700 px-3 py-1.5 rounded-xl transition-all text-xs font-medium">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5 text-slate-400"></i>
                                        Editar
                                    </button>

                                    <a href="admin-delete?delete=<?= $qr['id'] ?>"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este código QR?')"
                                        class="inline-flex items-center justify-center gap-1.5 text-red-400 hover:text-red-300 bg-red-500/10 hover:bg-red-500/20 px-3 py-1.5 rounded-xl transition-all text-xs font-medium border border-red-500/10">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        Eliminar
                                    </a>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div x-show="openFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak x-init="$watch('openFormModal', value => { 
        if(value) { 
                $nextTick(() => { window.updateFormPreview($data); }); 
            } 
        })">

        <div x-show="openFormModal" @click="openFormModal = false"
            class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" x-transition></div>

        <div x-show="openFormModal"
            class="relative bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-3xl max-h-[calc(100vh-2rem)] shadow-2xl overflow-hidden flex flex-col"
            x-transition x-effect="if(openFormModal) { $nextTick(() => { window.updateFormPreview($data); })); }">

            <div class="flex items-center justify-between border-b border-slate-800 p-4 shrink-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-white" x-text="modalTitle"></h3>
                    <input type="hidden" name="code" x-model="formCode">
                </div>
                <button @click="openFormModal = false" class="text-slate-400 hover:text-white focus:outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-800 overflow-y-auto flex-1 global-scrollbar">

                <form method="POST" action="admin-save" class="sm:p-6 p-5 space-y-5 flex-1">
                    <template x-if="formId">
                        <input type="hidden" name="id" :value="formId">
                    </template>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Cliente / Referencia</label>
                        <input type="text" name="client_name" x-model="formClientName"
                            placeholder="Ej: Menú Restaurante Sol"
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">URL de Destino</label>
                        <input type="url" name="target_url" x-model="formTargetUrl"
                            placeholder="https://ejemplo.com/menu" required
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="space-y-3 bg-slate-950/40 p-4 rounded-xl border border-slate-800/60">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Paleta de Colores
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">Puntos QR</label>
                                <div
                                    class="flex items-center gap-1.5 bg-slate-950 border border-slate-800 rounded-xl p-1.5">
                                    <input type="color" name="color_foreground" x-model="formColorForeground"
                                        @input.debounce.200ms="window.updateFormPreview($data)"
                                        class="w-6 h-6 rounded-md border-0 bg-transparent cursor-pointer shrink-0">
                                    <span class="text-[9px] font-mono text-slate-500 truncate"
                                        x-text="formColorForeground"></span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">Fondo QR</label>
                                <div
                                    class="flex items-center gap-1.5 bg-slate-950 border border-slate-800 rounded-xl p-1.5">
                                    <input type="color" name="color_background" x-model="formColorBackground"
                                        @input.debounce.200ms="window.updateFormPreview($data)"
                                        class="w-6 h-6 rounded-md border-0 bg-transparent cursor-pointer shrink-0">
                                    <span class="text-[9px] font-mono text-slate-500 truncate"
                                        x-text="formColorBackground"></span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">Texto Leyenda</label>
                                <div
                                    class="flex items-center gap-1.5 bg-slate-950 border border-slate-800 rounded-xl p-1.5">
                                    <input type="color" name="color_text" x-model="formColorText"
                                        class="w-6 h-6 rounded-md border-0 bg-transparent cursor-pointer shrink-0">
                                    <span class="text-[9px] font-mono text-slate-500 truncate"
                                        x-text="formColorText"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                        <button type="button" @click="openFormModal = false"
                            class="px-3 py-2.5 text-sm font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-3 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span x-text="formSubmitText"></span>
                        </button>
                    </div>
                </form>

                <div class="sm:p-6 p-5 bg-slate-950/20 flex flex-col items-center justify-center text-center space-y-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Previsualización del Diseño
                    </p>

                    <div class="p-1 shadow-xl transition-all flex flex-col items-center justify-center"
                        :style="`background-color: ${formColorBackground}; border-color: ${formColorBackground}`">
                        <div class="shadow-inner flex items-center justify-center">
                            <div id="modal-qrcode-preview"></div>
                        </div>
                        <p class="text-xs font-bold mt-1 tracking-wide font-sans" :style="`color: ${formColorText}`"
                            x-text="formId ?  (formCode || '...') : 'ESCANEAME'"></p>
                    </div>

                    <p class="text-[11px] text-slate-500 max-w-[240px]">Los colores de los puntos, el fondo y la leyenda
                        inferior se reflejarán tanto en los archivos PNG como en los PDFs exportados.</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

    <script>
        function processExport(route, selectedIds) {
            if (selectedIds.length === 0) return Promise.resolve();

            return fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ qr_ids: selectedIds })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Error al generar el archivo");

                    let filename = route.includes('pdf') ? 'qrs-impresion.pdf' : 'qr-codes.zip';
                    const disposition = response.headers.get('Content-Disposition');

                    if (disposition && disposition.includes('attachment')) {
                        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    return response.blob().then(blob => ({ blob, filename }));
                })
                .then(({ blob, contentType, filename }) => {
                    const url = URL.createObjectURL(blob);
                    const tempLink = document.createElement('a');
                    tempLink.href = url;
                    tempLink.download = filename;
                    document.body.appendChild(tempLink);
                    tempLink.click();
                    document.body.removeChild(tempLink);
                    setTimeout(() => URL.revokeObjectURL(url), 100);
                })
                .catch(error => {
                    alert("Ocurrió un error al generar el archivo para descarga.");
                    console.error(error);
                });
        }

        window.updateFormPreview = function (alpineData) {
            let previewContainer = document.getElementById('modal-qrcode-preview');
            if (!previewContainer) return;

            previewContainer.innerHTML = ''; // Limpiar previsualizaciones previas

            new QRCode(previewContainer, {
                text: alpineData.formTargetUrl || window.location.origin,
                width: 80,
                height: 80,
                colorDark: alpineData.formColorForeground || '#FFFFFF',
                colorLight: alpineData.formColorBackground || '#000000',
                correctLevel: QRCode.CorrectLevel.M
            });
        };

    </script>
</body>

</html>