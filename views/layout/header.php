<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas SaaS</title>
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
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased flex flex-col min-h-screen">
    <?php if (isset($_SESSION['user_id'])): ?>

        <nav class="bg-white shadow-sm h-16 px-6 flex justify-between items-center z-10 sticky top-0">

            <div class="flex items-center gap-2">
                <a href="index.php?action=dashboard">
                    <img src="../../assets/Blue.png" class="w-14 h-5" alt="Logo Finanzas">
                </a>
            </div>

            <div class="flex items-center gap-6">

                <div class="flex items-center gap-4 text-text">
                    <?php if ($_SESSION['role'] == 'admin' ): ?>

                        <a href="index.php?action=admin_panel" class="hover:text-primary transition cursor-pointer" title="Admin Panel">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </a>

                    <?php endif; ?>
                
                    <a href="index.php?action=admin_panel" class="hover:text-primary transition cursor-pointer" title="Admin Panel">
                        <i class="fa-solid fa-moon text-lg"></i>
                    </a>
                </div>

                <div class="h-4 w-px bg-secondary/30"></div>

                <div class="flex items-center gap-3">
                    <div class="text-text">
                        <i class="fa-solid fa-circle-user text-2xl"></i>
                    </div>
                    <div class="text-text text-base hidden sm:block">
                        <span class="font-normal">Hola, </span>
                        <span class="font-bold">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>
                        </span>
                    </div>
                </div>

                <div class="h-4 w-px bg-secondary/30"></div>

                <a href="index.php?action=logout" class="text-text hover:text-red-500 transition-colors cursor-pointer flex items-center gap-2" title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </a>

            </div>
        </nav>

    <?php endif; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">