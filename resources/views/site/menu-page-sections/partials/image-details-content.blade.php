@php
    $detailsBodyClass = $detailsBodyClass ?? 'image-details-body text-base leading-relaxed sm:text-lg';
    $descMargin = $descMargin ?? 'mt-0';
    $pointsMargin = $pointsMargin ?? 'mt-0';
    $buttonMargin = $buttonMargin ?? 'mt-8';
    $maxWidth = $maxWidth ?? '';
@endphp
@if (filled($desc ?? null))
    <p @class([$descMargin, $maxWidth, $detailsBodyClass])>{!! nl2br(e($desc)) !!}</p>
@endif
@if (count($points ?? []) > 0)
    <ul @class([$pointsMargin, $maxWidth, 'list-disc space-y-2 pl-5', $detailsBodyClass])>
        @foreach ($points as $p)
            <li>{{ $p }}</li>
        @endforeach
    </ul>
@endif
@if (filled($buttonLabel ?? null))
    <a href="{{ $buttonHref ?? '#' }}" @class([$buttonMargin, 'inline-flex w-fit shrink-0 self-start items-center justify-center rounded-sm bg-primary px-8 py-3.5 text-xs font-bold uppercase tracking-widest text-secondary shadow-md transition-all hover:brightness-95 hover:shadow-lg sm:px-10 sm:py-4'])>
        {{ $buttonLabel }}
    </a>
@endif
