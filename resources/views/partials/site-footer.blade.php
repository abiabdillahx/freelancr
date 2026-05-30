@php
    $left = $left ?? 'Freelancr. Marketplace jasa freelance kampus.';
    $right = $right ?? 'Tempat cari dan jual jasa tanpa ribet.';
    $footerClass = $footerClass ?? 'border-t border-[#2A2A2A]/80 bg-[#111111] py-6';
    $innerClass = $innerClass ?? 'mx-auto max-w-7xl px-4 text-sm text-[#E5E7EB] sm:px-6 lg:px-8';
    $columns = $columns ?? [];
    $socials = $socials ?? [];
@endphp

<footer class="{{ $footerClass }}">
    <div class="{{ $innerClass }}">
        <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr_0.8fr_0.8fr]">
            <div>
                <div class="max-w-sm">
                    <p class="text-base font-semibold text-[#F05A28]">{{ $left }}</p>
                    <p class="mt-3 leading-6 text-[#E5E7EB]">{{ $right }}</p>
                </div>

                @if (! empty($socials))
                    <div class="mt-5 flex flex-wrap gap-3">
                        @foreach ($socials as $social)
                            <a href="{{ $social['href'] }}" class="inline-flex items-center gap-2 rounded-2xl border border-[#2A2A2A] bg-[#171717] px-4 py-2 text-sm font-semibold text-[#F5F5F4] hover:border-[#F7931E] hover:text-[#F05A28]">
                                @if (!empty($social['icon']))
                                    <i data-lucide="{{ $social['icon'] }}" class="h-4 w-4"></i>
                                @endif
                                {{ $social['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @foreach ($columns as $column)
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#F7931E]">{{ $column['title'] }}</p>
                    <div class="mt-4 space-y-3">
                        @foreach ($column['links'] as $link)
                            <a href="{{ $link['href'] }}" class="block text-[#E5E7EB] hover:text-[#F05A28]">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</footer>
