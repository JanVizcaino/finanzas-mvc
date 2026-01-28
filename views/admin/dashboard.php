<div class="flex-1 max-w-5xl w-full mx-auto p-12 flex flex-col gap-9">

    <div class="flex justify-start items-center gap-9">
        <a href="index.php?action=dashboard" class="flex items-center gap-2 text-secondary hover:text-primary transition bg-white px-3 py-1.5 rounded-md shadow-sm border border-transparent hover:border-secondary/20">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span class="text-sm font-medium">Volver al Dashboard</span>
        </a>
        <div class="flex flex-col gap-1">
            <h1 class="text-primary text-2xl font-bold">Panel de Administración</h1>
            <p class="text-secondary text-base">Visión general y control de acceso del sistema</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

        <div class="bg-white p-6 rounded-md shadow-sm flex justify-between items-center border border-transparent hover:border-secondary/10 transition">
            <div class="flex flex-col justify-between h-16">
                <span class="text-secondary text-base font-medium">USUARIOS REGISTRADOS</span>
                <span class="text-text text-3xl font-medium"><?= count($users) ?></span>
            </div>
            <div class="w-10 h-10 flex justify-center items-center bg-primary/25 rounded-full text-primary">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-md shadow-sm flex justify-between items-center border border-transparent hover:border-secondary/10 transition">
            <div class="flex flex-col justify-between h-16">
                <span class="text-secondary text-base font-medium">PLANES ACTIVOS</span>
                <span class="text-text text-3xl font-medium"><?= count($plans) ?></span>
            </div>
            <div class="w-10 h-10 flex justify-center items-center bg-primary/25 rounded-full text-primary">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

    </div>

    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <h2 class="text-text text-base font-medium">Usuarios del Sistema</h2>
            <button onclick="openUserSlideOver()" class="text-primary text-sm font-medium hover:underline cursor-pointer bg-transparent border-none flex items-center">
                <i class="fa-solid fa-user-plus mr-1"></i> Nuevo Usuario
            </button>
        </div>

        <div class="bg-white rounded-md shadow-sm overflow-hidden flex flex-col border border-secondary/10">
            <div class="grid grid-cols-5 gap-4 px-6 py-3 border-b border-secondary/10 bg-secondary/5">
                <div class="text-center text-text text-sm font-semibold">ID</div>
                <div class="text-left text-text text-sm font-semibold col-span-1">USUARIO</div>
                <div class="text-left text-text text-sm font-semibold">EMAIL</div>
                <div class="text-center text-text text-sm font-semibold">ROL</div>
                <div class="text-center text-text text-sm font-semibold">ACCIONES</div>
            </div>

            <?php foreach ($users as $user): ?>
                <div class="grid grid-cols-5 gap-4 px-6 py-3 items-center border-b border-secondary/10 hover:bg-secondary/5 transition">

                    <div class="text-center text-secondary text-sm">#<?= $user['id'] ?></div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary/10 rounded-full flex items-center justify-center text-secondary text-xs font-bold">
                            <?= strtoupper(substr($user['username'], 0, 2)) ?>
                        </div>
                        <span class="text-text text-base font-medium truncate"><?= htmlspecialchars($user['username']) ?></span>
                    </div>

                    <div class="text-left text-secondary text-sm truncate"><?= htmlspecialchars($user['email']) ?></div>

                    <div class="flex justify-center">
                        <?php if ($user['role'] === 'admin'): ?>
                            <div class="px-2.5 py-0.5 bg-primary/10 rounded-full flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-primary rounded-full"></div>
                                <span class="text-primary text-xs font-medium">ADMIN</span>
                            </div>
                        <?php else: ?>
                            <div class="px-2.5 py-0.5 bg-secondary/10 rounded-full flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-secondary rounded-full"></div>
                                <span class="text-secondary text-xs font-medium">USUARIO</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-center">
                        <div class="flex justify-center gap-2"> <button onclick="editUser(this)"
                                data-id="<?= $user['id'] ?>"
                                data-username="<?= htmlspecialchars($user['username']) ?>"
                                data-email="<?= htmlspecialchars($user['email']) ?>"
                                data-role="<?= $user['role'] ?>"
                                class="w-8 h-8 flex justify-center items-center bg-secondary/10 rounded-full text-secondary hover:bg-primary hover:text-white transition cursor-pointer"
                                title="Editar Usuario">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>

                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?action=admin_delete_user&id=<?= $user['id'] ?>"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar permanentemente a este usuario?');"
                                    class="w-8 h-8 flex justify-center items-center bg-alert/10 rounded-full text-alert hover:bg-alert hover:text-white transition cursor-pointer"
                                    title="Eliminar Usuario">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </a>
                            <?php else: ?>
                                <div class="w-8 h-8"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="px-6 py-3 border-t border-secondary/20 flex justify-between items-center bg-white">
                <span class="text-text text-xs">Mostrando <b><?= count($users) ?></b> resultados</span>

            </div>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <h2 class="text-text text-base font-medium">Planes Financieros (Posts)</h2>

        <?php if (empty($plans)): ?>
            <div class="bg-white p-8 rounded-md shadow-sm text-center border border-dashed border-secondary/30 text-secondary">
                No hay planes registrados en el sistema.
            </div>
        <?php else: ?>
            <div class="bg-white rounded-md shadow-sm overflow-hidden flex flex-col border border-secondary/10">
                <div class="grid grid-cols-5 gap-4 px-6 py-3 border-b border-secondary/10 bg-secondary/5">
                    <div class="text-center text-text text-sm font-semibold">ID</div>
                    <div class="text-left text-text text-sm font-semibold col-span-1">NOMBRE DEL PLAN</div>
                    <div class="text-left text-text text-sm font-semibold">CREADO POR</div>
                    <div class="text-center text-text text-sm font-semibold">FECHA</div>
                    <div class="text-center text-text text-sm font-semibold">ACCIONES</div>
                </div>

                <?php foreach ($plans as $plan): ?>
                    <div class="grid grid-cols-5 gap-4 px-6 py-3 items-center border-b border-secondary/10 hover:bg-secondary/5 transition">

                        <div class="text-center text-secondary text-sm">#<?= $plan['id'] ?></div>

                        <div class="text-left text-text text-base font-medium truncate"><?= htmlspecialchars($plan['name']) ?></div>

                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-secondary/10 rounded-full flex items-center justify-center text-secondary text-xs font-bold">
                                <?= strtoupper(substr($plan['owner_name'], 0, 1)) ?>
                            </div>
                            <span class="text-secondary text-sm truncate"><?= htmlspecialchars($plan['owner_name']) ?></span>
                        </div>

                        <div class="text-center text-secondary text-sm">
                            <?= date('d M, Y', strtotime($plan['created_at'])) ?>
                        </div>

                        <div class="flex justify-center">
                            <a href="index.php?action=view_plan&id=<?= $plan['id'] ?>" class="w-8 h-8 flex justify-center items-center bg-primary/20 rounded-full text-primary hover:bg-primary hover:text-white transition cursor-pointer mr-2" title="Ver Plan">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <a href="index.php?action=admin_delete_plan&id=<?= $plan['id'] ?>" class="w-8 h-8 flex justify-center items-center bg-alert/10 rounded-full text-alert hover:bg-alert hover:text-white transition cursor-pointer" title="Eliminar Plan" onclick="return confirm('¿Borrar este plan?')">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="px-6 py-3 border-t border-secondary/20 flex justify-between items-center bg-white">
                    <span class="text-text text-xs">Mostrando <b><?= count($plans) ?></b> resultados</span>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<div id="userSlideOverBackdrop" class="fixed inset-0 z-50 invisible">
    
    <div id="userSlideOverOverlay" onclick="closeUserSlideOver()" class="absolute inset-0 bg-gray-900/50 opacity-0 transition-opacity duration-300 ease-in-out"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 pointer-events-none">
        
        <div id="userSlideOverPanel" class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-xl flex flex-col h-full">
            
            <div class="h-16 px-8 py-3.5 border-b border-secondary/20 flex justify-between items-center bg-white flex-shrink-0">
                <h1 id="slideOverTitle" class="text-text text-xl font-bold">Nuevo Usuario</h1>
                <button onclick="closeUserSlideOver()" class="w-10 h-10 flex justify-center items-center text-secondary hover:text-text transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="userForm" action="index.php?action=admin_store_user" method="POST" class="flex-1 px-8 py-6 flex flex-col gap-5 overflow-y-auto">
                
                <input type="hidden" name="id" id="userIdInput">

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Nombre de Usuario*</label>
                    <input type="text" name="username" id="usernameInput" placeholder="Ej: JuanPerez" required
                           class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Correo Electrónico*</label>
                    <input type="email" name="email" id="emailInput" placeholder="juan@ejemplo.com" required
                           class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Contraseña</label>
                    <input type="password" name="password" id="passwordInput" placeholder="••••••••" 
                           class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 shadow-sm">
                    <p id="passwordHint" class="text-xs text-secondary hidden">Dejar en blanco para mantener la contraseña actual.</p>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-text text-sm font-medium">Rol*</label>
                    <div class="relative">
                        <select name="role" id="roleInput" class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text appearance-none focus:outline-none focus:border-primary cursor-pointer">
                            <option value="user">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none text-secondary">
                             <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

            </form>
            
            <div class="h-20 px-4 py-2.5 border-t border-secondary/20 flex justify-end items-center gap-4 bg-white flex-shrink-0">
                <button type="button" onclick="closeUserSlideOver()" class="h-10 px-4 bg-white border border-secondary/30 hover:bg-secondary/5 rounded-md flex items-center justify-center text-secondary text-sm font-medium transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" form="userForm" class="h-10 px-4 bg-primary hover:opacity-90 rounded-md flex items-center justify-center gap-2 text-white text-sm font-medium transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitBtnText">Guardar Usuario</span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const userForm = document.getElementById('userForm');
    const userTitle = document.getElementById('slideOverTitle');
    const userSubmitBtn = document.getElementById('submitBtnText');
    const passwordInput = document.getElementById('passwordInput');
    const passwordHint = document.getElementById('passwordHint');

    const backdrop = document.getElementById('userSlideOverBackdrop');
    const overlay = document.getElementById('userSlideOverOverlay');
    const panel = document.getElementById('userSlideOverPanel');

    // MODO CREAR
    function openUserSlideOver() {
        userForm.reset();
        
        // Configuración para CREAR
        userForm.action = "index.php?action=admin_store_user";
        userTitle.textContent = "Nuevo Usuario";
        userSubmitBtn.textContent = "Crear Usuario";
        document.getElementById('userIdInput').value = "";
        
        // Contraseña obligatoria al crear
        passwordInput.required = true;
        passwordHint.classList.add('hidden');

        showSlideOver();
    }

    // MODO EDITAR
    function editUser(btn) {
        // Obtener datos del botón
        const id = btn.dataset.id;
        const username = btn.dataset.username;
        const email = btn.dataset.email;
        const role = btn.dataset.role;

        // Rellenar formulario
        document.getElementById('userIdInput').value = id;
        document.getElementById('usernameInput').value = username;
        document.getElementById('emailInput').value = email;
        document.getElementById('roleInput').value = role;
        
        // Limpiar campo contraseña
        passwordInput.value = "";

        // Configuración para EDITAR
        userForm.action = "index.php?action=admin_update_user";
        userTitle.textContent = "Editar Usuario";
        userSubmitBtn.textContent = "Actualizar Usuario";

        // Contraseña opcional al editar
        passwordInput.required = false;
        passwordHint.classList.remove('hidden');

        showSlideOver();
    }

    // Funciones visuales
    function showSlideOver() {
        backdrop.classList.remove('invisible');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeUserSlideOver() {
        overlay.classList.add('opacity-0');
        panel.classList.add('translate-x-full');
        setTimeout(() => {
            backdrop.classList.add('invisible');
        }, 300);
    }
</script>