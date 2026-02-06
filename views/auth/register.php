<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="w-full max-w-sm bg-background rounded-lg shadow-md p-6 sm:p-8 flex flex-col items-center gap-5 border border-secondary/10">

        <div class="flex flex-col items-center gap-5 w-full">
            <img src="../../assets/logo.png" class="w-auto h-22 sm:h-26 object-contain" alt="Logo">
            
            <div class="text-center w-full">
                <h1 class="text-text text-lg sm:text-xl font-semibold">Crea tu cuenta</h1>
                <p class="text-secondary text-sm mt-3 max-w-xs mx-auto leading-tight">
                    Registro rápido solo con nombre de usuario.
                </p>
            </div>
        </div>

        <form class="w-full py-5 border-b border-secondary/20 flex flex-col gap-5 sm:gap-6" action="index.php?action=store_user" method="POST">
            
            <div class="flex flex-col gap-2">
                <label for="username" class="text-text text-sm font-medium">Nombre de Usuario</label>
                 <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-secondary/50">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <input id="username" name="username" type="text" required placeholder="Ej. JuanPerez" 
                           class="w-full h-10 pl-9 pr-3 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 transition-colors shadow-sm">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-text text-sm font-medium">Contraseña</label>
                 <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-secondary/50">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input id="password" name="password" type="password" required placeholder="••••••••" 
                           class="w-full h-10 pl-9 pr-3 bg-background border border-secondary/30 rounded-md text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-secondary/50 transition-colors shadow-sm">
                </div>
                <span class="text-secondary text-xs">Mínimo 8 caracteres recomendados.</span>
            </div>

            <button type="submit" class="w-full h-10 bg-primary hover:opacity-90 transition-all rounded-md flex justify-center items-center gap-2 text-white font-medium text-sm mt-2 cursor-pointer shadow-sm">
                <i class="fa-solid fa-user-plus"></i>
                Registrarme
            </button>
        </form>

        <div class="text-center text-xs">
            <span class="text-text">¿Ya tienes una cuenta?</span>
            <a href="index.php?action=login" class="text-primary font-medium hover:underline ml-1 transition-all">
                Inicia sesión aquí
            </a>
        </div>

    </div>
</div>