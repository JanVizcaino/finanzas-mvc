<div class="flex flex-col sm:flex-row justify-between items-end sm:items-center gap-4 mb-8 pb-6 border-b border-slate-200">
    <div class="w-full sm:w-auto">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
            Mis Planes Financieros
        </h1>
        <p class="text-slate-500 text-sm mt-1">Gestiona tus proyectos y presupuestos</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto items-stretch sm:items-center">
        
        <a href="index.php?action=admin_panel" 
           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-semibold rounded-lg hover:bg-slate-50 hover:border-indigo-300 hover:text-indigo-600 transition-all shadow-sm group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-hover:scale-110 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>Admin</span>
        </a>

        <form action="index.php?action=store_plan" method="POST" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="name" placeholder="Nuevo Plan (ej: Viaje)" 
                   class="flex-1 sm:w-64 bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm transition-all" required>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-bold text-sm transition-all shadow-sm hover:shadow-md flex items-center gap-2 whitespace-nowrap">
                <span class="text-lg leading-none">+</span> <span class="hidden sm:inline">Crear</span>
            </button>
        </form>
        
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($plans as $plan): ?>
        <a href="index.php?action=view_plan&id=<?= $plan['id'] ?>" class="group block h-full">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-full flex flex-col justify-between hover:shadow-md hover:border-indigo-300 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                        <?= htmlspecialchars($plan['name']) ?>
                    </h2>
                    <p class="text-slate-400 text-xs font-medium mt-1">
                        Creado el <?= $plan['created_at'] ?>
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-50 relative z-10 flex items-center text-indigo-600 font-semibold text-sm group-hover:text-indigo-700">
                    Ver Gastos 
                    <span class="ml-2 transform group-hover:translate-x-1 transition-transform duration-300">→</span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>