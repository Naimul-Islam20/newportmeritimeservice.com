@extends('site.layouts.app', [
'title' => config('app.name') . ' — Home',
'metaDescription' => 'Maritime logistics, port operations, and trusted supply chain support.',
])

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .services-swiper,
    .supplies-swiper,
    .news-swiper {
        padding-bottom: 35px !important;
    }

    .supplies-swiper .swiper-slide,
    .services-swiper .swiper-slide,
    .news-swiper .swiper-slide {
        height: auto !important;
    }

    /* Hero carousel full height */
    .hero-swiper,
    .hero-swiper .swiper-wrapper,
    .hero-swiper .swiper-slide {
        height: 100% !important;
    }

    /* Equal-height news cards */
    .news-swiper .swiper-wrapper {
        align-items: stretch;
    }

    .news-swiper .swiper-slide {
        display: flex;
        flex-direction: column;
    }

    .news-swiper .swiper-slide>div {
        flex: 1;
    }

    /* Hero: black shadow directly under each word (text-shadow, not image overlay) */
    .hero-slide__copy h1,
    .hero-slide__copy p {
        text-shadow:
            0 1px 0 rgba(0, 0, 0, 0.85),
            0 2px 4px rgba(0, 0, 0, 0.75),
            0 4px 10px rgba(0, 0, 0, 0.55),
            0 8px 22px rgba(0, 0, 0, 0.35);
    }
</style>
@endpush

@section('content')
{{-- Hero Carousel --}}
<section class="relative h-[300px] w-full overflow-hidden bg-secondary sm:h-[540px] md:h-[700px] lg:h-[800px]">
    <div class="swiper hero-swiper h-full w-full">
        <div class="swiper-wrapper">
            @if ($heroSlides->isNotEmpty())
            @foreach ($heroSlides as $slide)
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    @if ($slide->imagePublicUrl() !== '')
                    <img src="{{ $slide->imagePublicUrl() }}" alt="{{ $slide->title }}" class="h-full w-full object-cover">
                    @else
                    <div class="h-full w-full bg-linear-to-br from-secondary to-secondary" role="img" aria-label="{{ $slide->title }}"></div>
                    @endif
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="hero-slide__copy max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white sm:text-6xl md:text-7xl">
                            {!! nl2br(e($slide->title)) !!}
                        </h1>
                        @if (filled($slide->description))
                        <p class="mt-6 text-base font-bold tracking-normal text-white sm:text-lg md:text-xl">
                            {!! nl2br(e($slide->description)) !!}
                        </p>
                        @endif
                        @if (filled($slide->button_label))
                        <div class="mt-6 sm:mt-10">
                            <a href="{{ $slide->resolvedButtonHref() }}" class="inline-block rounded bg-primary px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-secondary shadow-lg transition-all hover:scale-105 hover:brightness-95 sm:px-10 sm:py-4 sm:text-sm sm:tracking-widest sm:shadow-xl">
                                {{ $slide->button_label }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @else
            {{-- Default slides when no hero rows in the database yet --}}
            {{-- Slide 1: One Partner. Every Need. --}}
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop" alt="Cargo Port" class="h-full w-full object-cover">
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="hero-slide__copy max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white sm:text-6xl md:text-7xl">
                            One Partner. Every Need.
                        </h1>
                        <p class="mt-6 text-base font-bold tracking-normal text-white sm:text-lg md:text-xl">
                            24/7 maritime solutions across all Bangladeshi ports.
                        </p>
                        <div class="mt-6 sm:mt-10">
                            <a href="#services" class="inline-block rounded bg-primary px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-secondary shadow-lg transition-all hover:scale-105 hover:brightness-95 sm:px-10 sm:py-4 sm:text-sm sm:tracking-widest sm:shadow-xl">
                                EXPLORE OUR SERVICES
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2: Built on Trust. Delivered with Precision. --}}
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=2070&auto=format&fit=crop" alt="Container Ship" class="h-full w-full object-cover">
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="hero-slide__copy max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white sm:text-6xl md:text-7xl">
                            Built on Trust.<br>Delivered with Precision.
                        </h1>
                        <p class="mt-6 text-base font-bold tracking-normal text-white sm:text-lg md:text-xl">
                            Quality ship supplies, spares &amp; repairs — all in one window.
                        </p>
                        <div class="mt-6 sm:mt-10">
                            <a href="#supplies" class="inline-block rounded bg-primary px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-secondary shadow-lg transition-all hover:scale-105 hover:brightness-95 sm:px-10 sm:py-4 sm:text-sm sm:tracking-widest sm:shadow-xl">
                                VIEW WHAT WE SUPPLY
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3: Bangladesh's Maritime Experts Since 2012. --}}
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=2070&auto=format&fit=crop" alt="Warehouse" class="h-full w-full object-cover">
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="hero-slide__copy max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white sm:text-6xl md:text-7xl">
                            Bangladesh's Maritime<br>Experts Since 2012.
                        </h1>
                        <p class="mt-6 text-base font-bold tracking-normal text-white sm:text-lg md:text-xl">
                            Serving global fleets with reliability, speed &amp; full compliance.
                        </p>
                        <div class="mt-6 sm:mt-10">
                            <a href="{{ route('contact.create') }}" class="inline-block rounded bg-primary px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-secondary shadow-lg transition-all hover:scale-105 hover:brightness-95 sm:px-10 sm:py-4 sm:text-sm sm:tracking-widest sm:shadow-xl">
                                GET IN TOUCH
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- swiper-wrapper --}}

        {{-- Navigation Arrows --}}
        <button id="hero-prev" class="absolute left-4 top-1/2 z-10 -translate-y-1/2 p-2 text-white/80 transition hover:text-white sm:left-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="h-8 w-8 drop-shadow-md sm:h-12 sm:w-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button id="hero-next" class="absolute right-4 top-1/2 z-10 -translate-y-1/2 p-2 text-white/80 transition hover:text-white sm:right-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="h-8 w-8 drop-shadow-md sm:h-12 sm:w-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>{{-- hero-swiper --}}
</section>

{{-- Home sections (ordered by serial/sort_order from DB) --}}
@foreach (($homeSections ?? []) as $section)
@php
$items = $sectionItems[$section->id] ?? collect();
$sectionStrip = $loop->index % 2 === 0 ? 'primary' : 'secondary';
@endphp

@if ($section->block_type === 'carousel' && $section->variant === 'simple')
@include('site.home-sections.carousel-simple', ['section' => $section, 'items' => $items, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'carousel' && $section->variant === 'content_2')
@include('site.home-sections.carousel-content-2', ['section' => $section, 'items' => $items, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'carousel' && $section->variant === 'news')
@include('site.home-sections.carousel-news', ['section' => $section, 'items' => $items, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'two_column' && $section->two_column_mode === 'image_details')
@include('site.home-sections.two-column-about', ['section' => $section, 'sectionStrip' => $sectionStrip])
@elseif ($section->block_type === 'two_column' && $section->two_column_mode === 'both_sides_details')
@include('site.home-sections.two-column-mission-vision', ['section' => $section, 'sectionStrip' => $sectionStrip])
@endif
@endforeach


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

        // Our Services Slider
        new Swiper('.services-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
            }
        });

        // Ship Supplies Slider
        new Swiper('.supplies-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
            }
        });

        // Latest News Slider
        new Swiper('.news-swiper', {
            slidesPerView: 1,
            spaceBetween: 28,
            loop: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            speed: 700,
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 24
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 28
                },
            }
        });
    });
</script>
@endpush
@endsection