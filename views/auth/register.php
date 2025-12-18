<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-xl border border-gray-100">
        
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Crea tu cuenta
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Empieza a controlar tus finanzas personales hoy mismo.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="index.php?action=store_user" method="POST">
            
            <div class="rounded-md shadow-sm space-y-4">
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre de Usuario
                    </label>
                    <input id="username" name="username" type="text" required 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors duration-200" 
                        placeholder="Ej. JuanPerez">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo Electrónico
                    </label>
                    <input id="email" name="email" type="email" required 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors duration-200" 
                        placeholder="juan@ejemplo.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors duration-200" 
                        placeholder="••••••••">
                    <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres recomendados.</p>
                </div>

            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Registrarme
                </button>
            </div>

        </form>
        
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                ¿Ya tienes una cuenta? 
                <a href="index.php?action=login" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    Inicia sesión aquí
                </a>
            </p>
        </div>

    </div>
</div>