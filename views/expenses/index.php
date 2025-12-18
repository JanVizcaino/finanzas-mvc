<div class="container mx-auto px-6 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Listado de Gastos</h1>
            <p class="text-gray-500 mt-1">Gestiona tus movimientos financieros recientes.</p>
        </div>
        <a href="?controller=expenses&action=create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm transition-colors duration-200 flex items-center">
            <span class="text-xl leading-none mr-2">+</span> Nuevo Gasto
        </a>
    </div>

    <?php if (empty($expenses)): ?>
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
            <div class="text-gray-300 text-6xl mb-4">💸</div>
            <h3 class="text-lg font-medium text-gray-900">Aún no tienes gastos registrados</h3>
            <p class="text-gray-500 mt-2 mb-6">Comienza a registrar tus movimientos para verlos aquí.</p>
            <a href="?controller=expenses&action=create" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Crear mi primer gasto &rarr;</a>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($expenses as $expense): ?>
                
                <div class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all duration-300 flex flex-col justify-between">
                    
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            <?= htmlspecialchars($expense['category']) ?>
                        </span>
                        <span class="text-xs text-gray-400" title="Fecha de registro">
                            <?= date('d M, Y', strtotime($expense['created_at'])) ?>
                        </span>
                    </div>

                    <div class="mb-2">
                        <h2 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2">
                            <?= htmlspecialchars($expense['title']) ?>
                        </h2>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-end justify-between">
                        <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Monto</span>
                        <span class="text-2xl font-bold text-red-500">
                            - $<?= number_format($expense['amount'], 2) ?>
                        </span>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>
        
    <?php endif; ?>
</div>