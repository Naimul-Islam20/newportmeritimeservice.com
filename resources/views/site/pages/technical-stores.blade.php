@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Technical Stores'),
    'metaDescription' => 'Technical stores, engine stores, deck supplies, safety equipment and certified marine spare parts for vessels worldwide.',
])

@php
    $technicalSubItems = [
        ['label' => 'Engine Stores', 'url' => route('ship-supply')],
        ['label' => 'Valves', 'url' => '#'],
        ['label' => 'Deck Stores', 'url' => '#'],
        ['label' => 'Safety Equipments', 'url' => '#'],
        ['label' => 'Cabin Stores', 'url' => '#'],
        ['label' => 'Nautical & Stationery Items', 'url' => '#'],
        ['label' => 'Galley Stores', 'url' => '#'],
    ];

    $navTopLevel = [
        ['label' => 'Provision', 'url' => route('ship-supply')],
        ['label' => 'Transit Delivery', 'url' => route('our-services')],
        ['label' => 'Port Delivery', 'url' => route('our-services')],
        ['label' => 'Operations & Logistics', 'url' => route('our-services')],
    ];

    $storeServices = [
        ['Engine Stores', 'Valves', 'Deck Stores', 'Safety Equipments'],
        ['Cabin Stores', 'Galley Stores', 'Nautical & Stationery Items'],
    ];

    $whyChoose = [
        ['title' => 'Dedicated teams', 'icon' => 'team'],
        ['title' => 'True partners', 'icon' => 'partners'],
        ['title' => 'Global know-how', 'icon' => 'global'],
        ['title' => 'Focus on innovation', 'icon' => 'innovation'],
    ];
@endphp

@section('content')
    {{-- Hero --}}
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
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">Technical Stores</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Technical Stores</span>
            </nav>
        </div>
    </section>

    <section class="service-detail site-section bg-white">
        <div class="site-container">
            <div class="service-detail__layout">
                <aside class="service-detail__sidebar" aria-label="Page sidebar">
                    <div class="service-detail__sidebar-upper">
                    <div class="service-detail__widget service-detail__widget--nav">
                        <h2 class="service-detail__widget-title">Our Service Categories</h2>
                        <nav class="service-detail__nav" data-service-detail-nav>
                            <div class="service-detail__nav-group service-detail__nav-group--open" data-service-nav-group>
                                <button
                                    type="button"
                                    class="service-detail__nav-parent service-detail__nav-parent--current"
                                    data-service-nav-toggle
                                    aria-expanded="true"
                                    aria-controls="service-nav-technical-stores"
                                >
                                    <span>Technical Stores</span>
                                    <svg class="service-detail__nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <ul id="service-nav-technical-stores" class="service-detail__nav-children">
                                    @foreach ($technicalSubItems as $item)
                                        <li>
                                            <a href="{{ $item['url'] }}" class="service-detail__nav-child">{{ $item['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @foreach ($navTopLevel as $item)
                                <a href="{{ $item['url'] }}" class="service-detail__nav-parent">
                                    <span>{{ $item['label'] }}</span>
                                    <svg class="service-detail__nav-chevron service-detail__nav-chevron--right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="service-detail__widget service-detail__widget--panel">
                        <h2 class="service-detail__widget-title service-detail__widget-title--bar">Your Spare Parts</h2>
                        <p class="service-detail__widget-text">Find your spare parts</p>
                        <a href="{{ route('quote.request') }}" class="service-detail__btn service-detail__btn--accent">Your spare parts</a>
                    </div>

                    <div class="service-detail__widget service-detail__widget--panel">
                        <h2 class="service-detail__widget-title service-detail__widget-title--bar">Brochures</h2>
                        <p class="service-detail__widget-text">Download our company brochure from the link below</p>
                        <a href="#" class="service-detail__download">
                            <span>Download Brochure PDF</span>
                            <span class="service-detail__download-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3v12M8 11l4 4 4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </a>
                    </div>
                    </div>

                    <div class="service-detail__sidebar-quote">
                        <div class="service-detail__widget service-detail__widget--quote">
                            <h2 class="service-detail__widget-title service-detail__widget-title--bar">Get a Quote</h2>
                            <form action="{{ route('quote.request') }}" method="get" class="service-detail__quote-form">
                                <label class="service-detail__quote-field">
                                    <span class="sr-only">Name</span>
                                    <input type="text" name="name" placeholder="Name" autocomplete="given-name">
                                </label>
                                <label class="service-detail__quote-field">
                                    <span class="sr-only">Surname</span>
                                    <input type="text" name="surname" placeholder="Surname" autocomplete="family-name">
                                </label>
                                <label class="service-detail__quote-field">
                                    <span class="sr-only">Company</span>
                                    <input type="text" name="company" placeholder="Company" autocomplete="organization">
                                </label>
                                <label class="service-detail__quote-field">
                                    <span class="sr-only">Email address</span>
                                    <input type="email" name="email" placeholder="Email Address" autocomplete="email">
                                </label>
                                <button type="submit" class="service-detail__btn service-detail__btn--accent service-detail__btn--block">Get a quote</button>
                            </form>
                        </div>
                    </div>
                </aside>

                <div class="service-detail__main">
                    <p class="service-detail__eyebrow">Our Services</p>
                    <h2 class="service-detail__title">Technical Stores</h2>
                    <h3 class="service-detail__subtitle">Competitive prices and high quality</h3>

                    <figure class="service-detail__gallery">
                        <img
                            src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1400&auto=format&fit=crop"
                            alt="Warehouse shelves with marine supplies"
                            class="service-detail__gallery-img"
                            loading="lazy"
                        >
                    </figure>

                    <div class="service-detail__prose">
                        <p class="service-detail__lead">Technical Stores</p>
                        <p>
                            Our experienced professionals have been assisting ship owners to obtain the correct items and spare parts at most competitive prices for many years.
                        </p>
                        <p>
                            Our strengths also include short delivery times, individual customer consultation, and tailored instrumentation solutions.
                        </p>
                        <p>
                            Our extensive range of equipment meets a vessel’s complete onboard needs. Our products are certified for use in the marine environment and supported with approvals from the major classification societies.
                        </p>
                        <p class="service-detail__highlight">
                            In technical supply, we offer an innovative approach and are always looking to find better ways of fulfilling things.
                        </p>
                    </div>

                    <h3 class="service-detail__section-heading">Our technical stores services</h3>
                    <div class="service-detail__services-grid">
                        @foreach ($storeServices as $column)
                            <ul class="service-detail__services-col">
                                @foreach ($column as $name)
                                    <li>
                                        <span class="service-detail__check" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <span>{{ $name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>

                    <figure class="service-detail__figure">
                        <img
                            src="https://images.unsplash.com/photo-1504917595217-04f9832fdffd?q=80&w=1400&auto=format&fit=crop"
                            alt="Maritime technical operations on deck"
                            class="service-detail__figure-img"
                            loading="lazy"
                        >
                    </figure>

                    <div class="service-detail__why">
                        <h3 class="service-detail__section-heading service-detail__section-heading--why">Why Choose Us?</h3>
                        <div class="service-detail__prose">
                        <p>
                            We offer an innovative approach, and are always looking to find better ways of doing things. Our expertise and knowledge has for many years assisted ship owners to obtain the correct items and spare parts at most competitive price.
                        </p>
                        <p>
                            We ensure that all partners in the purchasing process are satisfied from the purchasing departments to the individual sailors. We regard this to be our duty.
                        </p>
                        <p>
                            Tried and tested components for instrumentation and control equipment and systems help our customers ensure the consistent long-term quality of their products and the efficiency of their production processes.
                        </p>
                        </div>

                        <div class="service-detail__why-grid">
                            @foreach ($whyChoose as $item)
                                <div class="service-detail__why-card">
                                    <div class="service-detail__why-icon" aria-hidden="true">
                                        @include('site.partials.service-detail-why-icon', ['icon' => $item['icon']])
                                    </div>
                                    <h4 class="service-detail__why-title">{{ $item['title'] }}</h4>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
