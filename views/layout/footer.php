</div> 

<footer class="bg-gray-900 text-gray-300 py-8 mt-auto border-t border-gray-800">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            
            <div class="text-center md:text-left">
                <p class="text-sm font-medium">
                    &copy; <?= date('Y') ?> <span class="text-blue-500 font-bold">FinanzasApp MVC</span>.
                </p>
                <p class="text-xs text-gray-500 mt-1">Práctica de Desarrollo Web.</p>
            </div>

            <div class="flex items-center bg-gray-800 rounded-full px-4 py-1.5 border border-gray-700 shadow-sm">
                <span class="text-xs text-gray-400 mr-2 uppercase tracking-wide font-semibold">Estado:</span>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="flex items-center text-xs font-medium text-emerald-400">
                        <span class="relative flex h-2.5 w-2.5 mr-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        Activa
                    </span>
                <?php else: ?>
                    <span class="flex items-center text-xs font-medium text-gray-500">
                        <span class="h-2.5 w-2.5 rounded-full bg-gray-600 mr-2"></span>
                        Inactiva
                    </span>
                <?php endif; ?>
            </div>

        </div>
    </div>
</footer>

</body>
</html>