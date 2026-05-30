@php
    $headerClass = $headerClass ?? 'sticky top-0 z-30 border-b border-[#2A2A2A]/80 bg-[#151515]/85 backdrop-blur';
    $navClass = $navClass ?? 'mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8';
    $brandHref = $brandHref ?? url('/');
    $brandSubtitle = $brandSubtitle ?? null;
    $brandClass = $brandClass ?? 'focus-ring inline-flex items-center gap-3 rounded-xl';
    $brandImageClass = $brandImageClass ?? 'h-10 w-10 rounded-2xl object-contain';
    $statusLabel = $statusLabel ?? null;
    $statusClass = $statusClass ?? 'rounded-full border border-[#F7931E]/60 bg-[#111111] px-4 py-2 text-sm font-medium text-[#F05A28]';
    $showThemeToggle = $showThemeToggle ?? false;
    $links = $links ?? [];
    $actions = $actions ?? [];
@endphp

<header class="{{ $headerClass }}">
    <nav class="{{ $navClass }}">
        @include('partials.site-logo', [
            'href' => $brandHref,
            'class' => $brandClass,
            'imageClass' => $brandImageClass,
            'subtitle' => $brandSubtitle,
        ])

        @if (! empty($links))
            <div class="hidden items-center gap-8 text-sm font-medium text-[#F7931E] md:flex">
                @foreach ($links as $link)
                    <a href="{{ $link['href'] }}" class="hover:text-[#F05A28]">{{ $link['label'] }}</a>
                @endforeach
            </div>
        @endif

        <div class="flex items-center gap-3">
            @if ($statusLabel)
                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
            @endif

            @if ($showThemeToggle)
                <button type="button" class="theme-toggle focus-ring" data-theme-toggle aria-label="Ubah tema" aria-pressed="true">
                    <span class="theme-indicator" aria-hidden="true"></span>
                    <span data-theme-label>Dark</span>
                </button>
            @endif

            @foreach ($actions as $action)
                <a href="{{ $action['href'] }}" class="{{ $action['class'] ?? 'focus-ring inline-flex h-10 items-center gap-2 rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]' }}">
                    {{ $action['label'] }}
                    @if (!empty($action['icon']))
                        <i data-lucide="{{ $action['icon'] }}" class="h-4 w-4"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>
</header>
