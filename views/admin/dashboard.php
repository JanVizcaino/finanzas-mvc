<div class="container mx-auto px-6 py-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Panel de Administración</h1>
            <p class="text-gray-500 mt-1">Visión general y control de acceso del sistema.</p>
        </div>
        <a href="index.php?action=dashboard" class="group flex items-center text-gray-500 hover:text-blue-600 transition-colors duration-200 font-medium bg-white px-4 py-2 rounded-lg border border-gray-200 hover:border-blue-300 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a mi Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Usuarios Registrados</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?= count($users) ?></p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Planes Activos</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?= count($plans) ?></p>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Usuarios del Sistema</h2>
            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Total: <?= count($users) ?></span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Rol</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 text-sm">#<?= $user['id'] ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs mr-3">
                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                </div>
                                <span class="font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-6 py-4">
                            <?php if($user['role'] === 'admin'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    Admin
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    Usuario
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($user['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?action=admin_delete_user&id=<?= $user['id'] ?>" 
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar permanentemente a este usuario?');"
                                   class="text-red-500 hover:text-red-700 font-medium text-sm hover:underline">
                                    Eliminar
                                </a>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">Tú</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Planes Financieros (Posts)</h2>
             <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Total: <?= count($plans) ?></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Título del Plan</th>
                        <th class="px-6 py-4">Creado Por</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($plans as $plan): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 text-sm">#<?= $plan['id'] ?></td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($plan['name'] ?? 'Sin nombre') ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                             <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <?= htmlspecialchars($plan['owner_name']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= date('d M, Y', strtotime($plan['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                             <a href="#" class="text-red-500 hover:text-red-700 font-medium text-sm hover:underline">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(empty($plans)): ?>
            <div class="p-8 text-center text-gray-500">
                No hay planes registrados en el sistema.
            </div>
        <?php endif; ?>
    </div>

</div>