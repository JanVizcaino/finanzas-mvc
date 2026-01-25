<div class="flex justify-between items-start mb-6">
    <div class="flex gap-6 items-center">
        <a href="index.php?action=dashboard" class="flex items-center gap-2 text-secondary hover:text-primary transition bg-white px-3 py-1.5 rounded-md shadow-sm border border-transparent hover:border-secondary/20">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span class="text-sm font-medium">Volver</span>
        </a>
        
        <div class="flex flex-col">
            <h1 class="text-primary text-2xl font-bold"><?= htmlspecialchars($plan['name']) ?></h1>
            <p class="text-secondary text-base">
                <?= isset($plan['description']) ? htmlspecialchars($plan['description']) : 'Gestiona los gastos de este proyecto.' ?>
            </p>
        </div>
    </div>
    
    <?php 
        $totalExpenses = 0;
        foreach($expenses as $e) { $totalExpenses += $e['amount']; }
    ?>
    <div class="text-text text-xl font-medium">-<?= number_format($totalExpenses, 2) ?>€</div>
</div>

<div class="border-b border-secondary/30 flex mb-8">
    <a href="#" class="px-6 py-3 border-b-2 border-primary text-text text-sm font-medium">
        Gastos
    </a>
    <a href="index.php?action=plan_settings&id=<?= $plan['id'] ?>" class="px-6 py-3 border-b-2 border-transparent text-secondary hover:text-text text-sm transition-colors">
        Ajustes y Miembros
    </a>
</div>

<div class="flex justify-between items-center mb-6">
    <div class="flex gap-4">
        <div class="relative">
            <select class="h-10 pl-3 pr-8 bg-white border border-secondary/30 rounded-md text-secondary text-sm font-medium appearance-none focus:outline-none focus:border-primary cursor-pointer">
                <option>Usuario: Todos</option>
                <?php foreach($members as $member): ?>
                    <option><?= htmlspecialchars($member['username']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="absolute right-3 top-3 pointer-events-none text-secondary">
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </div>
        </div>

        <div class="relative">
            <select class="h-10 pl-3 pr-8 bg-white border border-secondary/30 rounded-md text-secondary text-sm font-medium appearance-none focus:outline-none focus:border-primary cursor-pointer">
                <option>Categoría: Todas</option>
                <option>Comida</option>
                <option>Transporte</option>
                <option>Ocio</option>
                <option>Hogar</option>
            </select>
            <div class="absolute right-3 top-3 pointer-events-none text-secondary">
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </div>
        </div>
    </div>

    <a href="index.php?action=create_expense_view&plan_id=<?= $plan['id'] ?>" class="h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex items-center gap-2 text-white text-sm font-medium shadow-sm">
        <i class="fa-solid fa-plus text-xs"></i>
        Añadir Gasto
    </a>
</div>

<div class="flex flex-col gap-3">
    
    <?php if (empty($expenses)): ?>
        <div class="py-10 text-center text-secondary bg-secondary/5 rounded-md border border-dashed border-secondary/20">
            No hay gastos registrados todavía.
        </div>
    <?php else: ?>
        <?php foreach($expenses as $expense): ?>
            <div class="bg-white rounded-md shadow-sm px-6 py-3 flex justify-between items-center border border-transparent hover:border-secondary/30 transition group">
                
                <div class="flex items-center gap-4 w-1/2">
                    <div class="flex flex-col">
                        <span class="text-text text-base font-medium"><?= htmlspecialchars($expense['title']) ?></span>
                        
                        <div class="flex gap-2 items-center">
                            <span class="text-secondary text-sm"><?= date('d/m', strtotime($expense['created_at'] ?? 'now')) ?></span>
                            <?php if (!empty($expense['receipt_path'])): ?>
                                <a href="<?= htmlspecialchars($expense['receipt_path']) ?>" target="_blank" class="text-primary text-xs hover:underline">
                                    <i class="fa-solid fa-paperclip"></i> Recibo
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 pl-4 border-l border-secondary/20 hidden sm:flex">
                        <div class="bg-secondary/10 px-3 py-1 rounded-full flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-text rounded-full"></div>
                            <span class="text-text text-xs font-medium"><?= htmlspecialchars($expense['category']) ?></span>
                        </div>
                        <span class="text-secondary text-sm">pagado por <b><?= htmlspecialchars($expense['username']) ?></b></span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="bg-alert/10 px-3 py-1 rounded-full">
                        <span class="text-alert text-xs font-medium">-<?= number_format($expense['amount'], 2) ?>€</span>
                    </div>
                    
                    <?php if ($currentUserRole === 'admin'): ?>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="#" class="w-8 h-8 flex justify-center items-center bg-primary/20 rounded-full text-primary hover:bg-primary hover:text-white transition cursor-pointer">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <a href="index.php?action=delete_expense&id=<?= $expense['id'] ?>&plan_id=<?= $plan['id'] ?>" 
                           class="w-8 h-8 flex justify-center items-center bg-primary/20 rounded-full text-primary hover:bg-primary hover:text-white transition cursor-pointer"
                           onclick="return confirm('¿Borrar este gasto?')">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>