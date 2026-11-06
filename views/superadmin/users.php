<?php
require_once __DIR__ . '/../../config/App.php';
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - QRify</title>
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

<body x-data="usersManager" class="h-full font-sans text-slate-200 bg-slate-950 flex flex-col md:flex-row antialiased">

    <?php include BASE_PATH . '/views/layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Gestión de Clientes</h2>
                    <p class="text-sm text-slate-400 mt-1">Administra las cuentas empresariales y sus accesos.</p>
                </div>

                <button @click="openCreateModal = true"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-lg shadow-indigo-600/20 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Crear Admin
                </button>
            </div>

            <?php include BASE_PATH . '/views/layouts/alerts.php'; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Total de Usuarios</p>
                        <p class="text-2xl font-bold text-white mt-1"><?= $total_users ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-800 bg-slate-950/40 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <th class="p-4">Usuario</th>
                                <th class="p-4">Correo Electrónico</th>
                                <th class="p-4">Fecha de Creación</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4"><?= htmlspecialchars($user['username']) ?></td>
                                    <td class="p-4"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="p-4"><?= htmlspecialchars($user['created_at']) ?></td>

                                    <td class="p-4 text-right space-x-2">
                                        <button @click="openEdit(<?= $user['id'] ?? 1 ?>)"
                                            class="text-slate-400 hover:text-white p-1.5 hover:bg-slate-800 rounded-lg transition-colors"
                                            title="Editar">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button
                                            class="text-slate-400 hover:text-red-400 p-1.5 hover:bg-red-500/10 rounded-lg transition-colors"
                                            title="Eliminar">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                        No hay usuarios registrados todavía.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <div x-show="openCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>

        <div x-show="openCreateModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="openCreateModal = false"
            class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm">
        </div>

        <div x-show="openCreateModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-6 overflow-hidden">

            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div class="flex items-center gap-3 text-white">
                    <div class="bg-indigo-600/10 text-indigo-400 p-2 rounded-lg">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold">Registrar Nuevo Cliente</h3>
                </div>
                <button @click="openCreateModal = false"
                    class="text-slate-400 hover:text-white transition-colors focus:outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="<?= BASE_URL . 'superadmin/save-user' ?>" method="POST" class="space-y-4">

                <div>
                    <label for="username"
                        class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nombre de
                        Usuario</label>
                    <input type="text" id="username" name="username" required placeholder="ej: cafecentral_admin"
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm">
                </div>

                <div>
                    <label for="email"
                        class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Correo
                        Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="ej: admin@cafe.com"
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm">
                </div>

                <div>
                    <label for="password"
                        class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Contraseña
                        Temporal</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="openCreateModal = false"
                        class="px-4 py-2.5 text-sm font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-all focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-lg shadow-indigo-600/20 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div x-show="openEditModal" @click="openEditModal = false"
            class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" x-transition></div>

        <div x-show="openEditModal"
            class="relative bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-6 overflow-hidden"
            x-transition>

            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div class="flex items-center gap-3 text-white">
                    <div class="bg-amber-600/10 text-amber-400 p-2 rounded-lg">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold">Editar Cliente</h3>
                </div>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-white focus:outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Formulario controlado por Alpine (prevent evita que se recargue la página) -->
            <form action="<?= BASE_URL . 'superadmin/update-user' ?>" method="POST" class="space-y-4">
                <input type="hidden" name="id" x-model="editUserData.id">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nombre de
                        Usuario</label>
                    <input type="text" name="username" x-model="editUserData.username" required
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Correo
                        Electrónico</label>
                    <input type="email" name="email" x-model="editUserData.email" required
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nueva
                        Contraseña (Dejar en blanco para mantener)</label>
                    <input type="password" name="password" x-model="editUserData.password" placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-amber-500">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="openEditModal = false"
                        class="px-4 py-2.5 text-sm font-semibold text-slate-400 hover:text-white bg-slate-800 rounded-xl">Cancelar</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-500 rounded-xl shadow-lg shadow-amber-600/20">Actualizar
                        Datos</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('usersManager', () => ({
                // Variables de estado (Data)
                openCreateModal: false,
                openEditModal: false,
                editUserData: { id: '', username: '', email: '', password: '' },

                // Función para buscar los datos del usuario por AJAX
                openEdit(userId) {
                    fetch(`/qrify/superadmin/get-user?id=${userId}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Usuario no encontrado');
                            return res.json();
                        })
                        .then(data => {
                            // Inyectamos los datos recibidos en el estado reactivo
                            this.editUserData = { id: data.id, username: data.username, email: data.email, password: '' };
                            this.openEditModal = true;
                        })
                        .catch(err => alert(err.message));
                },
            }));
        });

        // Inicializar tus iconos de Lucide como siempre
        lucide.createIcons();
    </script>
</body>

</html>