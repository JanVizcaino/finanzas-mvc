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

    <button onclick="openExpenseSlideOver()" class="h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex items-center gap-2 text-white text-sm font-medium shadow-sm cursor-pointer">
        <i class="fa-solid fa-plus text-xs"></i>
        Añadir Gasto
    </button>
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

<div id="expenseSlideOverBackdrop" class="fixed inset-0 z-50 invisible">
    
    <div id="expenseSlideOverOverlay" onclick="closeExpenseSlideOver()" class="absolute inset-0 bg-gray-900/50 opacity-0 transition-opacity duration-300 ease-in-out"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 pointer-events-none">
        
        <div id="expenseSlideOverPanel" class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-xl flex flex-col h-full">
            
            <div class="h-16 px-8 py-3.5 border-b border-secondary/20 flex justify-between items-center bg-white flex-shrink-0">
                <h1 class="text-text text-xl font-bold">Nuevo Gasto</h1>
                <button onclick="closeExpenseSlideOver()" class="w-10 h-10 flex justify-center items-center text-secondary hover:text-text transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="createExpenseForm" action="index.php?action=store_expense" method="POST" enctype="multipart/form-data" class="flex-1 px-8 py-6 flex flex-col gap-5 overflow-y-auto">
                
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Concepto*</label>
                    <input type="text" name="title" placeholder="Ej: Cena en restaurante" required
                           class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Detalles</label>
                    <textarea placeholder="Detalles adicionales..." 
                              class="w-full h-24 px-3 py-2 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm resize-none"></textarea>
                </div>

                <div class="flex justify-between items-end gap-4">
                    
                    <div class="flex-1 flex flex-col gap-1.5">
                        <label class="text-text text-sm font-medium">Cantidad*</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" placeholder="0.00" required
                                   class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
                            <div class="absolute right-3 top-2.5 pointer-events-none text-secondary text-xs font-bold">€</div>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col gap-1.5">
                        <label class="text-text text-sm font-medium">Categoría*</label>
                        <div class="relative">
                            <select name="category" class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-secondary focus:text-text appearance-none focus:outline-none focus:border-primary cursor-pointer">
                                <option value="Comida">Comida</option>
                                <option value="Transporte">Transporte</option>
                                <option value="Ocio">Ocio</option>
                                <option value="Hogar">Hogar</option>
                                <option value="Otros">Otros</option>
                            </select>
                            <div class="absolute right-3 top-3 pointer-events-none text-secondary">
                                 <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-2">
                     <label class="text-text text-sm font-medium">Adjuntar Recibo</label>
                     <input type="file" name="receipt" id="receiptInput" class="hidden" onchange="updateFileName()">
                     
                     <button type="button" onclick="document.getElementById('receiptInput').click()" class="w-full h-10 px-4 bg-white border border-secondary/30 hover:bg-secondary/5 transition rounded-md flex justify-center items-center gap-2 text-primary text-sm font-medium shadow-sm cursor-pointer border-dashed">
                        <i class="fa-solid fa-paperclip"></i>
                        <span id="fileNameDisplay">Seleccionar archivo...</span>
                    </button>
                </div>

            </form>

            <div class="h-20 px-4 py-2.5 border-t border-secondary/20 flex justify-end items-center gap-4 bg-white flex-shrink-0">
                <button type="button" onclick="closeExpenseSlideOver()" class="h-10 px-4 bg-white border border-secondary/30 hover:bg-secondary/5 rounded-md flex items-center justify-center text-secondary text-sm font-medium transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" form="createExpenseForm" class="h-10 px-4 bg-primary hover:opacity-90 rounded-md flex items-center justify-center gap-2 text-white text-sm font-medium transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar Gasto
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function openExpenseSlideOver() {
        const backdrop = document.getElementById('expenseSlideOverBackdrop');
        const overlay = document.getElementById('expenseSlideOverOverlay');
        const panel = document.getElementById('expenseSlideOverPanel');

        backdrop.classList.remove('invisible');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeExpenseSlideOver() {
        const backdrop = document.getElementById('expenseSlideOverBackdrop');
        const overlay = document.getElementById('expenseSlideOverOverlay');
        const panel = document.getElementById('expenseSlideOverPanel');

        overlay.classList.add('opacity-0');
        panel.classList.add('translate-x-full');

        setTimeout(() => {
            backdrop.classList.add('invisible');
        }, 300);
    }

    // Script para mostrar el nombre del archivo seleccionado
    function updateFileName() {
        const input = document.getElementById('receiptInput');
        const display = document.getElementById('fileNameDisplay');
        if (input.files.length > 0) {
            display.textContent = input.files[0].name;
            display.classList.add('text-text');
        } else {
            display.textContent = "Seleccionar archivo...";
        }
    }
</script>