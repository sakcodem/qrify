<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - qrify</title>
    <!-- Tailwind CDN y Lucide Icons cargados correctamente -->
     <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gradient-to-br from-red-700 via-red-900 to-red-950 flex items-center justify-center h-screen px-4 antialiased">
    
    <div class="max-w-md w-full bg-white/90 backdrop-blur-md border border-gray-200 rounded-2xl shadow-xl p-8 text-center transition-all transform hover:scale-[1.01]">

        <div class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-900 mb-2 select-none font-mono">
            404
        </div>

        <h1 class="text-2xl font-extrabold text-gray-900 mb-3 tracking-tight">
            Página no encontrada
        </h1>
        
        <div class="w-16 h-1 bg-red-500 mx-auto rounded-full mb-4"></div>

        <p class="text-gray-600 text-base mb-8 leading-relaxed">
            Lo sentimos, la ruta a la que estás intentando acceder no existe, ha sido movida o no se encuentra disponible en este momento.
        </p>

        
    </div>
    

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
