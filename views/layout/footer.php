</div> <footer class="bg-background w-full py-8 px-6 md:px-16 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-4 mt-auto">
    
    <div class="flex flex-col gap-2 md:gap-1 items-center md:items-start text-center md:text-left">
        <span class="text-text text-sm font-medium">© <?= date('Y') ?> Odin MVC</span>
        <span class="text-text/70 text-xs sm:text-sm">Práctica de Desarrollo Web.</span>
    </div>
    
<h1 class="text-text text-xl">Odin</h1>
</footer>
<script>
    const themeToggle = document.getElementById('toggle-theme');
    const body = document.body;

    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark');
    }

    themeToggle.addEventListener('click', (e) => {
        e.preventDefault();
        body.classList.toggle('dark');
        
        if (body.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });
</script>
</body>
</html>