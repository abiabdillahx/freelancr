<header class="sticky top-0 z-30 border-b border-[#2A2A2A]/80 bg-[#151515]/85 backdrop-blur">
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        @include('partials.site-logo', [
            'href' => route('home'),
            'class' => 'focus-ring inline-flex items-center gap-3 rounded-xl',
            'imageClass' => 'h-10 w-10 rounded-2xl object-contain',
        ])

        <div class="hidden items-center gap-8 text-sm font-medium text-[#F7931E] md:flex">
            <a href="{{ route('services') }}" class="hover:text-[#F05A28]">Jasa</a>
            <a href="{{ route('freelancers') }}" class="hover:text-[#F05A28]">Freelancer</a>
            <a href="{{ route('orders') }}" class="hover:text-[#F05A28]">Pesanan</a>
        </div>

        <div class="flex items-center gap-3" id="nav-actions">
            <!-- Auth actions will be injected here by JS -->
            <div class="flex items-center gap-3 animate-pulse">
                <div class="h-8 w-16 rounded-xl bg-[#2A2A2A]"></div>
                <div class="h-8 w-20 rounded-xl bg-[#2A2A2A]"></div>
            </div>
        </div>
    </nav>
</header>

<script>
    (function () {
        const navActions = document.getElementById('nav-actions');

        const updateNav = () => {
            const token = localStorage.getItem('freelancr_token');
            let html = '';

            if (token) {
                html = `
                    <a href="{{ route('dashboard') }}" class="focus-ring inline-flex h-10 items-center gap-2 rounded-2xl border border-[#F7931E]/60 bg-[#111111] px-4 text-sm font-semibold text-[#F05A28] transition hover:bg-[#1f1f1f]">
                        Dashboard
                    </a>
                    <button id="logout-btn" class="focus-ring inline-flex h-10 items-center gap-2 rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                        Logout
                    </button>
                `;
            } else {
                html = `
                    <a href="{{ route('login') }}" class="focus-ring inline-flex h-10 items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold text-[#F05A28] hover:bg-[#1f1f1f]">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="focus-ring inline-flex h-10 items-center gap-2 rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                        Daftar
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                `;
            }

            navActions.innerHTML = html;
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    localStorage.removeItem('freelancr_token');
                    localStorage.removeItem('freelancr_user');
                    window.location.href = '/';
                });
            }
        };

        updateNav();
    })();
</script>
