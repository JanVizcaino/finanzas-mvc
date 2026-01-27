<div class="flex justify-between items-start mb-6">
    <div class="flex gap-6 items-center">
        <a href="index.php?action=dashboard" class="flex items-center gap-2 text-secondary hover:text-primary transition bg-white px-3 py-1.5 rounded-md shadow-sm border border-transparent hover:border-secondary/20">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span class="text-sm font-medium">Volver</span>
        </a>
        <div class="flex flex-col">
            <h1 class="text-primary text-2xl font-bold"><?= htmlspecialchars($plan['name']) ?></h1>
            <p class="text-secondary text-base">Configuración y miembros.</p>
        </div>
    </div>
    <div class="text-text text-xl font-medium">-<?= number_format($totalExpenses ?? 0, 2) ?>€</div>
</div>

<div class="border-b border-secondary/30 flex mb-8">
    <a href="index.php?action=view_plan&id=<?= $plan['id'] ?>" class="px-6 py-3 border-b-2 border-transparent text-secondary hover:text-text text-sm transition-colors">
        Gastos
    </a>
    <a href="#" class="px-6 py-3 border-b-2 border-primary text-text text-sm font-medium">
        Ajustes y Miembros
    </a>
</div>

<form action="index.php?action=update_plan" id="settingsForm" method="POST" class="flex flex-col gap-4 mb-8">
    <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
    <h2 class="text-text text-base font-medium">Información General</h2>

    <div class="flex gap-6">
        <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-text text-sm font-medium">Nombre del Plan</label>
            <input type="text" name="name" value="<?= htmlspecialchars($plan['name']) ?>"
                class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text shadow-sm focus:outline-none focus:border-primary">
        </div>
        <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-text text-sm font-medium">Moneda</label>
            <div class="relative">
                <select name="currency"
                    class="w-full h-10 pl-3 pr-10 bg-white border border-secondary/30 rounded-md text-sm text-text shadow-sm appearance-none focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="EUR" <?= (($plan['currency'] ?? 'EUR') == 'EUR') ? 'selected' : '' ?>>EUR</option>
                    <option value="USD" <?= (($plan['currency'] ?? '') == 'USD') ? 'selected' : '' ?>>USD</option>
                </select>

                <div class="absolute right-3 inset-y-0 flex items-center pointer-events-none text-secondary">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-text text-sm font-medium">Descripción</label>
        <textarea name="detail" class="w-full p-3 bg-white border border-secondary/30 rounded-md text-sm text-text shadow-sm h-24 resize-none focus:outline-none focus:border-primary"><?= htmlspecialchars($plan['detail'] ?? '') ?></textarea>
    </div>
</form>

<div class="flex flex-col gap-4 mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-text text-base font-medium">Miembros del Plan</h2>

        <?php if ($currentUserRole === 'admin'): ?>
            <button onclick="openMemberSlideOver()" class="text-primary text-sm font-medium hover:underline cursor-pointer bg-transparent border-none flex items-center">
                <i class="fa-solid fa-user-plus mr-1"></i> Añadir Usuario
            </button>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-md shadow-sm flex flex-col overflow-hidden">

        <div class="grid grid-cols-5 gap-4 px-6 py-3 border-b border-secondary/10 bg-secondary/5">
            <div class="text-left text-text text-sm font-semibold col-span-2">USUARIO</div>
            <div class="text-center text-text text-sm font-semibold">ROL</div>
            <div class="text-center text-text text-sm font-semibold">GASTADO</div>
            <div class="text-center text-text text-sm font-semibold">ACCIONES</div>
        </div>

        <?php foreach ($members as $member): ?>
            <div class="grid grid-cols-5 gap-4 px-6 py-4 items-center border-b border-secondary/10 hover:bg-secondary/5 transition">

                <div class="flex items-center gap-3 col-span-2">
                    <div class="w-10 h-10 bg-secondary/10 rounded-full flex items-center justify-center text-secondary text-xs font-bold">
                        <?= strtoupper(substr($member['username'], 0, 2)) ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-text text-base font-medium"><?= htmlspecialchars($member['username']) ?></span>
                        <span class="text-secondary text-xs"><?= htmlspecialchars($member['email'] ?? 'Sin email') ?></span>
                    </div>
                </div>

                <div class="flex justify-center">
                    <?php if ($member['role'] === 'admin'): ?>
                        <div class="px-2.5 py-0.5 bg-primary/10 rounded-full flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 bg-primary rounded-full"></div>
                            <span class="text-primary text-xs font-medium">ADMIN</span>
                        </div>
                    <?php else: ?>
                        <div class="px-2.5 py-0.5 bg-secondary/10 rounded-full flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 bg-secondary rounded-full"></div>
                            <span class="text-secondary text-xs font-medium">MIEMBRO</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-center">
                    <div class="px-2.5 py-0.5 bg-alert/10 rounded-full">
                        <span class="text-alert text-xs font-medium">-0€</span>
                    </div>
                </div>

                <div class="flex justify-center">
                    <?php if ($currentUserRole === 'admin' && $member['role'] !== 'admin'): ?>
                        <a href="index.php?action=remove_member&user_id=<?= $member['id'] ?>&plan_id=<?= $plan['id'] ?>"
                            onclick="return confirm('¿Expulsar usuario?')"
                            class="w-8 h-8 flex justify-center items-center bg-primary/20 rounded-full text-primary hover:bg-primary hover:text-white transition cursor-pointer">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<div class="flex justify-end gap-5 mt-4">
    <?php if ($currentUserRole === 'admin'): ?>
        <a href="index.php?action=delete_plan&id=<?= $plan['id'] ?>"
            onclick="return confirm('¿Estás seguro? Esto borrará el plan y todos sus gastos.')"
            class="h-10 px-4 bg-white border border-secondary/30 hover:bg-alert/5 hover:border-alert hover:text-alert transition rounded-md flex items-center gap-2 text-secondary text-sm font-medium cursor-pointer">
            <i class="fa-solid fa-trash"></i>
            Eliminar Plan
        </a>
    <?php endif; ?>

    <button type="submit" form="settingsForm" class="h-10 px-4 bg-primary hover:opacity-90 transition rounded-md flex items-center gap-2 text-white text-sm font-medium shadow-sm cursor-pointer">
        <i class="fa-solid fa-floppy-disk"></i>
        Guardar Cambios
    </button>
</div>

<div id="memberSlideOverBackdrop" class="fixed inset-0 z-50 invisible">

    <div id="memberSlideOverOverlay" onclick="closeMemberSlideOver()" class="absolute inset-0 bg-gray-900/50 opacity-0 transition-opacity duration-300 ease-in-out"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 pointer-events-none">

        <div id="memberSlideOverPanel" class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-xl flex flex-col h-full">

            <div class="h-16 px-8 py-3.5 border-b border-secondary/20 flex justify-between items-center bg-white flex-shrink-0">
                <h1 class="text-text text-xl font-bold">Añadir Miembro</h1>
                <button onclick="closeMemberSlideOver()" class="w-10 h-10 flex justify-center items-center text-secondary hover:text-text transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="addMemberForm" action="index.php?action=store_member" method="POST" class="flex-1 px-8 py-6 flex flex-col gap-5 overflow-y-auto">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Correo Electrónico*</label>
                    <input type="email" name="email" placeholder="ejemplo@email.com" required
                        class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>

            </form>

            <div class="h-20 px-4 py-2.5 border-t border-secondary/20 flex justify-end items-center gap-4 bg-white flex-shrink-0">
                <button type="button" onclick="closeMemberSlideOver()" class="h-10 px-4 bg-white border border-secondary/30 hover:bg-secondary/5 rounded-md flex items-center justify-center text-secondary text-sm font-medium transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" form="addMemberForm" class="h-10 px-4 bg-primary hover:opacity-90 rounded-md flex items-center justify-center gap-2 text-white text-sm font-medium transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-user-plus"></i>
                    Añadir
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function openMemberSlideOver() {
        const backdrop = document.getElementById('memberSlideOverBackdrop');
        const overlay = document.getElementById('memberSlideOverOverlay');
        const panel = document.getElementById('memberSlideOverPanel');

        backdrop.classList.remove('invisible');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeMemberSlideOver() {
        const backdrop = document.getElementById('memberSlideOverBackdrop');
        const overlay = document.getElementById('memberSlideOverOverlay');
        const panel = document.getElementById('memberSlideOverPanel');

        overlay.classList.add('opacity-0');
        panel.classList.add('translate-x-full');

        setTimeout(() => {
            backdrop.classList.add('invisible');
        }, 300);
    }
</script>