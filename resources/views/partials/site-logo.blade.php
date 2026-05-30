@php
    $href = $href ?? url('/');
    $class = $class ?? 'inline-flex items-center gap-3';
    $imageClass = $imageClass ?? 'h-10 w-10 rounded-2xl object-contain';
    $labelClass = $labelClass ?? 'text-lg font-semibold tracking-normal text-[#F05A28]';
    $subtitleClass = $subtitleClass ?? 'block text-sm text-[#E5E7EB]';
    $subtitle = $subtitle ?? null;
    $showLabel = $showLabel ?? true;
@endphp

<a href="{{ $href }}" class="{{ $class }}">
    <img src="{{ asset('freelancr.png') }}" alt="Freelancr" class="{{ $imageClass }}">
    @if ($showLabel)
        <span>
            <span class="{{ $labelClass }}">Freelancr</span>
            @if ($subtitle)
                <span class="{{ $subtitleClass }}">{{ $subtitle }}</span>
            @endif
        </span>
    @endif
</a>
