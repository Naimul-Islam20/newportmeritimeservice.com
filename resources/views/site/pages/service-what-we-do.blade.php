@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($page->title),
    'metaDescription' => $page->meta_description,
])

@section('content')
    <section class="service-detail-hero relative flex min-h-[300px] w-full items-center overflow-hidden bg-secondary sm:min-h-[400px]">
        <div class="absolute inset-0">
            <img
                src="{{ $page->hero_background_url }}"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="service-detail-hero__title">{{ $page->hero_title }}</h1>
            <nav class="service-detail-hero__crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>{{ $page->breadcrumb_label }}</span>
            </nav>
        </div>
    </section>

    <section class="service-what-we-do site-section bg-white">
        <div class="site-container">
            <div class="service-what-we-do__overview">
                <p class="service-what-we-do__eyebrow">Overview</p>
                <h2 class="service-what-we-do__title">{{ $page->title }}</h2>
                @if ($overviewParagraphs->count() > 0)
                    <div class="service-what-we-do__prose">
                        @foreach ($overviewParagraphs as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($serviceCards->count() > 0)
                <div class="service-what-we-do__cards-wrap">
                    <p class="service-what-we-do__eyebrow service-what-we-do__eyebrow--sub">Our services</p>
                    <h3 class="service-what-we-do__cards-title">What we do</h3>

                    <div class="service-what-we-do__cards">
                        @foreach ($serviceCards as $card)
                            @php
                                $cardDescRaw = trim(strip_tags((string) ($card['description'] ?? '')));
                                $cardDesc = $cardDescRaw !== ''
                                    ? \Illuminate\Support\Str::limit(preg_replace('/\s+/u', ' ', $cardDescRaw), 280)
                                    : '';
                            @endphp
                            <article class="services-card">
                                <div class="services-card__icon-wrap">
                                    @if (!empty($card['image_url']))
                                        <img src="{{ $card['image_url'] }}" alt="" class="services-card__icon-img">
                                    @else
                                        @include('site.partials.services-card-icon')
                                    @endif
                                </div>
                                <h3 class="services-card__title">{{ $card['label'] }}</h3>
                                <p @class(['services-card__text', 'services-card__text--empty' => $cardDesc === ''])>
                                    {{ $cardDesc !== '' ? $cardDesc : ' ' }}
                                </p>
                                <a href="{{ $card['href'] }}" class="services-card__link">
                                    <span>View Details</span>
                                    <span class="services-card__link-icon" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14M13 6l6 6-6 6" />
                                        </svg>
                                    </span>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

