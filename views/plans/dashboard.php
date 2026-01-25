<div class="flex justify-between items-center w-full max-w-[950px] mx-auto mb-8">
    <div class="flex flex-col gap-2">
        <h1 class="text-primary text-2xl font-bold">Mis Planes</h1>
        <p class="text-secondary text-base">Gestiona y configura tus proyectos y presupuestos</p>
    </div>
    
    <a href="index.php?action=add_plan" class="h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex items-center gap-2 text-white text-sm font-medium shadow-sm cursor-pointer">
        <i class="fa-solid fa-plus text-xs"></i>
        Nuevo Plan
    </a>
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