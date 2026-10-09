<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Admin - QRify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full font-sans text-slate-200 bg-slate-950 flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md space-y-8">
        
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-3 bg-indigo-600/10 text-indigo-400 rounded-2xl border border-indigo-500/20 mb-2">
                <i data-lucide="qr-code" class="w-8 h-8"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">QRify</h1>
            <p class="text-sm text-slate-400">Ingresa tus credenciales para acceder al panel admin</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl space-y-6">
            
            <?php if (!empty($error)): ?>
                <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login" class="space-y-5">
    
                <div>
                    <label for="username" class="block text-xs font-medium text-slate-300 mb-2">Usuario</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="username" name="username" placeholder="Tu usuario" required
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-300 mb-2">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 group">
                    <span>Entrar</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-0.5"></i>
                </button>

            </form>
        </div>

        <p class="text-center text-xs text-slate-500">
            &copy; <?= date('Y') ?> QRify. Todos los derechos reservados.
        </p>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>