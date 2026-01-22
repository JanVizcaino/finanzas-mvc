<div class="mb-8">
    <a href="index.php?action=dashboard" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4 group">
        <span class="mr-1 group-hover:-translate-x-1 transition-transform">←</span> Volver al Dashboard
    </a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                Plan: <span class="text-indigo-600"><?= htmlspecialchars($plan['name']) ?></span>
            </h1>
        </div>
        
        <span class="px-4 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase shadow-sm border <?= $currentUserRole === 'admin' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
            Rol: <?= strtoupper($currentUserRole) ?>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                Añadir Nuevo Gasto
            </h3>
            <form action="index.php?action=store_expense" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                
                <div class="sm:col-span-5">
                    <input type="text" name="title" placeholder="Concepto del gasto" 
                           class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all" required>
                </div>
                
                <div class="sm:col-span-3">
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400 text-sm">€</span>
                        <input type="number" step="0.01" name="amount" placeholder="0.00" 
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 pl-7 transition-all" required>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <select name="category" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all">
                        <option>Comida</option>
                        <option>Transporte</option>
                        <option>Ocio</option>
                        <option>Hogar</option>
                    </select>
                </div>

                <div class="sm:col-span-1 flex items-center justify-center">
                    <label class="cursor-pointer w-full h-full flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg border border-slate-200 transition-colors" title="Adjuntar Recibo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                        </svg>
                        <input type="file" name="receipt" class="hidden"> 
                    </label>
                </div>

                <div class="sm:col-span-1">
                    <button class="w-full h-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center text-xl shadow-md shadow-indigo-200">
                        +
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-3">
            <?php if (empty($expenses)): ?>
                <div class="flex flex-col items-center justify-center p-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium italic">No hay gastos registrados en este plan todavía.</p>
                </div>
            <?php else: ?>
               <?php foreach($expenses as $expense): ?>
                    <div class="group bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-200 flex justify-between items-center">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-slate-800 text-lg leading-tight group-hover:text-indigo-700 transition-colors">
                                    <?= htmlspecialchars($expense['title']) ?>
                                </p>
                                
                                <?php if (!empty($expense['receipt_path'])): ?>
                                    <a href="<?= htmlspecialchars($expense['receipt_path']) ?>" target="_blank" class="text-indigo-400 hover:text-indigo-600 transition-colors" title="Ver Comprobante">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                    <?= htmlspecialchars($expense['category']) ?>
                                </span>
                                <span class="text-xs text-slate-400">
                                    pagado por <span class="font-medium text-slate-600"><?= htmlspecialchars($expense['username']) ?></span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-5">
                            <span class="bg-rose-50 text-rose-700 font-bold px-3 py-1 rounded-lg border border-rose-100">
                                -<?= htmlspecialchars($expense['amount']) ?>€
                            </span>
                            
                            <?php if ($currentUserRole === 'admin'): ?>
                                <a href="index.php?action=delete_expense&id=<?= $expense['id'] ?>&plan_id=<?= $plan['id'] ?>" 
                                class="text-slate-300 hover:text-rose-500 hover:bg-rose-50 p-2 rounded-full transition-all"
                                onclick="return confirm('¿Borrar gasto?')"
                                title="Eliminar gasto">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="space-y-6">
        
        <?php if ($currentUserRole === 'admin'): ?>
            <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Añadir Miembro
                </h3>
                <form action="index.php?action=store_member" method="POST" class="space-y-3">
                    <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                    
                    <input type="text" name="username" placeholder="Nombre de usuario" 
                           class="w-full border-0 shadow-sm ring-1 ring-inset ring-indigo-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 rounded-md p-2 text-sm bg-white" required>
                    
                    <input type="email" name="email" placeholder="Correo electrónico" 
                           class="w-full border-0 shadow-sm ring-1 ring-inset ring-indigo-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 rounded-md p-2 text-sm bg-white" required>
                    
                    <input type="password" name="password" placeholder="Contraseña (deja vacio si el usuario ya existe)" 
                           class="w-full border-0 shadow-sm ring-1 ring-inset ring-indigo-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 rounded-md p-2 text-sm bg-white" required>
                    
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md font-semibold text-sm shadow-sm transition-colors mt-2">
                        Registrar Usuario
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Miembros del Plan</h3>
            <ul class="space-y-3">
                <?php foreach($members as $member): ?>
                    <li class="flex justify-between items-center text-sm group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs
                                <?= $member['role'] === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' ?>">
                                <?= strtoupper(substr($member['username'], 0, 1)) ?>
                            </div>
                            <span class="text-slate-700 font-medium">
                                <?= htmlspecialchars($member['username']) ?> 
                                <?php if($member['role'] === 'admin'): ?>
                                    <span class="ml-1 text-[10px] uppercase font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">Admin</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <?php if ($currentUserRole === 'admin' && $member['role'] !== 'admin'): ?>
                            <a href="index.php?action=remove_member&user_id=<?= $member['id'] ?>&plan_id=<?= $plan['id'] ?>" 
                               class="text-slate-400 hover:text-rose-600 hover:bg-rose-50 px-2 py-1 rounded text-xs font-semibold transition-all opacity-0 group-hover:opacity-100"
                               onclick="return confirm('¿Echar a este usuario?')">
                               Expulsar
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>