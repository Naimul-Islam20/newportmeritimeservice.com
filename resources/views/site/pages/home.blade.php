@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Home'),
    'metaDescription' => 'Maritime logistics, port operations, and trusted supply chain support.',
])

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .services-swiper,
    .supplies-swiper {
        padding-bottom: 35px !important;
    }

    .supplies-swiper .swiper-slide,
    .services-swiper .swiper-slide {
        height: auto !important;
    }

    .supplies-swiper .swiper-wrapper {
        align-items: stretch;
    }

    .supplies-swiper .swiper-slide {
        display: flex;
        flex-direction: column;
    }

    .supplies-swiper .swiper-slide > div {
        flex: 1;
        min-height: 0;
    }

</style>
@endpush

@section('content')
{{-- Hero Carousel --}}
<section class="hero-carousel">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @if ($heroSlides->isNotEmpty())
                @foreach ($heroSlides as $slide)
                    @include('site.partials.hero-slide', [
                        'title' => $slide->title,
                        'buttonLabel' => $slide->button_label,
                        'buttonHref' => $slide->resolvedButtonHref(),
                        'imageUrl' => $slide->imagePublicUrl(),
                        'imageAlt' => $slide->title,
                        'showCta' => filled($slide->button_label),
                    ])
                @endforeach
            @else
                @include('site.partials.hero-slide', [
                    'title' => "On-time delivery &\ncustomer satisfaction",
                    'buttonLabel' => 'EXPLORE NOW',
                    'buttonHref' => '#services',
                    'imageUrl' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop',
                    'imageAlt' => 'On-time delivery and customer satisfaction',
                    'showCta' => true,
                ])
                @include('site.partials.hero-slide', [
                    'title' => "Best quality,\nbest service",
                    'buttonLabel' => 'EXPLORE NOW',
                    'buttonHref' => '#supplies',
                    'imageUrl' => 'https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=2070&auto=format&fit=crop',
                    'imageAlt' => 'Best quality, best service',
                    'showCta' => true,
                ])
                @include('site.partials.hero-slide', [
                    'title' => "30 years of\nexperience",
                    'buttonLabel' => 'EXPLORE NOW',
                    'buttonHref' => route('contact.create'),
                    'imageUrl' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=2070&auto=format&fit=crop',
                    'imageAlt' => '30 years of experience',
                    'showCta' => true,
                ])
            @endif
        </div>

        <button type="button" id="hero-prev" class="hero-carousel__nav hero-carousel__nav--prev" aria-label="Previous slide">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M14.5 6.5 9 12l5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button type="button" id="hero-next" class="hero-carousel__nav hero-carousel__nav--next" aria-label="Next slide">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M9.5 6.5 15 12l-5.5 5.5" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</section>

{{-- Home sections (ordered by serial/sort_order from DB) --}}
@php
    $homeSectionsList = collect($homeSections ?? []);
    $newsHomeSection = $homeSectionsList->first(
        fn ($s) => $s->block_type === 'carousel' && $s->variant === 'news'
    );
    $content2CarouselSection = $homeSectionsList->first(
        fn ($s) => $s->block_type === 'carousel' && $s->variant === 'content_2'
    );
    $content2CarouselItems = $content2CarouselSection
        ? ($sectionItems[$content2CarouselSection->id] ?? collect())
        : collect();
    $mainHomeSections = $homeSectionsList->reject(
        fn ($s) => $s->block_type === 'carousel' && in_array($s->variant, ['news', 'content_2'], true)
    )->values();
    $serviceAreaStripIndex = $mainHomeSections->search(
        fn ($s) => $s->block_type === 'logo_carousel'
    );
    $content2SectionStrip = (($serviceAreaStripIndex === false ? $mainHomeSections->count() : $serviceAreaStripIndex) % 2 === 0)
        ? 'primary'
        : 'secondary';
    $serviceAreaRendered = false;
@endphp
@foreach ($mainHomeSections as $section)
@php
$items = $sectionItems[$section->id] ?? collect();
$sectionStrip = $loop->index % 2 === 0 ? 'primary' : 'secondary';
@endphp

@if ($section->block_type === 'carousel' && $section->variant === 'simple')
@include('site.home-sections.carousel-simple', ['section' => $section, 'items' => $items, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'two_column' && $section->two_column_mode === 'image_details')
@include('site.home-sections.two-column-about', ['section' => $section, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'two_column' && $section->two_column_mode === 'split_cta')
@include('site.home-sections.two-column-mission-vision-split', ['section' => $section, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'logo_carousel')
    @if (! $serviceAreaRendered)
        @php $serviceAreaRendered = true; @endphp
        @include('site.home-sections.service-areas', [
            'serviceArea' => $serviceArea ?? [],
            'content2CarouselSection' => $content2CarouselSection,
            'content2CarouselItems' => $content2CarouselItems,
            'content2SectionStrip' => $content2SectionStrip,
        ])
    @endif
    @include('site.home-sections.certificates-carousel', [
        'section' => $section,
        'sectionStrip' => $sectionStrip,
        'homeCertificates' => $homeCertificates ?? collect(),
    ])
@elseif ($section->block_type === 'image')
@include('site.menu-page-sections.image-block', ['section' => $section, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'text_input')
@include('site.menu-page-sections.text-input', ['section' => $section, 'sectionStrip' => $sectionStrip])
@endif
@endforeach

@if (! $serviceAreaRendered)
    @include('site.home-sections.service-areas', [
        'serviceArea' => $serviceArea ?? [],
        'content2CarouselSection' => $content2CarouselSection,
        'content2CarouselItems' => $content2CarouselItems,
        'content2SectionStrip' => $content2SectionStrip,
    ])
@endif

{{-- Latest news — above footer (dynamic from Blog → News posts) --}}
<!-- @if ($latestNews ?? null)
    @include('site.home-sections.home-latest-news', ['latestNews' => $latestNews])
@endif -->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hero Carousel (loop only when more than one slide — avoids Swiper glitches)
        const heroSlideEls = document.querySelectorAll('.hero-swiper .swiper-slide');
        const heroLoop = heroSlideEls.length > 1;
        const heroSwiper = new Swiper('.hero-swiper', {
            slidesPerView: 1,
            loop: heroLoop,
            autoplay: heroLoop ? {
                delay: 5000,
                disableOnInteraction: false,
            } : false,
            speed: 800,
            navigation: {
                prevEl: '#hero-prev',
                nextEl: '#hero-next',
            },
        });

        // Our Services / What We Do carousel
        const servicesEl = document.querySelector('.services-swiper');
        if (servicesEl) {
            const servicesSlides = servicesEl.querySelectorAll('.swiper-slide');
            const servicesLoop = servicesSlides.length > 1;
            new Swiper('.services-swiper', {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: servicesLoop,
                speed: 600,
                autoplay: servicesLoop ? {
                    delay: 4000,
                    disableOnInteraction: false,
                } : false,
                navigation: {
                    prevEl: '#services-prev',
                    nextEl: '#services-next',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 28,
                    },
                },
            });
        }

        // Branch offices carousel — 3-up row, next arrow beside track
        const branchesEl = document.querySelector('[data-branches-swiper]');
        if (branchesEl) {
            const branchesRow = branchesEl.closest('.service-area-branches__carousel-row');
            const branchesSlides = branchesEl.querySelectorAll('.swiper-slide');
            const branchesCount = branchesSlides.length;
            const branchesNext = branchesRow?.querySelector('[data-branches-next]') ?? null;
            const branchesCanAdvance = branchesCount > 1;
            const branchesUseLoop = branchesCount > 3;

            const branchesSwiper = new Swiper(branchesEl, {
                slidesPerView: 1.15,
                slidesPerGroup: 1,
                spaceBetween: 16,
                loop: branchesUseLoop,
                rewind: branchesCanAdvance && !branchesUseLoop,
                speed: 700,
                grabCursor: true,
                autoplay: branchesCount > 1 ? {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                } : false,
                navigation: {
                    nextEl: branchesNext,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 18,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                },
            });

            if (branchesNext) {
                branchesNext.disabled = !branchesCanAdvance;
            }

            if (branchesCount > 1 && branchesSwiper.autoplay) {
                branchesSwiper.autoplay.start();
            }
        }

        // Apatoto off: Ship Supplies / What We Supply (content_2 — above service area)
        // const suppliesEl = document.querySelector('.supplies-swiper');
        // if (suppliesEl) {
        //     const suppliesSlides = suppliesEl.querySelectorAll('.swiper-slide');
        //     const suppliesLoop = suppliesSlides.length > 1;
        //     new Swiper('.supplies-swiper', {
        //         slidesPerView: 1,
        //         spaceBetween: 24,
        //         loop: suppliesLoop,
        //         speed: 600,
        //         autoplay: suppliesLoop ? {
        //             delay: 4000,
        //             disableOnInteraction: false,
        //         } : false,
        //         breakpoints: {
        //             640: {
        //                 slidesPerView: 2,
        //                 spaceBetween: 24,
        //             },
        //             1024: {
        //                 slidesPerView: 3,
        //                 spaceBetween: 28,
        //             },
        //         },
        //     });
        // }

        // Certificates / memberships logo carousel (arrows + autoplay)
        document.querySelectorAll('[data-certs-swiper]').forEach((el) => {
            const sectionId = el.getAttribute('data-section-id');
            if (!sectionId) return;
            const slides = el.querySelectorAll('.swiper-slide');
            const slideCount = slides.length;
            const canAdvance = slideCount > 1;
            const useLoop = slideCount > 5;
            const prevBtn = document.getElementById('certs-prev-' + sectionId);
            const nextBtn = document.getElementById('certs-next-' + sectionId);
            if (prevBtn) prevBtn.disabled = !canAdvance;
            if (nextBtn) nextBtn.disabled = !canAdvance;

            new Swiper(el, {
                slidesPerView: 2,
                spaceBetween: 16,
                loop: useLoop,
                rewind: canAdvance && !useLoop,
                speed: 600,
                autoplay: canAdvance ? {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                } : false,
                navigation: {
                    prevEl: prevBtn,
                    nextEl: nextBtn,
                },
                breakpoints: {
                    640: { slidesPerView: 3, spaceBetween: 18 },
                    1024: { slidesPerView: 4, spaceBetween: 20 },
                },
            });
        });

    });
</script>
@endpush
@endsection