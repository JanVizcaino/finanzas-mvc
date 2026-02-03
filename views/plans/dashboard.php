<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
        <div class="flex flex-col gap-1 sm:gap-2">
            <h1 class="text-primary text-2xl font-bold">Mis Planes</h1>
            <p class="text-secondary text-sm sm:text-base">Gestiona y configura tus proyectos y presupuestos</p>
        </div>
        
        <button onclick="openSlideOver()" class="w-full sm:w-auto h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex justify-center items-center gap-2 text-white text-sm font-medium shadow-sm cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            Nuevo Plan
        </button>
    </div>
</div>

<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <?php foreach($plans as $plan): ?>
            <a href="index.php?action=view_plan&id=<?= $plan['id'] ?>" class="block group">
                <div class="bg-background rounded-lg shadow-sm p-5 flex flex-col gap-2 h-full border border-transparent group-hover:border-primary/20 group-hover:shadow-md transition">
                    <div class="w-10 h-10 flex items-center justify-center bg-primary/20 rounded-full text-primary mb-2">
                        <i class="fa-solid fa-dollar-sign"></i>
                    </div>
                    
                    <h3 class="text-secondary text-base font-medium truncate w-full" title="<?= htmlspecialchars($plan['name']) ?>">
                        <?= htmlspecialchars($plan['name']) ?>
                    </h3>
                    
                    <div class="flex items-end gap-2 mt-auto pt-4">
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
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-background rounded-lg border border-dashed border-secondary/30 text-secondary">
                <div class="flex flex-col items-center gap-3">
                    <i class="fa-regular fa-folder-open text-4xl opacity-50"></i>
                    <p>No tienes planes creados todavía.</p>
                    <button onclick="openSlideOver()" class="text-primary font-medium hover:underline">
                        ¡Crea el primero ahora!
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="slideOverBackdrop" class="fixed inset-0 z-50 invisible">
    <div id="slideOverOverlay" onclick="closeSlideOver()" class="absolute inset-0 bg-text/50 opacity-0 transition-opacity duration-300 ease-in-out"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-0 sm:pl-10 pointer-events-none">
        <div id="slideOverPanel" class="pointer-events-auto w-screen sm:max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-background shadow-xl flex flex-col h-full">
            
            <div class="h-16 px-4 sm:px-8 py-3.5 border-b border-secondary/10 flex justify-between items-center flex-shrink-0">
                <h1 class="text-text text-xl font-bold">Nuevo Plan</h1>
                <button onclick="closeSlideOver()" class="w-10 h-10 flex justify-center items-center text-secondary hover:text-text transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="createPlanForm" action="index.php?action=store_plan" method="POST" class="flex-1 px-4 sm:px-8 py-6 flex flex-col gap-5 overflow-y-auto">
                
                <div class="flex flex-col gap-2">
                    <label for="planName" class="text-text text-sm font-medium">Nombre del Plan*</label>
                    <input type="text" id="planName" name="name" placeholder="Ej: Viaje a París" required
                           class="w-full h-10 px-3 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="planDetail" class="text-text text-sm font-medium">Detalles / Descripción</label>
                    <textarea id="planDetail" name="detail" placeholder="Describe el objetivo de este plan..." 
                              class="w-full h-24 px-3 py-2 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm resize-none"></textarea>
                </div>

                <div class="flex flex-col gap-1.5 w-full">
                    <label for="planCurrency" class="text-text text-sm font-medium">Moneda*</label>
                    <div class="relative">
                        <select id="planCurrency" name="currency" class="w-full h-10 px-3 bg-background border border-secondary/30 rounded-md text-sm text-secondary focus:text-text appearance-none focus:outline-none focus:border-primary cursor-pointer">
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">Dólar ($)</option>
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none text-secondary">
                             <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </form>

            <div class="h-auto sm:h-20 px-4 sm:px-8 py-4 sm:py-2.5 border-t border-secondary/10 flex flex-col-reverse sm:flex-row justify-end items-center gap-3 sm:gap-4 flex-shrink-0">
                <button type="button" onclick="closeSlideOver()" class="w-full sm:w-auto h-10 px-4 bg-transparent border border-secondary/30 hover:bg-secondary/5 rounded-md flex items-center justify-center text-secondary text-sm font-medium transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" form="createPlanForm" class="w-full sm:w-auto h-10 px-4 bg-primary hover:opacity-90 rounded-md flex items-center justify-center gap-2 text-white text-sm font-medium transition shadow-sm cursor-pointer">
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

        backdrop.classList.remove('invisible');
        
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeSlideOver() {
        const backdrop = document.getElementById('slideOverBackdrop');
        const overlay = document.getElementById('slideOverOverlay');
        const panel = document.getElementById('slideOverPanel');

        overlay.classList.add('opacity-0');
        panel.classList.add('translate-x-full');

        setTimeout(() => {
            backdrop.classList.add('invisible');
        }, 300);
    }
</script>