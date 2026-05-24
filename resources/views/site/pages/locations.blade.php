@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Locations'),
    'metaDescription' => 'We serve all ports and straits of Turkey and the ARA area — 365 days, 24 hours delivery across West Europe.',
])

@php
    $mapMarkers = [
        ['label' => 'Rotterdam', 'x' => 72, 'y' => 58, 'lx' => 42, 'ly' => 42],
        ['label' => 'Tuzla', 'x' => 292, 'y' => 98, 'lx' => 318, 'ly' => 78],
        ['label' => 'Istanbul', 'x' => 278, 'y' => 108, 'lx' => 308, 'ly' => 118],
        ['label' => 'Athens', 'x' => 252, 'y' => 138, 'lx' => 218, 'ly' => 152],
        ['label' => 'Mersin', 'x' => 312, 'y' => 128, 'lx' => 348, 'ly' => 138],
    ];

    $areas = [
        [
            'title' => 'All Ports of Turkey',
            'image' => 'https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=900&auto=format&fit=crop',
            'url' => '#',
        ],
        [
            'title' => 'Ports in the ARA area',
            'image' => 'https://images.unsplash.com/photo-1467269209838-ff998a88a88c?q=80&w=900&auto=format&fit=crop',
            'url' => '#',
        ],
    ];

    $processSteps = [
        [
            'label' => 'Getting Order',
            'icon' => 'headset',
        ],
        [
            'label' => "Preparing order and\npackaging process",
            'icon' => 'warehouse',
        ],
        [
            'label' => "Safe delivery service\nin the refrigerated\ntrucks",
            'icon' => 'truck',
        ],
        [
            'label' => 'On-time delivery',
            'icon' => 'pin',
        ],
    ];
@endphp

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="max-w-4xl font-sans text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                We serve all ports and strait of Turkey and the ARA Area
            </h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Locations</span>
            </nav>
        </div>
    </section>

    <section class="locations-page site-section bg-white">
        <div class="site-container">
            <div class="locations-page__overview">
                <div class="locations-page__overview-text">
                    <p class="locations-page__eyebrow">Overview</p>
                    <h2 class="locations-page__title">Locations</h2>
                    <p class="locations-page__lead">
                        We pride ourselves on our delivery and operate 365 days, 24 hours, non-stop in all of the ports and Straits of Turkey and West Europe.
                    </p>
                </div>

                <div class="locations-page__map-wrap">
                    <svg class="locations-page__map" viewBox="0 0 400 200" role="img" aria-label="Map showing Rotterdam, Tuzla, Istanbul, Athens and Mersin">
                        <path class="locations-page__map-land" d="M48 72c28-34 72-48 118-40 46 8 88 34 108 68 18 30 14 68-8 96-28 36-74 58-122 54-52-4-98-36-108-78-6-24 2-52 12-100z"/>
                        <path class="locations-page__map-land" d="M168 88c22-8 48-6 72 8 24 14 40 40 38 66-2 28-24 52-54 58-32 6-64-10-82-36-14-20-12-48 26-96z"/>
                        @foreach ($mapMarkers as $marker)
                            <line
                                class="locations-page__map-line"
                                x1="{{ $marker['x'] }}"
                                y1="{{ $marker['y'] }}"
                                x2="{{ $marker['lx'] }}"
                                y2="{{ $marker['ly'] }}"
                            />
                            <circle class="locations-page__map-dot" cx="{{ $marker['x'] }}" cy="{{ $marker['y'] }}" r="4.5"/>
                            <text class="locations-page__map-label" x="{{ $marker['lx'] }}" y="{{ $marker['ly'] }}">{{ $marker['label'] }}</text>
                        @endforeach
                    </svg>
                </div>
            </div>

            <p class="locations-page__areas-lead">Select an area to view all ports in detail:</p>

            <div class="locations-page__cards">
                @foreach ($areas as $area)
                    <article class="locations-page__card">
                        <a href="{{ $area['url'] }}" class="locations-page__card-link">
                            <div class="locations-page__card-media">
                                <img src="{{ $area['image'] }}" alt="" class="locations-page__card-img" loading="lazy">
                            </div>
                            <div class="locations-page__card-body">
                                <h3 class="locations-page__card-title">{{ $area['title'] }}</h3>
                                <span class="locations-page__card-cta">
                                    View details
                                    <span class="locations-page__card-cta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                            <path d="M12 5v14M5 12h14"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="locations-page__process site-section bg-white" aria-label="Delivery process">
        <div class="site-container">
            <ol class="locations-page__process-list">
                @foreach ($processSteps as $index => $step)
                    <li class="locations-page__process-step">
                        <div class="locations-page__process-icon" aria-hidden="true">
                            @if ($step['icon'] === 'headset')
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2a7 7 0 0 0-7 7v4a5 5 0 0 0 5 5h1v-5H7V9a5 5 0 1 1 10 0v4h-4v5h1a5 5 0 0 0 5-5V9a7 7 0 0 0-7-7zm-1 18h2v2h-2z"/>
                                </svg>
                            @elseif ($step['icon'] === 'warehouse')
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z"/>
                                </svg>
                            @elseif ($step['icon'] === 'truck')
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 6h11v9H3zm11 2h4l3 3v4h-7zm-9 11a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm14 0a2 2 0 1 0 .001-3.999A2 2 0 0 0 19 20z"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="locations-page__process-node" aria-hidden="true"></div>
                        @if (! $loop->last)
                            <span class="locations-page__process-arrow" aria-hidden="true"></span>
                        @endif
                        <p class="locations-page__process-label">{!! nl2br(e($step['label'])) !!}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endsection
