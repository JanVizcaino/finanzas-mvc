<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased flex flex-col min-h-screen">
    
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <a href="index.php?action=dashboard" class="group flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-indigo-200 transition-transform group-hover:scale-110">
                        <span class="text-sm">📊</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-800 group-hover:text-indigo-600 transition-colors">
                        FinanzasApp
                    </span>
                </a>

                <div class="flex items-center gap-6">
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <div class="hidden md:flex flex-col items-end text-xs mr-2">
                            <span class="text-slate-400 font-medium">Conectado como</span>
                            <strong class="text-slate-700"><?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?></strong>
                        </div>

                        <div class="hidden md:block h-6 w-px bg-slate-200"></div>

                        <a href="index.php?action=dashboard" 
                           class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
                            Mis Planes
                        </a>

                        <a href="index.php?action=logout" 
                           class="group flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100">
                            <span>Cerrar Sesión</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 opacity-50 group-hover:opacity-100 transition-opacity">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </a>

                    <?php else: ?>
                        
                        <a href="index.php?action=login" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                            Iniciar Sesión
                        </a>
                        
                        <a href="index.php?action=register" 
                           class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            Registrarse
                        </a>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">