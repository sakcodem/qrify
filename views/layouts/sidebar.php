<?php
require_once __DIR__ . '/../../config/App.php';

$menu_items = [];
$current_role = $_SESSION['role'] ?? 'admin';
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);
if ($current_role === 'superadmin') {
    // Menú exclusivo para ti (Dueño del SaaS)
    $menu_items = [
        ['label' => 'Dashboard', 'url' => 'superadmin', 'icon' => 'layout-dashboard'],
        ['label' => 'Clientes', 'url' => 'superadmin/users', 'icon' => 'users'],
        ['label' => 'QRs', 'url' => 'superadmin/qrs', 'icon' => 'qr-code'],
    ];
    $role_label = "SuperAdmin";
} else {
    $menu_items = [
        ['label' => 'Mis QR', 'url' => 'admin', 'icon' => 'qr-code'],
        ['label' => 'Estadísticas', 'url' => 'admin/stats', 'icon' => 'bar-chart-2'],
    ];
    $role_label = "Administrador";
}
$route = $_GET['route'] ?? '';
$route = rtrim($route, '/');

$current_route = $route ?? ''; // Ajusta según tu lógica de enrutamiento
?>

<div x-data="{ 
       sidebarOpen: false, 
        collapsed: $persist(false).as('sidebar_collapsed') 
     }" class="contents">

    <!-- Header superior para Móviles -->
    <header
        class="md:hidden flex items-center justify-between p-4 bg-slate-900 border-b border-slate-800 sticky top-0 z-30 w-full shrink-0">
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

    <!-- Overlay para Móviles -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 md:hidden" x-cloak>
    </div>

    <aside :class="{
            'translate-x-0': sidebarOpen,
            '-translate-x-full md:translate-x-0': !sidebarOpen,
            'md:w-64': !collapsed,
            'md:w-20': collapsed
        }"
        class="fixed top-0 left-0 z-50 w-64 h-full bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800 transition-all duration-300 ease-in-out md:static md:z-auto">

        <div class="p-4 md:p-5 border-b border-slate-800 flex items-center justify-between min-h-[73px]">
            <div class="flex items-center gap-3 overflow-hidden">
                <div x-show="!collapsed"
                    class="bg-indigo-600 text-white p-2 rounded-xl shadow-lg shadow-indigo-500/30 shrink-0">
                    <i data-lucide="qr-code" class="w-6 h-6"></i>
                </div>
                <!-- Ocultar marcas de texto al colapsar -->
                <div x-show="!collapsed" class="transition-opacity duration-200 whitespace-nowrap">
                    <h1 class="font-bold text-white tracking-wide text-lg leading-tight">QRify</h1>
                    <span class="text-xs text-slate-500 font-medium">Panel de Gestión</span>
                </div>
            </div>

            <button @click="collapsed = !collapsed"
                class="hidden md:flex p-1.5 text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-800 rounded-lg transition-colors focus:outline-none"
                :title="collapsed ? 'Expandir menú' : 'Colapsar menú'">
                <i data-lucide="chevron-left" class="w-5 h-5" x-show="!collapsed"></i>
                <i data-lucide="chevron-right" class="w-5 h-5" x-show="collapsed" x-cloak></i>
            </button>

            <button @click="sidebarOpen = false"
                class="text-slate-400 hover:text-white p-1 rounded-lg md:hidden focus:outline-none">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <nav class="flex-1 p-3 space-y-1 overflow-x-hidden">
            <?php foreach ($menu_items as $item): ?>
                <?php
 
                $isActive = ($current_route === $item['url'] || ($item['url'] === 'admin' && $current_route === ''));
                $navClasses = $isActive
                    ? 'bg-indigo-600/10 text-indigo-400 border border-indigo-500/20'
                    : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 border border-transparent';
                ?>

                <a href="<?php echo BASE_URL . $item['url']; ?>"
                    class="flex items-center gap-3 px-3.5 py-3 text-sm font-medium rounded-xl transition-all <?php echo $navClasses; ?>"
                    :class="collapsed ? 'justify-center px-0' : ''"
                    :title="collapsed ? '<?php echo $item['label']; ?>' : ''">
                    <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5 shrink-0"></i>
                    <span x-show="!collapsed" class="whitespace-nowrap"><?php echo $item['label']; ?></span>
                </a>

            <?php endforeach; ?>

        </nav>

        <div class="p-3 border-t border-slate-800/80 bg-slate-950/40">
            <div class="flex items-center justify-between gap-3" :class="collapsed ? 'flex-col md:gap-2' : ''">
                <div class="flex items-center gap-3 overflow-hidden" :class="collapsed ? 'justify-center' : ''">
                    <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400 text-sm shrink-0"
                        :title="collapsed ? '<?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>' : ''">
                        <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div x-show="!collapsed" class="truncate whitespace-nowrap">
                        <p class="text-sm font-semibold text-slate-200 truncate">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>
                        </p>
                        <p class="text-xs text-slate-500"><?php echo $role_label; ?></p>
                    </div>
                </div>

                <a href="<?php echo BASE_URL . 'logout'; ?>"
                    class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors shrink-0"
                    title="Cerrar Sesión">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </aside>

</div>