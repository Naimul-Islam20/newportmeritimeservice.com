@php
    $markers = $markers ?? [];
@endphp

@if (count($markers) > 0)
    <div class="where-location__map-overview" role="region" aria-label="Ports in the ARA area">
        <svg class="where-location__map-overview-svg" viewBox="0 0 640 360" role="img" aria-hidden="true">
            <defs>
                <linearGradient id="ara-sea" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#dceaf5" />
                    <stop offset="100%" style="stop-color:#b8d4e8" />
                </linearGradient>
            </defs>
            <rect width="640" height="360" fill="url(#ara-sea)" />
            <path class="where-location__map-land" d="M40 40 L220 35 L280 55 L340 48 L420 42 L600 50 L620 120 L600 200 L580 280 L520 320 L400 340 L280 330 L160 310 L60 280 L30 200 L35 120 Z" />
            <path class="where-location__map-land" d="M480 80 L560 75 L600 100 L590 160 L560 200 L500 220 L460 190 L450 130 Z" />
            <text class="where-location__map-region-label" x="520" y="95">UK</text>
            <text class="where-location__map-region-label" x="300" y="250">FR</text>
            <text class="where-location__map-region-label" x="420" y="115">DE</text>
            <text class="where-location__map-region-label" x="310" y="155">NL / BE</text>
        </svg>
        <div class="where-location__map-overview-markers">
            @foreach ($markers as $marker)
                <a
                    href="{{ $marker->href }}"
                    class="where-location__map-marker @if($marker->active) where-location__map-marker--active @endif"
                    style="left: {{ ($marker->x / 640) * 100 }}%; top: {{ ($marker->y / 360) * 100 }}%;"
                    title="{{ $marker->label }}"
                >
                    <span class="where-location__map-marker-pin" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                    </span>
                    <span class="where-location__map-marker-label">{{ $marker->label }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
