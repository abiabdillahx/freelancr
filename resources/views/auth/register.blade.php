<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - Freelancr</title>

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

    <style>
        :root {
            --primary: #F05A28;
            --secondary: #F7931E;
            --line: #2A2A2A;
            --bg: #111111;
            --panel: #171717;
            --surface: #111111;
            --border: #2A2A2A;
            --text: #F5F5F4;
            --muted: #E5E7EB;
            --placeholder: #94A3B8;
            --card-head-bg: #F7931E;
            --card-head-text: #111111;
            --button-bg: #F05A28;
            --button-hover: #F7931E;
            --button-text: #ffffff;
            --shadow: 0 24px 90px rgba(240, 90, 40, 0.16);
            --toggle-bg: #111111;
            --toggle-text: #F5F5F4;
            --toggle-border: rgba(247, 147, 30, 0.6);
            --hero-overlay: linear-gradient(180deg, rgba(17, 17, 17, 0.12) 0%, rgba(17, 17, 17, 0.45) 55%, rgba(17, 17, 17, 0.85) 100%);
        }

        html[data-theme="light"] {
            --bg: #f8f7f4;
            --panel: #ffffff;
            --surface: #f3f4f6;
            --border: #e5e7eb;
            --text: #111827;
            --muted: #4b5563;
            --placeholder: #9ca3af;
            --card-head-bg: #F05A28;
            --card-head-text: #ffffff;
            --button-bg: #111111;
            --button-hover: #1f2937;
            --button-text: #ffffff;
            --shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
            --toggle-bg: #ffffff;
            --toggle-text: #111827;
            --toggle-border: #e5e7eb;
            --hero-overlay: linear-gradient(180deg, rgba(255, 255, 255, 0.35) 0%, rgba(255, 255, 255, 0.6) 55%, rgba(255, 255, 255, 0.9) 100%);
        }

        body {
            font-family: Poppins, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .soft-shadow {
            box-shadow: var(--shadow);
        }

        .soft-focus:focus-visible {
            outline: 3px solid var(--secondary);
            outline-offset: 3px;
        }

        .auth-body {
            background: var(--bg);
            color: var(--text);
        }

        .auth-panel {
            background: var(--panel);
            border-color: var(--border);
        }

        .auth-card {
            background: var(--panel);
            border-color: var(--border);
        }

        .auth-cardhead {
            background: var(--card-head-bg);
            color: var(--card-head-text);
            border-color: var(--border);
        }

        .auth-border {
            border-color: var(--border);
        }

        .auth-muted {
            color: var(--muted);
        }

        .auth-text {
            color: var(--text);
        }

        .auth-surface {
            background: var(--surface);
        }

        .auth-outline {
            background: var(--surface);
            border-color: var(--toggle-border);
            color: var(--primary);
        }

        .auth-card-item {
            background: var(--surface);
            border-color: var(--border);
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid var(--border);
            background: var(--toggle-bg);
            color: var(--toggle-text);
            font-size: 0.875rem;
            font-weight: 600;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .theme-toggle:hover {
            border-color: var(--secondary);
        }

        .theme-indicator {
            width: 0.7rem;
            height: 0.7rem;
            border-radius: 9999px;
            background: var(--secondary);
            box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.18);
        }

        html[data-theme="light"] .theme-indicator {
            background: #111111;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.12);
        }

        .hero-photo {
            background-image: var(--hero-overlay), var(--hero-image);
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="auth-body min-h-screen bg-[#111111] text-[#F5F5F4] antialiased">
    <main class="min-h-screen px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto grid min-h-[calc(100vh-48px)] max-w-[96rem] gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <section class="auth-panel auth-border relative overflow-hidden rounded-[36px] border border-[#2A2A2A] bg-[#171717] p-6 sm:p-8 lg:p-10">
                <div class="flex items-center justify-between gap-4">
                    @include('partials.site-logo', [
                        'href' => route('home'),
                        'class' => 'inline-flex items-center gap-3',
                        'imageClass' => 'h-11 w-11 rounded-2xl object-contain',
                        'labelClass' => 'text-lg font-semibold tracking-normal text-[#F05A28]',
                    ])

                    <div class="flex items-center gap-3">
                        <button type="button" class="theme-toggle soft-focus" data-theme-toggle aria-label="Ubah tema" aria-pressed="true">
                            <span class="theme-indicator" aria-hidden="true"></span>
                            <span data-theme-label>Dark</span>
                        </button>
                        <a href="{{ route('login') }}" class="auth-outline soft-focus rounded-2xl border border-[#F7931E]/60 bg-[#111111] px-4 py-2 text-sm font-semibold text-[#F05A28] transition hover:bg-[#1f1f1f]">
                            Masuk
                        </a>
                    </div>
                </div>

                <div class="mt-14 flex flex-col gap-8 lg:mt-20">
                    <div class="max-w-3xl">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">
                            Buat akun baru
                        </p>
                        <h1 class="text-5xl font-semibold leading-[1.04] tracking-normal text-[#F05A28] sm:text-6xl lg:text-7xl">
                            Pilih jalur yang sesuai, lalu lanjut daftar.
                        </h1>
                        <p class="auth-muted mt-6 max-w-2xl text-base leading-7 text-[#E5E7EB] sm:text-lg">
                            Sesudah pilih akun, kamu langsung masuk ke form yang relevan.
                        </p>
                    </div>
                    <div class="auth-border auth-surface overflow-hidden rounded-[32px] border border-[#2A2A2A] bg-[#111111]">
                        <div class="hero-photo relative h-56 sm:h-64 lg:h-72" style="--hero-image: url('https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1600&q=80');"></div>
                    </div>
                </div>
            </section>

            <section class="flex items-center justify-center">
                <div class="w-full max-w-[520px]">
                    <div class="auth-card soft-shadow overflow-hidden rounded-[32px] border border-[#2A2A2A] bg-[#171717]">
                        <div class="auth-cardhead border-b border-[#2A2A2A] bg-[#F7931E] px-6 py-5 text-center text-[#111111]">
                            <h2 class="text-3xl font-semibold tracking-normal">Pilih akun</h2>
                            <p class="mt-2 text-sm leading-6">Lanjut ke form daftar yang sesuai</p>
                        </div>

                        <div class="space-y-4 p-6">
                            <a href="{{ route('register.client') }}" class="auth-card-item soft-focus block rounded-[24px] border border-[#2A2A2A] bg-[#111111] p-5 transition hover:border-[#F7931E]">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F05A28]/10 text-[#F7931E]">
                                        <i data-lucide="search" class="h-5 w-5"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="auth-text text-lg font-semibold text-[#F5F5F4]">Client</p>
                                        <p class="auth-muted mt-1 text-sm leading-6 text-[#E5E7EB]">Cari jasa dan kelola pesanan.</p>
                                    </div>
                                    <i data-lucide="arrow-right" class="mt-1 h-5 w-5 text-[#F7931E]"></i>
                                </div>
                            </a>

                            <a href="{{ route('register.freelancer') }}" class="auth-card-item soft-focus block rounded-[24px] border border-[#2A2A2A] bg-[#111111] p-5 transition hover:border-[#F7931E]">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F05A28]/10 text-[#F7931E]">
                                        <i data-lucide="briefcase-business" class="h-5 w-5"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="auth-text text-lg font-semibold text-[#F5F5F4]">Freelancer</p>
                                        <p class="auth-muted mt-1 text-sm leading-6 text-[#E5E7EB]">Pasang jasa dan terima order.</p>
                                    </div>
                                    <i data-lucide="arrow-right" class="mt-1 h-5 w-5 text-[#F7931E]"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script>
        (function () {
            const root = document.documentElement;
            const storageKey = 'freelancr-theme';
            const label = document.querySelector('[data-theme-label]');
            const button = document.querySelector('[data-theme-toggle]');

            const applyTheme = (theme) => {
                root.setAttribute('data-theme', theme);
                if (label) {
                    label.textContent = theme === 'dark' ? 'Dark' : 'Light';
                }
                if (button) {
                    button.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
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

            if (button) {
                button.addEventListener('click', () => {
                    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    applyTheme(next);
                    if (typeof localStorage !== 'undefined') {
                        localStorage.setItem(storageKey, next);
                    }
                });
            }
        })();
    </script>
</body>
</html>
