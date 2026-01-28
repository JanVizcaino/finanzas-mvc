<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-6 sm:p-8 flex flex-col items-center gap-5">

        <div class="flex flex-col items-center gap-5 w-full">
            <img src="../../assets/BlueandGold.png" class="w-auto h-12 sm:h-14 object-contain" alt="Logo">

            <div class="text-center w-full">
                <h1 class="text-text text-lg sm:text-xl font-semibold">Bienvenido de nuevo</h1>
                <p class="text-secondary text-sm mt-3 max-w-xs mx-auto leading-tight">
                    Ingresa tus credenciales para acceder a tus finanzas.
                </p>
            </div>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="w-full bg-red-50 border-l-4 border-red-500 p-3 rounded-r-md" role="alert">
                <p class="text-xs text-red-700">
                    <span class="font-bold">Error:</span> Credenciales incorrectas.
                </p>
            </div>
        <?php endif; ?>

        <form class="w-full py-5 border-b border-secondary/20 flex flex-col gap-5 sm:gap-6" action="index.php?action=authenticate" method="POST">

            <div class="flex flex-col gap-2">
                <label for="email" class="text-text text-sm font-medium">Correo Electrónico</label>
                <input id="email" name="email" type="email" required placeholder="tu@email.com"
                    class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 transition-colors shadow-sm">
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-text text-sm font-medium">Contraseña</label>
                <input id="password" name="password" type="password" required placeholder="••••••••"
                    class="w-full h-10 px-3 bg-white border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 transition-colors shadow-sm">
            </div>

            <div class="flex flex-wrap items-center justify-between gap-y-2 text-xs">
                <div class="flex items-center gap-2">
                    <input id="remember_me" name="remember_me" type="checkbox" 
                        class="h-3 w-3 text-primary focus:ring-primary border-secondary/30 rounded cursor-pointer">
                    <label for="remember_me" class="text-secondary cursor-pointer select-none">Recordarme</label>
                </div>
                <a href="#" class="font-medium text-primary hover:opacity-80 transition-opacity">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit"
                class="w-full h-10 bg-primary hover:opacity-90 transition-opacity rounded-md flex justify-center items-center gap-2 text-white font-medium text-sm mt-2 cursor-pointer shadow-sm">
                <i class="fa-solid fa-right-to-bracket"></i>
                Iniciar Sesión
            </button>
        </form>
        
        <div class="text-center text-xs">
            <span class="text-text">¿Aún no tienes cuenta? </span>
            <a href="index.php?action=register" class="text-primary font-medium hover:underline ml-1 transition-all">
                Regístrate gratis
            </a>
        </div>

    </div>
</div>