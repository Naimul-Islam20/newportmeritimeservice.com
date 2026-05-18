@php
    $mini = $mini ?? null;
    $title = $title ?? null;
    $miniClass = $miniClass ?? '';
    $titleClass = $titleClass ?? '';
    $titleSize = $titleSize ?? 'lg';
@endphp
@if (filled($mini))
    <h3 @class([
        'font-bold uppercase tracking-wider',
        $miniClass,
        'text-sm' => $titleSize === 'lg',
        'text-xs tracking-[0.2em]' => $titleSize === 'full',
    ])>{{ $mini }}</h3>
@endif
@if (filled($title))
    <h2 @class([
        'font-sans font-bold leading-tight',
        $titleClass,
        'mt-3 text-3xl sm:text-4xl lg:text-[2.75rem]' => $titleSize === 'lg',
        'mt-3 text-2xl sm:text-3xl lg:text-[2.25rem]' => $titleSize === 'full',
        'mt-0' => ! filled($mini),
    ])>{!! $titleSize === 'lg' ? nl2br(e($title)) : e($title) !!}</h2>
@endif
