@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($page->title),
    'metaDescription' => $page->meta_description,
])

@section('content')
    <section class="service-detail-hero relative flex min-h-[300px] w-full items-center overflow-hidden bg-secondary sm:min-h-[400px]">
        @include('site.partials.page-hero-media', ['imageUrl' => $page->hero_background_url])
        <div class="relative z-10 site-container">
            <h1 class="service-detail-hero__title">{{ $page->hero_title }}</h1>
            <nav class="service-detail-hero__crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>{{ $page->breadcrumb_label }}</span>
            </nav>
        </div>
    </section>

    <section class="service-detail site-section bg-white">
        <div class="site-container">
            <div class="service-detail__layout">
                @include('site.partials.service-detail-sidebar', ['sidebar' => $sidebar])

                <div class="service-detail__main">
                    @if ($page->content_layout === 'simple')
                        <h2 class="service-detail__title">{{ $page->title }}</h2>

                        @if (count($page->body_paragraphs) > 0)
                            <div class="service-detail__prose">
                                @foreach ($page->body_paragraphs as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        @endif

                        <figure class="service-detail__figure service-detail__figure--simple">
                            <img
                                src="{{ $page->content_image_url }}"
                                alt="{{ $page->title }}"
                                class="service-detail__figure-img"
                                loading="lazy"
                            >
                        </figure>
                    @else
                        @if (filled($page->eyebrow))
                            <p class="service-detail__eyebrow">{{ $page->eyebrow }}</p>
                        @endif
                        <h2 class="service-detail__title">{{ $page->title }}</h2>
                        @if (filled($page->subtitle))
                            <h3 class="service-detail__subtitle">{{ $page->subtitle }}</h3>
                        @endif

                        @if (count($page->gallery_image_urls) > 0)
                            <div class="service-detail__gallery">
                                @foreach ($page->gallery_image_urls as $galleryUrl)
                                    <img
                                        src="{{ $galleryUrl }}"
                                        alt="{{ $page->title }}"
                                        class="service-detail__gallery-img"
                                        loading="lazy"
                                    >
                                @endforeach
                            </div>
                        @endif

                        <div class="service-detail__prose">
                            @if (filled($page->lead_paragraph))
                                <p class="service-detail__lead">{{ $page->lead_paragraph }}</p>
                            @endif
                            @foreach ($page->body_paragraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                            @if (filled($page->highlight_paragraph))
                                <p class="service-detail__highlight">{{ $page->highlight_paragraph }}</p>
                            @endif
                        </div>

                        @if (filled($page->services_heading) && count($page->service_columns) > 0)
                            <h3 class="service-detail__section-heading">{{ $page->services_heading }}</h3>
                            <div class="service-detail__services-grid">
                                @foreach ($page->service_columns as $column)
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
                        @endif

                        <figure class="service-detail__figure">
                            <img
                                src="{{ $page->content_image_url }}"
                                alt="{{ $page->title }}"
                                class="service-detail__figure-img"
                                loading="lazy"
                            >
                        </figure>
                    @endif

                    @if (filled($page->why_heading) && (count($page->why_paragraphs) > 0 || count($page->why_cards) > 0))
                        <div class="service-detail__why">
                            <h3 class="service-detail__section-heading service-detail__section-heading--why">{{ $page->why_heading }}</h3>
                            @if (count($page->why_paragraphs) > 0)
                                <div class="service-detail__prose">
                                    @foreach ($page->why_paragraphs as $paragraph)
                                        <p>{{ $paragraph }}</p>
                                    @endforeach
                                </div>
                            @endif

                            @if (count($page->why_cards) > 0)
                                <div class="service-detail__why-grid">
                                    @foreach ($page->why_cards as $item)
                                        <div class="service-detail__why-card">
                                            <div class="service-detail__why-icon" aria-hidden="true">
                                                @include('site.partials.service-detail-why-icon', ['icon' => $item['icon']])
                                            </div>
                                            <h4 class="service-detail__why-title">{{ $item['title'] }}</h4>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
