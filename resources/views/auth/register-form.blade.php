<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $roleTitle ?? 'Daftar' }} - Freelancr</title>

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

        .input-shell {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-shell:focus-within {
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px rgba(247, 147, 30, 0.35);
        }

        .input-shell:focus-within i {
            color: var(--secondary);
        }

        .input-shell input:focus-visible {
            outline: none;
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

        .auth-input {
            background: var(--surface);
            border-color: var(--border);
        }

        .auth-input input {
            color: var(--text);
        }

        .auth-input input::placeholder {
            color: var(--placeholder);
        }

        .auth-action {
            background: var(--button-bg);
            color: var(--button-text);
        }

        .auth-action:hover {
            background: var(--button-hover);
        }

        .auth-outline {
            background: var(--surface);
            border-color: var(--toggle-border);
            color: var(--primary);
        }

        .auth-link {
            color: var(--primary);
            text-decoration-color: var(--secondary);
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
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Daftar akun</p>
                        <h1 class="text-5xl font-semibold leading-[1.04] tracking-normal text-[#F05A28] sm:text-6xl lg:text-7xl">
                            {{ $roleTitle ?? 'Buat akun baru' }}
                        </h1>
                        <p class="auth-muted mt-6 max-w-2xl text-base leading-7 text-[#E5E7EB] sm:text-lg">
                            {{ $roleDescription ?? 'Isi data dasar dulu, lalu lanjut bangun akun kamu.' }}
                        </p>
                    </div>
                    <div class="auth-border auth-surface overflow-hidden rounded-[32px] border border-[#2A2A2A] bg-[#111111]">
                        <div class="hero-photo relative h-56 sm:h-64 lg:h-72" style="--hero-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80');"></div>
                    </div>
                </div>
            </section>

            <section class="flex items-center justify-center">
                <div class="w-full max-w-[520px]">
                    <div class="auth-card soft-shadow overflow-hidden rounded-[32px] border border-[#2A2A2A] bg-[#171717]">
                        <div class="auth-cardhead border-b border-[#2A2A2A] bg-[#F7931E] px-6 py-5 text-center text-[#111111]">
                            <h2 class="text-3xl font-semibold tracking-normal">{{ $roleLabel ?? 'Daftar' }}</h2>
                            <p class="mt-2 text-sm leading-6">{{ $roleDescription ?? 'Isi data yang dibutuhkan untuk lanjut' }}</p>
                        </div>

                        <form id="register-form" class="space-y-5 p-6">
                            @csrf
                            <input type="hidden" name="role" value="{{ $role }}">
                            <div id="error-message" class="hidden rounded-xl bg-red-500/10 p-4 text-sm font-medium text-red-500 border border-red-500/20"></div>

                            <div>
                                <label for="name" class="auth-text mb-2 block text-sm font-semibold text-[#F5F5F4]">Nama lengkap</label>
                                <div class="auth-input input-shell flex h-14 items-center gap-3 rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4">
                                    <i data-lucide="user" class="h-5 w-5 text-[#F7931E]"></i>
                                    <input id="name" name="name" type="text" autocomplete="name" required class="auth-text h-full flex-1 bg-transparent text-base font-medium text-[#F5F5F4] outline-none placeholder:text-[#94A3B8]" placeholder="Nama kamu">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="auth-text mb-2 block text-sm font-semibold text-[#F5F5F4]">Email</label>
                                <div class="auth-input input-shell flex h-14 items-center gap-3 rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4">
                                    <i data-lucide="mail" class="h-5 w-5 text-[#F7931E]"></i>
                                    <input id="email" name="email" type="email" autocomplete="email" required class="auth-text h-full flex-1 bg-transparent text-base font-medium text-[#F5F5F4] outline-none placeholder:text-[#94A3B8]" placeholder="nama@email.com">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="auth-text mb-2 block text-sm font-semibold text-[#F5F5F4]">Password</label>
                                <div class="auth-input input-shell flex h-14 items-center gap-3 rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4">
                                    <i data-lucide="lock" class="h-5 w-5 text-[#F7931E]"></i>
                                    <input id="password" name="password" type="password" autocomplete="new-password" required class="auth-text h-full flex-1 bg-transparent text-base font-medium text-[#F5F5F4] outline-none placeholder:text-[#94A3B8]" placeholder="Buat password (min 6 karakter)">
                                </div>
                            </div>

                            <button type="submit" id="submit-btn" class="auth-action soft-focus inline-flex h-14 w-full items-center justify-center rounded-2xl bg-[#F05A28] px-4 text-base font-semibold text-white transition hover:bg-[#F7931E]">
                                Daftar sebagai {{ $roleLabel ?? 'Akun' }}
                            </button>

                            <p class="auth-muted pt-2 text-center text-sm text-[#E5E7EB]">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="auth-link font-semibold text-[#F05A28] underline decoration-[#F7931E] underline-offset-4">Masuk</a>
                            </p>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        (function () {
            const registerForm = document.getElementById('register-form');
            const errorDiv = document.getElementById('error-message');
            const submitBtn = document.getElementById('submit-btn');

            if (registerForm) {
                registerForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    errorDiv.classList.add('hidden');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Memproses...';

                    const formData = new FormData(registerForm);
                    const data = Object.fromEntries(formData.entries());

                    try {
                        const response = await fetch('/api/auth/register', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(data)
                        });

                        const result = await response.json();

                        if (response.ok) {
                            localStorage.setItem('freelancr_token', result.data.token);
                            localStorage.setItem('freelancr_user', JSON.stringify(result.data.user));
                            window.location.href = '/dashboard';
                        } else {
                            if (result.errors) {
                                const firstError = Object.values(result.errors)[0][0];
                                errorDiv.textContent = firstError;
                            } else {
                                errorDiv.textContent = result.message || 'Registrasi gagal. Silakan coba lagi.';
                            }
                            errorDiv.classList.remove('hidden');
                        }
                    } catch (error) {
                        console.error('Register error:', error);
                        errorDiv.textContent = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                        errorDiv.classList.remove('hidden');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Daftar sebagai {{ $roleLabel ?? "Akun" }}';
                    }
                });
            }
        })();
    </script>
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
