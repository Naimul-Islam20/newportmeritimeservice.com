{{-- Branch offices & warehouses — autoplay image carousel (inside service area) --}}
@php
    $branches = is_array($branches ?? null) ? $branches : [];
    $mini = $branches['mini_title'] ?? 'Where We Are';
    $heading = $branches['title'] ?? 'Branch Offices & Warehouses';
    $viewAllLabel = $branches['view_all_label'] ?? 'View all';
    $viewAllUrl = $branches['view_all_url'] ?? route('where-we-are');
    $items = is_array($branches['items'] ?? null) ? $branches['items'] : [];
    $hasViewAll = filled($viewAllLabel) && filled($viewAllUrl) && $viewAllUrl !== '#';
    $slideCount = count($items);
@endphp

<div class="service-area-branches">
    <div class="service-area-branches__header">
        <div class="service-area-branches__headings">
            <p class="service-area-branches__eyebrow">{{ $mini }}</p>
            <h2 class="service-area-branches__title">{{ $heading }}</h2>
        </div>
        @if ($hasViewAll)
            <a href="{{ $viewAllUrl }}" class="service-area-branches__view-all">
                <span>{{ $viewAllLabel }}</span>
                <span class="service-area-branches__view-all-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </span>
            </a>
        @endif
    </div>

    @if ($slideCount > 0)
        <div class="service-area-branches__carousel-row">
            <div class="service-area-branches__track">
                <div class="swiper service-area-branches__swiper" data-branches-swiper>
                    <div class="swiper-wrapper">
                        @foreach ($items as $item)
                            @php
                                $imgUrl = $item['image_url'] ?? '';
                                $href = $item['url'] ?? null;
                                $title = $item['label'] ?? '';
                                $subtitle = $item['subtitle'] ?? '';
                                $hasLink = filled($href) && $href !== '#';
                                $showOverlay = filled($title) || filled($subtitle) || $hasLink;
                            @endphp
                            @if ($imgUrl === '')
                                @continue
                            @endif
                            <div class="swiper-slide">
                                @if ($hasLink)
                                    <a
                                        href="{{ $href }}"
                                        class="service-area-branches__slide service-area-branches__slide-link"
                                        style="background-image: url('{{ e($imgUrl) }}');"
                                        aria-label="{{ $title !== '' ? $title : 'Branch office' }}"
                                    >
                                        @if ($showOverlay)
                                            <span class="service-area-branches__slide-overlay">
                                                @if (filled($subtitle))
                                                    <span class="service-area-branches__slide-eyebrow">{{ $subtitle }}</span>
                                                    <span class="service-area-branches__slide-rule" aria-hidden="true"></span>
                                                @endif
                                                @if (filled($title))
                                                    <span class="service-area-branches__slide-name">{{ $title }}</span>
                                                @endif
                                                <span class="service-area-branches__slide-cta">
                                                    <span>View details</span>
                                                    <span class="service-area-branches__slide-cta-icon" aria-hidden="true">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M5 12h14M13 6l6 6-6 6" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            </span>
                                        @endif
                                    </a>
                                @else
                                    <div
                                        class="service-area-branches__slide"
                                        style="background-image: url('{{ e($imgUrl) }}');"
                                        role="img"
                                        aria-label="{{ $title !== '' ? $title : 'Branch office' }}"
                                    >
                                        @if ($showOverlay)
                                            <span class="service-area-branches__slide-overlay">
                                                @if (filled($subtitle))
                                                    <span class="service-area-branches__slide-eyebrow">{{ $subtitle }}</span>
                                                    <span class="service-area-branches__slide-rule" aria-hidden="true"></span>
                                                @endif
                                                @if (filled($title))
                                                    <span class="service-area-branches__slide-name">{{ $title }}</span>
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="button" class="service-area-branches__nav service-area-branches__nav--next" data-branches-next aria-label="Next slide">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9.5 6.5 15 12l-5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    @endif
</div>
