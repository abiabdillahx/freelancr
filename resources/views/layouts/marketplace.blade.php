<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Freelancr') - Freelancr</title>

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
            --primary: #F05A28;
            --secondary: #F7931E;
            --bg: #111111;
            --panel: #171717;
            --surface: #1C1C1C;
            --border: #2A2A2A;
            --text: #F5F5F4;
            --muted: #E5E7EB;
            --placeholder: #94A3B8;
            --shadow: 0 24px 90px rgba(240, 90, 40, 0.16);
            --toggle-bg: #111111;
            --toggle-text: #F5F5F4;
            --toggle-border: rgba(247, 147, 30, 0.6);
        }

        html[data-theme="light"] {
            --bg: #f8f7f4;
            --panel: #ffffff;
            --surface: #f3f4f6;
            --border: #e5e7eb;
            --text: #111827;
            --muted: #4b5563;
            --placeholder: #9ca3af;
            --shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
            --toggle-bg: #ffffff;
            --toggle-text: #111827;
            --toggle-border: #e5e7eb;
        }

        body {
            font-family: Poppins, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .focus-ring:focus-visible {
            outline: 3px solid var(--primary);
            outline-offset: 3px;
        }

        .page-body {
            background: var(--bg);
            color: var(--text);
        }

        .page-panel {
            background: var(--panel);
            border-color: var(--border);
        }

        .page-card {
            background: var(--panel);
            border-color: var(--border);
        }

        .page-surface {
            background: var(--surface);
        }

        .page-border {
            border-color: var(--border);
        }

        .page-text {
            color: var(--text);
        }

        .page-muted {
            color: var(--muted);
        }

        .page-shadow {
            box-shadow: var(--shadow);
        }

        .page-outline {
            background: var(--surface);
            border-color: var(--toggle-border);
            color: var(--primary);
        }

        .page-chip {
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--primary);
        }

        .page-input {
            background: var(--surface);
            border-color: var(--border);
        }

        .page-input input,
        .page-input textarea,
        .page-input select {
            color: var(--text);
        }

        .page-input input::placeholder,
        .page-input textarea::placeholder {
            color: var(--placeholder);
        }

        .field-shell {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field-shell:focus-within {
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px rgba(247, 147, 30, 0.35);
        }

        .field-shell:focus-within i {
            color: var(--secondary);
        }

        .field-shell input:focus-visible,
        .field-shell textarea:focus-visible,
        .field-shell select:focus-visible {
            outline: none;
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

        html[data-theme="light"] body {
            background-color: var(--bg);
            color: var(--text);
        }

        html[data-theme="light"] [class*="bg-[#111111]"] {
            background-color: var(--bg) !important;
        }

        html[data-theme="light"] [class*="bg-[#151515]"] {
            background-color: #f3f2ee !important;
        }

        html[data-theme="light"] [class*="bg-[#171717]"] {
            background-color: var(--panel) !important;
        }

        html[data-theme="light"] [class*="bg-[#1C1C1C]"] {
            background-color: var(--surface) !important;
        }

        html[data-theme="light"] [class*="text-[#F5F5F4]"] {
            color: var(--text) !important;
        }

        html[data-theme="light"] [class*="text-[#E5E7EB]"] {
            color: var(--muted) !important;
        }

        html[data-theme="light"] [class*="border-[#2A2A2A]"] {
            border-color: var(--border) !important;
        }
    </style>
    @yield('head')
</head>
<body class="page-body antialiased">
    @yield('navbar')

    <main class="pb-16">
        @yield('content')
    </main>

    @yield('footer')

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
    </script>
</body>
</html>
