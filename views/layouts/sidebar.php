<div x-data="{ sidebarOpen: false }" class="contents">

    <header class="md:hidden flex items-center justify-between p-4 bg-slate-900 border-b border-slate-800 sticky top-0 z-30 w-full shrink-0">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 text-white p-1.5 rounded-lg shadow-md shadow-indigo-500/20">
                <i data-lucide="qr-code" class="w-5 h-5"></i>
            </div>
            <span class="font-bold text-white text-base tracking-wide">QRify</span>
        </div>
        <button @click="sidebarOpen = true"
            class="p-2 text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors focus:outline-none">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </header>

    <div x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 md:hidden" 
        x-cloak>
    </div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed top-0 left-0 z-50 w-64 h-full bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800 transition-transform duration-300 ease-in-out md:static md:z-auto">

        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-lg shadow-indigo-500/30">
                    <i data-lucide="qr-code" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white tracking-wide text-lg leading-tight">QRify</h1>
                    <span class="text-xs text-slate-500 font-medium">Panel de Gestión</span>
                </div>
            </div>

            <!-- Botón X para cierre en Móvil -->
            <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg md:hidden focus:outline-none">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="admin"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Códigos QR
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
            <div class="flex items-center justify-between gap-3 px-3 py-2">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400 text-sm shrink-0">
                        <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-semibold text-slate-200 truncate">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>
                        </p>
                        <p class="text-xs text-slate-500">Administrador</p>
                    </div>
                </div>
                <a href="logout"
                    class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                    title="Cerrar Sesión">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </aside>

</div>