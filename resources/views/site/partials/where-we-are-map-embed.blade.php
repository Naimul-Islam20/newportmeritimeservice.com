@php
    $map = $map ?? null;
@endphp

@if ($map && \App\Support\MapEmbed::hasDisplay($map))
    <div class="where-location__map-embed" aria-label="{{ $map->title ?? 'Map' }}">
        @if ($map->type === 'iframe')
            <div class="where-location__map-iframe-wrap">{!! $map->html !!}</div>
        @else
            <iframe
                title="{{ $map->title ?? 'Map' }}"
                src="{{ $map->src }}"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
            ></iframe>
        @endif
    </div>
@endif
