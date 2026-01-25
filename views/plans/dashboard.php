<div class="flex justify-between items-center w-full max-w-[950px] mx-auto mb-8">
    <div class="flex flex-col gap-2">
        <h1 class="text-primary text-2xl font-bold">Mis Planes</h1>
        <p class="text-secondary text-base">Gestiona y configura tus proyectos y presupuestos</p>
    </div>
    
    <button onclick="openSlideOver()" class="h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex items-center gap-2 text-white text-sm font-medium shadow-sm cursor-pointer">
        <i class="fa-solid fa-plus text-xs"></i>
        Nuevo Plan
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-[950px] mx-auto">
    <?php foreach($plans as $plan): ?>
        <a href="index.php?action=view_plan&id=<?= $plan['id'] ?>">
            <div class="bg-white rounded-lg shadow-sm p-5 flex flex-col gap-2 hover:shadow-md transition cursor-pointer border border-transparent hover:border-primary/10">
                <div class="w-10 h-10 flex items-center justify-center bg-primary/25 rounded-full text-primary mb-2">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <h3 class="text-secondary text-base font-medium truncate">
                    <?= htmlspecialchars($plan['name']) ?>
                </h3>
                <div class="flex items-end gap-2 mt-auto">
                    <span class="text-text text-3xl font-medium">
                        <?= isset($plan['total']) ? htmlspecialchars($plan['total']) : '0' ?>€
                    </span>
                    <span class="text-secondary text-xs font-thin mb-1">
                        Creado: <?= date('d/m/Y', strtotime($plan['created_at'])) ?>
                    </span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>

    <?php if (empty($plans)): ?>
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-10 text-secondary">
            <p>No tienes planes creados todavía. ¡Crea el primero arriba!</p>
        </div>
    <?php endif; ?>
</div>

<div id="slideOverBackdrop" class="fixed inset-0 z-50 invisible">
    
    <div id="slideOverOverlay" onclick="closeSlideOver()" class="absolute inset-0 bg-gray-900/50 opacity-0 transition-opacity duration-300 ease-in-out"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 pointer-events-none">
        
        <div id="slideOverPanel" class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-xl flex flex-col h-full">
            
            <div class="h-16 px-8 py-3.5 border-b border-secondary/20 flex justify-between items-center bg-white flex-shrink-0">
                <h1 class="text-text text-xl font-bold">Nuevo Plan</h1>
                <button onclick="closeSlideOver()" class="w-10 h-10 flex justify-center items-center text-secondary hover:text-text transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="createPlanForm" action="index.php?action=store_plan" method="POST" class="flex-1 px-8 py-6 flex flex-col gap-5 overflow-y-auto">
                
                <div class="flex flex-col gap-2">
                    <label for="planName" class="text-text text-sm font-medium">Nombre del Plan*</label>
                    <input type="text" id="planName" name="name" placeholder="Ej: Viaje a París" required
                           class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="planDetail" class="text-text text-sm font-medium">Detalles / Descripción</label>
                    <textarea id="planDetail" name="description" placeholder="Describe el objetivo de este plan..." 
                              class="w-full h-24 px-3 py-2 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm resize-none"></textarea>
                </div>

                <div class="flex flex-col gap-1.5 w-full">
                    <label for="planCurrency" class="text-text text-sm font-medium">Moneda*</label>
                    <div class="relative">
                        <select id="planCurrency" name="currency" class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-secondary focus:text-text appearance-none focus:outline-none focus:border-primary cursor-pointer">
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">Dólar ($)</option>
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none text-secondary">
                             <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

            </form>

            <div class="h-20 px-4 py-2.5 border-t border-secondary/20 flex justify-end items-center gap-4 bg-white flex-shrink-0">
                <button type="button" onclick="closeSlideOver()" class="h-10 px-4 bg-white border border-secondary/30 hover:bg-secondary/5 rounded-md flex items-center justify-center text-secondary text-sm font-medium transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" form="createPlanForm" class="h-10 px-4 bg-primary hover:opacity-90 rounded-md flex items-center justify-center gap-2 text-white text-sm font-medium transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar Plan
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function openSlideOver() {
        const backdrop = document.getElementById('slideOverBackdrop');
        const overlay = document.getElementById('slideOverOverlay');
        const panel = document.getElementById('slideOverPanel');

        // 1. Hacer visible el contenedor padre
        backdrop.classList.remove('invisible');
        
        // 2. Pequeño timeout para permitir que el navegador renderice antes de animar
        setTimeout(() => {
            overlay.classList.remove('opacity-0'); // Fundido negro
            panel.classList.remove('translate-x-full'); // Deslizar panel
        }, 10);
    }

    function closeSlideOver() {
        const backdrop = document.getElementById('slideOverBackdrop');
        const overlay = document.getElementById('slideOverOverlay');
        const panel = document.getElementById('slideOverPanel');

        // 1. Iniciar animación de salida
        overlay.classList.add('opacity-0');
        panel.classList.add('translate-x-full');

        // 2. Esperar a que termine la animación (300ms) para ocultar el padre
        setTimeout(() => {
            backdrop.classList.add('invisible');
        }, 300);
    }
</script>