<nav class="fixed top-4 left-1/2 z-50 -translate-x-1/2 w-[90%] max-w-4xl">
    <div class="flex items-center justify-between rounded-full border border-[#D5DDC8] bg-white/80 px-4 md:px-6 py-3 shadow-sm backdrop-blur-xl">
        <div class="flex items-center gap-2">
            <div class="h-4 w-4 rounded-full bg-[#EFC765] flex-shrink-0"></div>
            <span class="font-heading text-base md:text-lg font-bold tracking-tight text-[#34402F] truncate">Multi Calendar Nusantara</span>
        </div>

        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-[#4F5F43]">
            <a href="#" class="hover:text-[#34402F] transition-colors">Beranda</a>
            <a href="#konversi" class="hover:text-[#34402F] transition-colors">Konversi</a>
            <a href="#multi-calendar" class="hover:text-[#34402F] transition-colors">Multi Calendar</a>
            <a href="#fitur" class="hover:text-[#34402F] transition-colors">Fitur</a>
        </div>

        <div class="hidden md:block">
            <a href="#konversi" class="inline-flex items-center justify-center rounded-full bg-[#7F946D] px-4 py-2 text-sm font-medium text-[#FFF9EA] shadow transition-colors hover:bg-[#647754] focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-[#EFC765]">
                Mulai Konversi
            </a>
        </div>
        
        <!-- Mobile menu button -->
        <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center p-1 text-[#4F5F43] hover:text-[#34402F] focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu Panel -->
    <div id="mobile-menu-panel" class="hidden absolute top-full left-0 right-0 mt-2 rounded-2xl border border-[#D5DDC8] bg-white/95 p-4 shadow-lg backdrop-blur-xl md:hidden">
        <div class="flex flex-col gap-4 text-sm font-medium text-[#4F5F43]">
            <a href="#" class="mobile-menu-link hover:text-[#34402F] transition-colors px-2">Beranda</a>
            <a href="#konversi" class="mobile-menu-link hover:text-[#34402F] transition-colors px-2">Konversi</a>
            <a href="#multi-calendar" class="mobile-menu-link hover:text-[#34402F] transition-colors px-2">Multi Calendar</a>
            <a href="#fitur" class="mobile-menu-link hover:text-[#34402F] transition-colors px-2">Fitur</a>
            <a href="#konversi" class="mobile-menu-link mt-2 inline-flex items-center justify-center rounded-full bg-[#7F946D] px-4 py-3 text-sm font-medium text-[#FFF9EA] shadow transition-colors hover:bg-[#647754]">
                Mulai Konversi
            </a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const panel = document.getElementById('mobile-menu-panel');
        const links = document.querySelectorAll('.mobile-menu-link');

        if (btn && panel) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                panel.classList.toggle('hidden');
            });

            // Close panel when link clicked
            links.forEach(link => {
                link.addEventListener('click', () => {
                    panel.classList.add('hidden');
                });
            });
            
            // Close panel when clicked outside
            document.addEventListener('click', (e) => {
                if (!btn.contains(e.target) && !panel.contains(e.target)) {
                    panel.classList.add('hidden');
                }
            });
        }
    });
</script>
