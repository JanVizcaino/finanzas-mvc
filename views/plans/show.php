<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center w-full md:w-auto">
            <a href="index.php?action=dashboard" class="flex items-center gap-2 text-secondary hover:text-primary transition bg-background px-3 py-1.5 rounded-md shadow-sm border border-transparent hover:border-secondary/20">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span class="text-sm font-medium">Volver</span>
            </a>
            <div class="flex flex-col">
                <h1 class="text-primary text-xl sm:text-2xl font-bold truncate max-w-[250px] sm:max-w-md"><?= htmlspecialchars($plan['name']) ?></h1>
                <p class="text-secondary text-sm sm:text-base truncate max-w-xs sm:max-w-md">
                    <?= isset($plan['detail']) ? htmlspecialchars($plan['detail']) : 'Gestiona los gastos de este proyecto.' ?>
                </p>
            </div>
        </div>

        <?php 
        $totalExpenses = 0; 
        foreach ($expenses as $e) { $totalExpenses += $e['amount']; } 
        ?>
        <div class="text-text text-xl font-medium pl-1 sm:pl-0 self-end md:self-auto">
            -<?= number_format($totalExpenses, 2) ?>€
        </div>
    </div>

    <div class="border-b border-secondary/30 flex mb-6 overflow-x-auto">
        <a href="#" class="px-4 sm:px-6 py-3 border-b-2 border-primary text-text text-sm font-medium whitespace-nowrap">Gastos</a>
        <a href="index.php?action=plan_settings&id=<?= $plan['id'] ?>" class="px-4 sm:px-6 py-3 border-b-2 border-transparent text-secondary hover:text-text text-sm transition-colors whitespace-nowrap">Ajustes y Miembros</a>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3 sm:gap-4">
            <div class="relative w-full sm:w-auto">
                <select class="w-full sm:w-48 h-10 pl-3 pr-8 bg-background border border-secondary/30 rounded-md text-secondary text-sm font-medium appearance-none focus:outline-none focus:border-primary cursor-pointer shadow-sm">
                    <option>Usuario: Todos</option>
                    <?php foreach ($members as $member): ?>
                        <option><?= htmlspecialchars($member['username']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute right-3 top-3 pointer-events-none text-secondary"><i class="fa-solid fa-chevron-down text-xs"></i></div>
            </div>
            <div class="relative w-full sm:w-auto">
                <select class="w-full sm:w-40 h-10 pl-3 pr-8 bg-background border border-secondary/30 rounded-md text-secondary text-sm font-medium appearance-none focus:outline-none focus:border-primary cursor-pointer shadow-sm">
                    <option>Categoría: Todas</option>
                    <option>Comida</option><option>Transporte</option><option>Ocio</option><option>Hogar</option>
                </select>
                <div class="absolute right-3 top-3 pointer-events-none text-secondary"><i class="fa-solid fa-chevron-down text-xs"></i></div>
            </div>
        </div>
        <button onclick="openExpenseSlideOver()" class="w-full md:w-auto h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex justify-center items-center gap-2 text-white text-sm font-medium shadow-sm cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i> Añadir Gasto
        </button>
    </div>

    <div class="flex flex-col gap-3">
        <?php if (empty($expenses)): ?>
            <div class="py-12 text-center text-secondary bg-background rounded-md border border-dashed border-secondary/20 flex flex-col items-center justify-center gap-2">
                <i class="fa-regular fa-clipboard text-3xl opacity-30"></i>
                <p>No hay gastos registrados todavía.</p>
            </div>
        <?php else: ?>
            <?php foreach ($expenses as $expense): ?>
                <div class="bg-background rounded-md shadow-sm px-4 sm:px-6 py-4 sm:py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center border border-transparent hover:border-secondary/30 transition group gap-3 sm:gap-0">
                    
                    <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto flex-1 min-w-0">
                        <div class="flex flex-col min-w-0">
                            <span class="text-text text-base font-medium truncate pr-2"><?= htmlspecialchars($expense['title']) ?></span>
                            <div class="flex gap-3 items-center mt-1 sm:mt-0">
                                <span class="text-secondary text-xs sm:text-sm"><?= date('d/m', strtotime($expense['created_at'] ?? 'now')) ?></span>
                                <span class="sm:hidden text-[10px] bg-secondary/10 px-1.5 py-0.5 rounded text-secondary/80"><?= htmlspecialchars($expense['category']) ?></span>
                                <?php if (!empty($expense['receipt_path'])): ?>
                                    <a href="index.php?action=view_receipt&id=<?= $expense['id'] ?>" target="_blank" class="text-primary text-xs hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-paperclip"></i> <span class="hidden sm:inline">Recibo</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="items-center gap-3 pl-4 border-l border-secondary/20 hidden sm:flex">
                            <div class="bg-secondary/10 px-3 py-1 rounded-full flex items-center gap-2">
                                <div class="w-1.5 h-1.5 bg-text rounded-full"></div>
                                <span class="text-text text-xs font-medium"><?= htmlspecialchars($expense['category']) ?></span>
                            </div>
                            <span class="text-secondary text-sm truncate max-w-[150px]">pagado por <b><?= htmlspecialchars($expense['username']) ?></b></span>
                            <?php if (!empty($expense['detail'])): ?>
                                <span class="text-secondary text-sm truncate max-w-[200px] italic">"<?= htmlspecialchars($expense['detail']) ?>"</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-1 sm:mt-0">
                        <div class="sm:hidden flex items-center gap-2">
                            <div class="w-5 h-5 bg-secondary/10 rounded-full flex items-center justify-center text-[10px] text-secondary font-bold">
                                <?= strtoupper(substr($expense['username'], 0, 1)) ?>
                            </div>
                            <span class="text-secondary text-xs"><?= htmlspecialchars($expense['username']) ?></span>
                        </div>

                        <div class="flex items-center gap-4 sm:gap-6">
                
                            <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-1 sm:mt-0">
                        <div class="sm:hidden flex items-center gap-2">
                            <div class="w-5 h-5 bg-secondary/10 rounded-full flex items-center justify-center text-[10px] text-secondary font-bold">
                                <?= strtoupper(substr($expense['username'], 0, 1)) ?>
                            </div>
                            <span class="text-secondary text-xs"><?= htmlspecialchars($expense['username']) ?></span>
                        </div>

                        <div class="flex items-center gap-4 sm:gap-6">
                            <div class="bg-alert/10 px-3 py-1 rounded-full whitespace-nowrap">
                                <span class="text-alert text-sm font-medium">-<?= number_format($expense['amount'], 2) ?>€</span>
                            </div>

                            <?php                            
                            $isMyExpense = ($expense['user_id'] == $_SESSION['user_id']);

                            $isPlanAdmin = ($planRole === 'admin'); 

                            if ($isPlanAdmin || $isMyExpense):
                            ?>
                                <div class="flex gap-3 sm:gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button type="button"
                                            onclick="openEditExpense(this)"
                                            data-id="<?= $expense['id'] ?>"
                                            data-title="<?= htmlspecialchars($expense['title']) ?>"
                                            data-amount="<?= $expense['amount'] ?>"
                                            data-category="<?= htmlspecialchars($expense['category']) ?>"
                                            data-detail="<?= htmlspecialchars($expense['detail'] ?? '') ?>"
                                            class="w-8 h-8 flex justify-center items-center bg-primary/10 sm:bg-primary/20 rounded-full text-primary hover:bg-primary hover:text-white transition cursor-pointer border-none">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>

                                    <a href="index.php?action=delete_expense&id=<?= $expense['id'] ?>&plan_id=<?= $plan['id'] ?>"
                                       class="w-8 h-8 flex justify-center items-center bg-primary/10 sm:bg-primary/20 rounded-full text-primary hover:bg-primary hover:text-white transition cursor-pointer"
                                       onclick="return confirm('¿Borrar este gasto?')">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if (!empty($relatedPlans)): ?>
        <div class="mt-12 mb-8 border-t border-secondary/20 pt-8">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-wand-magic-sparkles text-primary"></i>
                <h3 class="text-text font-medium text-lg">Quizás te interese...</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($relatedPlans as $related): ?>
                    <a href="index.php?action=view_plan&id=<?= $related['id'] ?>" 
                       class="block p-4 bg-background border border-secondary/20 rounded-lg hover:border-primary/50 hover:shadow-sm transition group">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-text font-semibold group-hover:text-primary transition truncate">
                                <?= htmlspecialchars($related['name']) ?>
                            </h4>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-secondary/10 text-secondary">
                                <?= $related['currency'] ?>
                            </span>
                        </div>
                        <p class="text-secondary text-xs line-clamp-2 h-8">
                            <?= htmlspecialchars($related['detail'] ?? 'Sin descripción') ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<div id="expenseSlideOverBackdrop" class="fixed inset-0 z-50 invisible">
    <div id="expenseSlideOverOverlay" onclick="closeExpenseSlideOver()" class="absolute inset-0 bg-text/50 opacity-0 transition-opacity duration-300 ease-in-out"></div>
    <div class="fixed inset-y-0 right-0 flex max-w-full pl-0 sm:pl-10 pointer-events-none">
        <div id="expenseSlideOverPanel" class="pointer-events-auto w-screen sm:max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-background shadow-xl flex flex-col h-full">
            <div class="h-16 px-4 sm:px-8 py-3.5 border-b border-secondary/20 flex justify-between items-center bg-background flex-shrink-0">
                <h1 id="slideOverTitle" class="text-text text-xl font-bold">Nuevo Gasto</h1>
                <button onclick="closeExpenseSlideOver()" class="w-10 h-10 flex justify-center items-center text-secondary hover:text-text transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form id="expenseForm" action="index.php?action=store_expense" method="POST" enctype="multipart/form-data" class="flex-1 px-4 sm:px-8 py-6 flex flex-col gap-5 overflow-y-auto">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                <input type="hidden" name="id" id="expenseIdInput">
                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Concepto*</label>
                    <input type="text" name="title" id="titleInput" placeholder="Ej: Cena en restaurante" required class="w-full h-10 px-3 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Detalles</label>
                    <textarea name="detail" id="detailInput" placeholder="Detalles adicionales..." class="w-full h-24 px-3 py-2 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm resize-none"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-end gap-4">
                    <div class="flex-1 w-full flex flex-col gap-1.5">
                        <label class="text-text text-sm font-medium">Cantidad*</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" id="amountInput" placeholder="0.00" required class="w-full h-10 px-3 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
                            <div class="absolute right-3 top-2.5 pointer-events-none text-secondary text-xs font-bold">€</div>
                        </div>
                    </div>
                    <div class="flex-1 w-full flex flex-col gap-1.5">
                        <label class="text-text text-sm font-medium">Categoría*</label>
                        <div class="relative">
                            <select name="category" id="categoryInput" class="w-full h-10 px-3 bg-background border border-secondary/30 rounded-md text-sm text-secondary focus:text-text appearance-none focus:outline-none focus:border-primary cursor-pointer">
                                <option value="Comida">Comida</option><option value="Transporte">Transporte</option><option value="Ocio">Ocio</option><option value="Hogar">Hogar</option><option value="Otros">Otros</option>
                            </select>
                            <div class="absolute right-3 top-3 pointer-events-none text-secondary"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 mt-2">
                      <label class="text-text text-sm font-medium">Adjuntar Recibo (Opcional)</label>
                      <input type="file" name="receipt" id="receiptInput" class="hidden" onchange="updateFileName()">
                      <button type="button" onclick="document.getElementById('receiptInput').click()" class="w-full h-10 px-4 bg-background border border-secondary/30 hover:bg-secondary/5 transition rounded-md flex justify-center items-center gap-2 text-primary text-sm font-medium shadow-sm cursor-pointer border-dashed">
                          <i class="fa-solid fa-paperclip"></i>
                          <span id="fileNameDisplay">Seleccionar archivo...</span>
                      </button>
                      <p class="text-xs text-secondary italic mt-1" id="editReceiptNote" style="display:none;">Nota: Subir un archivo reemplazará el anterior.</p>
                </div>
            </form>
            <div class="h-auto sm:h-20 px-4 sm:px-8 py-4 sm:py-2.5 border-t border-secondary/20 flex flex-col-reverse sm:flex-row justify-end items-center gap-3 sm:gap-4 bg-background flex-shrink-0">
                <button type="button" onclick="closeExpenseSlideOver()" class="w-full sm:w-auto h-10 px-4 bg-transparent border border-secondary/30 hover:bg-secondary/5 rounded-md flex items-center justify-center text-secondary text-sm font-medium transition cursor-pointer">Cancelar</button>
                <button type="submit" form="expenseForm" class="w-full sm:w-auto h-10 px-4 bg-primary hover:opacity-90 rounded-md flex items-center justify-center gap-2 text-white text-sm font-medium transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i> <span id="submitBtnText">Guardar Gasto</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    const form = document.getElementById('expenseForm');
    const titleText = document.getElementById('slideOverTitle');
    const submitBtnText = document.getElementById('submitBtnText');
    const backdrop = document.getElementById('expenseSlideOverBackdrop');
    const overlay = document.getElementById('expenseSlideOverOverlay');
    const panel = document.getElementById('expenseSlideOverPanel');

    function openExpenseSlideOver() {
        form.reset();
        document.getElementById('fileNameDisplay').textContent = "Seleccionar archivo...";
        document.getElementById('editReceiptNote').style.display = 'none';
        form.action = "index.php?action=store_expense";
        titleText.textContent = "Nuevo Gasto";
        submitBtnText.textContent = "Guardar Gasto";
        document.getElementById('expenseIdInput').value = "";
        showSlideOver();
    }

    function openEditExpense(btn) {
        const id = btn.dataset.id;
        const title = btn.dataset.title;
        const amount = btn.dataset.amount;
        const category = btn.dataset.category;
        const detail = btn.dataset.detail;

        document.getElementById('expenseIdInput').value = id;
        document.getElementById('titleInput').value = title;
        document.getElementById('amountInput').value = amount;
        document.getElementById('categoryInput').value = category;
        document.getElementById('detailInput').value = detail;
        document.getElementById('fileNameDisplay').textContent = "Mantener actual (o seleccionar nuevo)";
        document.getElementById('editReceiptNote').style.display = 'block';
        form.action = "index.php?action=update_expense";
        titleText.textContent = "Editar Gasto";
        submitBtnText.textContent = "Actualizar Gasto";
        showSlideOver();
    }

    function showSlideOver() {
        backdrop.classList.remove('invisible');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeExpenseSlideOver() {
        overlay.classList.add('opacity-0');
        panel.classList.add('translate-x-full');
        setTimeout(() => {
            backdrop.classList.add('invisible');
        }, 300);
    }

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