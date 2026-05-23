{{-- Simple carousel: Our Services / What We Do (Gimaş-style white cards) --}}
<section id="services" class="services-carousel site-section bg-white">
    <div class="site-container">
        <div class="services-carousel__header">
            <div class="services-carousel__headings">
                <p class="services-carousel__eyebrow">{{ $section->mini_title ?: 'Our Services' }}</p>
                <h2 class="services-carousel__title">{{ $section->title ?: 'What We Do' }}</h2>
            </div>
            <div class="services-carousel__nav" aria-label="Services carousel controls">
                <button type="button" id="services-prev" class="services-carousel__arrow services-carousel__arrow--prev" aria-label="Previous slide">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M14.5 6.5 9 12l5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button type="button" id="services-next" class="services-carousel__arrow services-carousel__arrow--next" aria-label="Next slide">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9.5 6.5 15 12l-5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="swiper services-swiper services-carousel__swiper">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    @php
                        $cardDescRaw = trim(strip_tags((string) ($item->description ?? '')));
                        if ($cardDescRaw === '' && filled($item->page_content)) {
                            $cardDescRaw = trim(strip_tags((string) $item->page_content));
                        }
                        $cardDesc = $cardDescRaw !== ''
                            ? \Illuminate\Support\Str::limit(preg_replace('/\s+/u', ' ', $cardDescRaw), 280)
                            : '';
                        $iconUrl = $item->coverImageUrl();
                    @endphp
                    <div class="swiper-slide">
                        <article class="services-card">
                            <div class="services-card__icon-wrap">
                                @if ($iconUrl !== '')
                                    <img src="{{ $iconUrl }}" alt="" class="services-card__icon-img">
                                @else
                                    @include('site.partials.services-card-icon')
                                @endif
                            </div>
                            <h3 class="services-card__title">{{ $item->label }}</h3>
                            <p @class(['services-card__text', 'services-card__text--empty' => $cardDesc === ''])>{{ $cardDesc !== '' ? $cardDesc : ' ' }}</p>
                            <a href="{{ $item->siteNavHref() }}" class="services-card__link">
                                <span>View Details</span>
                                <span class="services-card__link-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14M13 6l6 6-6 6" />
                                    </svg>
                                </span>
                            </a>
                        </article>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <article class="services-card services-card--empty">
                            <p class="services-card__text">No items found for this section.</p>
                        </article>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
