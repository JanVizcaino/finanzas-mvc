<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas SaaS</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style type="text/tailwindcss">
@theme {
    --font-sans: "Inter", sans-serif;
    
    --color-primary: #D4AF37; 
    --color-text: #0F172A; 
    --color-secondary: #64748B;
    --color-background: #F8FAFC; 
    --color-success: #10B981;
    --color-alert: #EF4444;
}
body.dark {
    --color-background: #0F172A; 
    --color-text: #F8FAFC; 
    --color-secondary: #94A3B8; 
}
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-background text-text font-font-sans antialiased flex flex-col min-h-screen">
    
    <?php if (isset($_SESSION['user_id']) && ($_GET['action'] ?? '') !== 'login'): ?>

        <nav class="bg-background shadow-sm h-16 px-4 sm:px-6 flex justify-between items-center z-10 sticky top-0 border-b border-secondary/10">

            <div class="flex items-center gap-2 shrink-0">
                <a href="index.php?action=dashboard">
                    <h1 class="text-text">Odin</h1>
                </a>
            </div>

            <div class="flex items-center gap-3 sm:gap-6">

                <div class="flex items-center gap-3 sm:gap-4 text-text">
                    <?php if ($_SESSION['role'] == 'admin' ): ?>
                        <a href="index.php?action=admin_panel" class="hover:text-primary transition cursor-pointer" title="Admin Panel">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </a>
                    <?php endif; ?>
                
                    <a href="#" class="hover:text-primary transition cursor-pointer" id="toggle-theme" title="Modo Oscuro (Demo)">
                        <i class="fa-solid fa-moon text-lg"></i>
                    </a>
                </div>

                <div class="hidden sm:block h-4 w-px bg-secondary/30"></div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="text-text">
                        <i class="fa-solid fa-circle-user text-xl sm:text-2xl"></i>
                    </div>
                    <div class="text-text text-base hidden sm:block">
                        <span class="font-normal opacity-80">Hola, </span>
                        <span class="font-bold">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>
                        </span>
                    </div>
                </div>

                <div class="hidden sm:block h-4 w-px bg-secondary/30"></div>

                <a href="index.php?action=logout" class="text-text hover:text-alert transition-colors cursor-pointer flex items-center gap-2" title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </a>

            </div>
        </nav>

    <?php endif; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10 flex-grow w-full">