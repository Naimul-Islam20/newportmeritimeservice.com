{{-- Quality Certificates & Memberships — carousel from admin-uploaded certificates --}}
@php
    $certificates = $homeCertificates ?? collect();
    $title = filled($section->title) ? trim($section->title) : 'Quality Certificates & Memberships';
    $subtitlePrefix = filled($section->description) ? trim($section->description) : 'Click to see all our';
    $linkLabel = filled($section->button_label) ? trim($section->button_label) : 'Quality Certificates & Memberships';
    $linkUrl = $section->resolvedButtonHref();
    $defaultCertsPageUrl = route('quality-certificates');
    if ($linkUrl === '#' || $linkUrl === '') {
        $linkUrl = $defaultCertsPageUrl;
    }
    $hasLink = filled($linkLabel);
    $swiperId = 'certs-swiper-'.$section->id;
@endphp

<section class="certs-carousel site-section" aria-labelledby="certs-carousel-title-{{ $section->id }}">
    <div class="site-container certs-carousel__inner">
        <div class="certs-carousel__header">
            <div class="certs-carousel__headings">
                <h2 id="certs-carousel-title-{{ $section->id }}" class="certs-carousel__title">{{ $title }}</h2>
                <p class="certs-carousel__subtitle">
                    {{ $subtitlePrefix }}
                    @if ($hasLink)
                        <a href="{{ $linkUrl }}" class="certs-carousel__subtitle-link">{{ $linkLabel }}</a>
                    @else
                        <span class="certs-carousel__subtitle-em">{{ $linkLabel }}</span>
                    @endif
                </p>
            </div>
            @if ($certificates->isNotEmpty())
                <div class="certs-carousel__nav" aria-label="Certificates carousel controls">
                    <button type="button" id="certs-prev-{{ $section->id }}" class="certs-carousel__arrow certs-carousel__arrow--prev" aria-label="Previous">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M14.5 6.5 9 12l5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button type="button" id="certs-next-{{ $section->id }}" class="certs-carousel__arrow certs-carousel__arrow--next" aria-label="Next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M9.5 6.5 15 12l-5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <div class="swiper certs-carousel__swiper" id="{{ $swiperId }}" data-certs-swiper data-section-id="{{ $section->id }}">
            <div class="swiper-wrapper">
                @forelse ($certificates as $cert)
                    @php
                        $imgUrl = $cert->imagePublicUrl();
                        $cardHref = $cert->carouselHref();
                    @endphp
                    <div class="swiper-slide">
                        <a
                            href="{{ $cardHref }}"
                            class="certs-carousel__card certs-carousel__card--link"
                            title="{{ $cert->title }}"
                        >
                            @if ($imgUrl !== '')
                                <img src="{{ $imgUrl }}" alt="{{ $cert->title }}" class="certs-carousel__logo" loading="lazy" decoding="async">
                            @else
                                <span class="certs-carousel__placeholder">{{ $cert->title }}</span>
                            @endif
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <a href="{{ $defaultCertsPageUrl }}" class="certs-carousel__card certs-carousel__card--link certs-carousel__card--empty">
                            <span class="certs-carousel__placeholder">Upload certificates in Admin → Quality Certificates</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
