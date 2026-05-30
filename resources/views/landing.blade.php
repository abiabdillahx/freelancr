<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Freelancr - Marketplace Jasa Freelance</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script>
        (function () {
            if (typeof localStorage === 'undefined') {
                return;
            }
            const stored = localStorage.getItem('freelancr-theme');
            if (stored) {
                document.documentElement.setAttribute('data-theme', stored);
            }
        })();
    </script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --icy-aqua: #FEFCDB;
            --light-blue: #FFF0BC;
            --lavender-grey: #F7931E;
            --dusty-lavender: #F7931E;
            --vintage-berry: #F05A28;
            --landing-bg: #111111;
            --landing-panel: #171717;
            --landing-surface: #1C1C1C;
            --landing-border: #2A2A2A;
            --landing-text: #F5F5F4;
            --landing-muted: #E5E7EB;
            --landing-shadow: 0 24px 90px rgba(240, 90, 40, 0.16);
            --toggle-bg: #111111;
            --toggle-text: #F5F5F4;
            --toggle-border: rgba(247, 147, 30, 0.6);
        }

        html[data-theme="light"] {
            --landing-bg: #f8f7f4;
            --landing-panel: #ffffff;
            --landing-surface: #f3f4f6;
            --landing-border: #e5e7eb;
            --landing-text: #111827;
            --landing-muted: #4b5563;
            --landing-shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
            --toggle-bg: #ffffff;
            --toggle-text: #111827;
            --toggle-border: #e5e7eb;
        }

        body {
            font-family: Poppins, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        .focus-ring:focus-visible {
            outline: 3px solid #F05A28;
            outline-offset: 3px;
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid var(--toggle-border);
            background: var(--toggle-bg);
            color: var(--toggle-text);
            font-size: 0.875rem;
            font-weight: 600;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .theme-toggle:hover {
            border-color: #F7931E;
        }

        .theme-indicator {
            width: 0.7rem;
            height: 0.7rem;
            border-radius: 9999px;
            background: #F7931E;
            box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.18);
        }

        html[data-theme="light"] .theme-indicator {
            background: #111111;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.12);
        }

        html[data-theme="light"] body {
            background-color: var(--landing-bg);
            color: var(--landing-text);
        }

        html[data-theme="light"] [class*="bg-[#111111]"] {
            background-color: var(--landing-bg) !important;
        }

        html[data-theme="light"] [class*="bg-[#131313]"] {
            background-color: #f2f1ee !important;
        }

        html[data-theme="light"] [class*="bg-[#171717]"] {
            background-color: var(--landing-panel) !important;
        }

        html[data-theme="light"] [class*="bg-[#1C1C1C]"] {
            background-color: var(--landing-surface) !important;
        }

        html[data-theme="light"] [class*="text-[#F5F5F4]"] {
            color: var(--landing-text) !important;
        }

        html[data-theme="light"] [class*="text-[#E5E7EB]"] {
            color: var(--landing-muted) !important;
        }

        html[data-theme="light"] [class*="border-[#2A2A2A]"] {
            border-color: var(--landing-border) !important;
        }

        html[data-theme="light"] [class*="shadow-[0_24px_90px_rgba(240,90,40,0.16)]"] {
            box-shadow: var(--landing-shadow) !important;
        }

        @keyframes searchPulse {
            0%, 100% { transform: scaleX(0.18); opacity: 0.45; }
            50% { transform: scaleX(1); opacity: 1; }
        }

        @keyframes resultFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        @keyframes cursorBlink {
            0%, 45% { opacity: 1; }
            46%, 100% { opacity: 0; }
        }

        .search-progress {
            transform-origin: left;
            animation: searchPulse 3.4s ease-in-out infinite;
        }

        .result-float {
            animation: resultFloat 4.5s ease-in-out infinite;
        }

        .result-float-delayed {
            animation: resultFloat 4.5s ease-in-out 1.2s infinite;
        }

        .typing-cursor {
            animation: cursorBlink 1s steps(1, end) infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            .search-progress,
            .result-float,
            .result-float-delayed,
            .typing-cursor {
                animation: none;
            }
        }
    </style>
</head>
    <body class="bg-[#111111] text-[#F5F5F4] antialiased">
    @php
        $navbarLinks = [
            ['label' => 'Jasa', 'href' => '#services'],
            ['label' => 'Alur', 'href' => '#workflow'],
            ['label' => 'Review', 'href' => '#reviews'],
        ];
    @endphp

    @include('partials.site-navbar', [
        'links' => $navbarLinks,
        'showThemeToggle' => true,
        'actions' => [
            ['label' => 'Masuk', 'href' => route('login'), 'class' => 'focus-ring hidden rounded-2xl px-4 py-2 text-sm font-semibold text-[#F05A28] hover:bg-[#1f1f1f] sm:inline-flex'],
            ['label' => 'Daftar', 'href' => route('register'), 'class' => 'focus-ring inline-flex h-10 items-center gap-2 rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]', 'icon' => 'arrow-right'],
        ],
    ])

    <main>
        <section class="relative overflow-hidden bg-[#111111]">
            <div class="mx-auto grid min-h-[calc(100vh-64px)] max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_0.95fr] lg:px-8 lg:py-16">
                <div class="max-w-3xl">
                    <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#F7931E]/55 bg-[#171717]/80 px-4 py-2 text-sm font-semibold text-[#F7931E]">
                        <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                        Marketplace jasa freelance untuk kebutuhan kampus
                    </p>
                    <h1 class="text-5xl font-semibold leading-[1.04] tracking-normal text-[#F05A28] sm:text-6xl lg:text-7xl">
                        Temukan freelancer kampus untuk kerja cepat dan rapi.
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-[#E5E7EB] sm:text-lg">
                        Freelancr menghubungkan client dan freelancer mahasiswa untuk desain, pemrograman, terjemahan, dan kebutuhan digital lain.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('services') }}" class="focus-ring inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-[#F05A28] px-5 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                            Cari jasa sekarang
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </a>
                        <a href="{{ route('projects.create') }}" class="focus-ring inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-[#F7931E]/70 bg-[#171717]/75 px-5 text-sm font-semibold text-[#F05A28] transition hover:bg-[#1f1f1f]">
                            Pasang proyek
                            <i data-lucide="plus-circle" class="h-4 w-4"></i>
                        </a>
                    </div>

                </div>

                <div class="relative">
                    <div class="rounded-[32px] border border-[#2A2A2A]/70 bg-[#171717] p-4 shadow-[0_24px_90px_rgba(240,90,40,0.16)]">
                        <div class="rounded-[24px] bg-[#1C1C1C] p-5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-[#F7931E]">Daftar layanan</p>
                                    <h2 class="mt-1 text-2xl font-semibold text-[#F05A28]">Jasa tersedia</h2>
                                </div>
                                <span class="rounded-full bg-[#111111] px-3 py-1 text-sm font-semibold text-[#F05A28]">Aktif</span>
                            </div>

                            <div class="mt-5 rounded-3xl border border-[#2A2A2A]/45 bg-[#111111]/80 p-3">
                                <div class="flex h-12 items-center gap-3 rounded-2xl bg-[#111111] px-4">
                                    <i data-lucide="search" class="h-5 w-5 shrink-0 text-[#F7931E]"></i>
                                    <span id="hero-search-query" class="min-w-0 flex-1 truncate text-sm font-semibold text-[#F05A28]">website portfolio</span>
                                    <span class="typing-cursor h-5 w-0.5 bg-[#F05A28]"></span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-[#2A2A2A]">
                                    <div class="search-progress h-full rounded-full bg-[#F05A28]"></div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs font-medium text-[#E5E7EB]">
                                    <span>Layanan cocok</span>
                                    <span id="hero-search-count">3 layanan</span>
                                </div>
                            </div>

                            <div class="mt-5 space-y-3">
                                <article class="result-float rounded-3xl bg-[#111111]/90 p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-[#F05A28]">Pembuatan Website Portfolio</p>
                                            <p class="mt-1 text-sm leading-6 text-[#E5E7EB]">Landing page dan profil responsive.</p>
                                        </div>
                                        <i data-lucide="monitor-smartphone" class="h-5 w-5 shrink-0 text-[#F05A28]"></i>
                                    </div>
                                    <div class="mt-4 grid grid-cols-4 gap-2 text-sm">
                                        <span class="rounded-2xl bg-[#1C1C1C] px-3 py-2 font-medium text-[#E5E7EB]">IDR 250K</span>
                                        <span class="rounded-2xl bg-[#1C1C1C] px-3 py-2 font-medium text-[#E5E7EB]">USD</span>
                                        <span class="rounded-2xl bg-[#F7931E]/20 px-3 py-2 font-medium text-[#F5F5F4]">SGD</span>
                                        <span class="rounded-2xl bg-[#F7931E]/12 px-3 py-2 font-medium text-[#F5F5F4]">JPY</span>
                                    </div>
                                </article>

                                <article class="result-float-delayed rounded-3xl bg-[#111111]/75 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-[#F05A28]">Desain Poster Event Kampus</p>
                                            <p class="mt-1 text-sm text-[#E5E7EB]">Visual promo untuk seminar dan lomba.</p>
                                        </div>
                                        <i data-lucide="palette" class="h-5 w-5 shrink-0 text-[#F05A28]"></i>
                                    </div>
                                </article>

                                <article class="rounded-3xl bg-[#111111]/75 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-[#F05A28]">Terjemahan Abstrak</p>
                                            <p class="mt-1 text-sm text-[#E5E7EB]">Indonesia ke Inggris untuk dokumen pendek.</p>
                                        </div>
                                        <i data-lucide="languages" class="h-5 w-5 shrink-0 text-[#F05A28]"></i>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="bg-[#131313] py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#F7931E]">Kategori jasa</p>
                        <h2 class="mt-3 text-3xl font-semibold text-[#F05A28] sm:text-4xl">Pilihan layanan untuk kebutuhan kampus.</h2>
                    </div>
                    <p class="max-w-xl text-sm leading-6 text-[#E5E7EB]">
                        Semua layanan disusun buat bantu mahasiswa dan client kerja lebih cepat tanpa ribet.
                    </p>
                </div>

                <div class="mt-10 grid gap-4 md:grid-cols-4">
                    @foreach ([
                        ['icon' => 'pen-tool', 'title' => 'Desain Grafis', 'desc' => 'Poster, banner, dan aset event.'],
                        ['icon' => 'code-2', 'title' => 'Pemrograman', 'desc' => 'Website, dashboard, dan script.'],
                        ['icon' => 'languages', 'title' => 'Penerjemahan', 'desc' => 'Abstrak dan dokumen pendek.'],
                        ['icon' => 'book-open', 'title' => 'Akademik', 'desc' => 'Bantuan format dan materi digital.'],
                    ] as $item)
                        <article class="rounded-[24px] border border-[#2A2A2A]/70 bg-[#171717] p-5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#111111] text-[#F05A28]">
                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-semibold text-[#F05A28]">{{ $item['title'] }}</h3>
                            <p class="mt-2 text-sm leading-6 text-[#E5E7EB]">{{ $item['desc'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="workflow" class="bg-[#111111] py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#F7931E]">Alur kerja</p>
                        <h2 class="mt-3 text-3xl font-semibold text-[#F05A28] sm:text-4xl">Alur kerja yang gampang dipakai.</h2>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ([
                            ['icon' => 'log-in', 'title' => 'Masuk akun', 'desc' => 'Client dan freelancer pakai akun masing-masing.'],
                            ['icon' => 'shopping-bag', 'title' => 'Pilih jasa', 'desc' => 'Client pilih layanan yang paling cocok.'],
                            ['icon' => 'check-circle-2', 'title' => 'Kerjakan order', 'desc' => 'Freelancer proses pesanan sampai selesai.'],
                            ['icon' => 'star', 'title' => 'Beri ulasan', 'desc' => 'Client kasih rating setelah hasil diterima.'],
                        ] as $step)
                            <article class="rounded-[24px] border border-[#2A2A2A]/70 bg-[#171717] p-5">
                                <i data-lucide="{{ $step['icon'] }}" class="h-6 w-6 text-[#F05A28]"></i>
                                <h3 class="mt-4 text-lg font-semibold text-[#F05A28]">{{ $step['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-[#E5E7EB]">{{ $step['desc'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="reviews" class="bg-[#111111] py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-[32px] border border-[#2A2A2A]/70 bg-[#171717] p-6 sm:p-8">
                    <div class="grid gap-8 lg:grid-cols-[1fr_0.9fr] lg:items-center">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#F7931E]">Ulasan dan pembayaran</p>
                            <h2 class="mt-3 text-3xl font-semibold text-[#F05A28] sm:text-4xl">Siap dipakai buat transaksi dan ulasan.</h2>
                            <p class="mt-4 max-w-2xl text-sm leading-6 text-[#E5E7EB]">
                                Ulasan, harga, dan detail layanan bisa ditampilkan langsung di halaman depan marketplace.
                            </p>
                        </div>
                        <div class="rounded-[24px] bg-[#111111] p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1C1C1C] text-[#F05A28]">
                                    <i data-lucide="quote" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-base leading-7 text-[#F05A28]">Hasilnya rapi, cepat, dan sesuai brief.</p>
                                    <p class="mt-3 text-sm font-semibold text-[#E5E7EB]">Ulasan pelanggan, rating 5.0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @php
        $footerColumns = [
            [
                'title' => 'Pages',
                'links' => [
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Login', 'href' => route('login')],
                    ['label' => 'Register', 'href' => route('register')],
                ],
            ],
            [
                'title' => 'Marketplace',
                'links' => [
                    ['label' => 'Jasa', 'href' => '#services'],
                    ['label' => 'Alur kerja', 'href' => '#workflow'],
                    ['label' => 'Ulasan', 'href' => '#reviews'],
                ],
            ],
            [
                'title' => 'Legal',
                'links' => [
                    ['label' => 'Terms', 'href' => '#'],
                    ['label' => 'Privacy', 'href' => '#'],
                    ['label' => 'Help', 'href' => '#'],
                ],
            ],
        ];
        $footerSocials = [
            ['label' => 'Instagram', 'href' => '#', 'icon' => 'instagram'],
            ['label' => 'X', 'href' => '#', 'icon' => 'twitter'],
            ['label' => 'LinkedIn', 'href' => '#', 'icon' => 'linkedin'],
        ];
    @endphp

    @include('partials.site-footer', [
        'columns' => $footerColumns,
        'socials' => $footerSocials,
    ])

    <script>
        const root = document.documentElement;
        const storageKey = 'freelancr-theme';
        const themeLabel = document.querySelector('[data-theme-label]');
        const themeButton = document.querySelector('[data-theme-toggle]');

        const applyTheme = (theme) => {
            root.setAttribute('data-theme', theme);
            if (themeLabel) {
                themeLabel.textContent = theme === 'dark' ? 'Dark' : 'Light';
            }
            if (themeButton) {
                themeButton.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
            }
        };

        const initTheme = () => {
            const stored = typeof localStorage !== 'undefined' ? localStorage.getItem(storageKey) : null;
            if (stored === 'light' || stored === 'dark') {
                applyTheme(stored);
                return;
            }
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            applyTheme(prefersDark ? 'dark' : 'light');
        };

        initTheme();

        if (themeButton) {
            themeButton.addEventListener('click', () => {
                const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                applyTheme(next);
                if (typeof localStorage !== 'undefined') {
                    localStorage.setItem(storageKey, next);
                }
            });
        }

        lucide.createIcons();

        const searchQueries = [
            { text: 'website portfolio', count: '3 layanan' },
            { text: 'desain poster event', count: '2 layanan' },
            { text: 'terjemahan abstrak', count: '1 layanan' },
            { text: 'jasa akademik', count: '4 layanan' },
        ];

        const queryElement = document.getElementById('hero-search-query');
        const countElement = document.getElementById('hero-search-count');
        let queryIndex = 0;

        if (queryElement && countElement && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            window.setInterval(() => {
                queryIndex = (queryIndex + 1) % searchQueries.length;
                queryElement.textContent = searchQueries[queryIndex].text;
                countElement.textContent = searchQueries[queryIndex].count;
            }, 2400);
        }
    </script>
</body>
</html>
