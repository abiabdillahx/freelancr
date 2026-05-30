<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

        html {
            scroll-behavior: smooth;
        }

        .focus-ring:focus-visible {
            outline: 3px solid var(--primary);
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

        html[data-theme="light"] [class*="bg-[#131313]"] {
            background-color: #f2f1ee !important;
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

        html[data-theme="light"] [class*="shadow-[0_24px_90px_rgba(240,90,40,0.16)]"] {
            box-shadow: var(--shadow) !important;
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
    <div id="app">
        <!-- Loading spinner -->
        <div id="loading" class="fixed inset-0 flex items-center justify-center bg-[#111111]/90 backdrop-blur-sm z-50 hidden">
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 border-2 border-[#F05A28] border-t-transparent rounded-full animate-spin"></div>
                <span class="text-[#F5F5F4]">Loading...</span>
            </div>
        </div>

        <!-- App content -->
        <div id="app-content" class="min-h-screen hidden">
            @yield('content')
        </div>
    </div>

    <script>
        window.Laravel = @json(@php
            return [
                'csrfToken' => csrf_token(),
                'user' => auth()->check() ? auth()->user() : null,
            ];
        @endphp);
    </script>
</body>
</html>