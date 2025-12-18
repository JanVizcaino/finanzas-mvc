<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-xl border border-gray-100">
        
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Bienvenido de nuevo
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Ingresa tus credenciales para acceder a tus finanzas.
            </p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4" role="alert">
                <p class="text-sm text-red-700">
                    <span class="font-bold">Error:</span> Credenciales incorrectas. Inténtalo de nuevo.
                </p>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="index.php?action=authenticate" method="POST">
            
            <div class="rounded-md shadow-sm space-y-4">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo Electrónico
                    </label>
                    <input id="email" name="email" type="email" required 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors duration-200" 
                        placeholder="tu@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors duration-200" 
                        placeholder="••••••••">
                </div>

            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Iniciar Sesión
                </button>
            </div>

        </form>
        
        <div class="text-center mt-4 border-t pt-4 border-gray-100">
            <p class="text-sm text-gray-600">
                ¿Aún no tienes cuenta? 
                <a href="index.php?action=register" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    Regístrate gratis
                </a>
            </p>
        </div>

    </div>
</div>