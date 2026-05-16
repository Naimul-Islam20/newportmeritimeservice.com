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
</style>
@endpush

@section('content')
{{-- Hero Carousel --}}
<section class="relative h-[480px] w-full overflow-hidden bg-slate-900 md:h-[560px] lg:h-[620px]">
    <div class="swiper hero-swiper h-full w-full">
        <div class="swiper-wrapper">
            @if ($heroSlides->isNotEmpty())
            @foreach ($heroSlides as $slide)
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    @if ($slide->imagePublicUrl() !== '')
                    <img src="{{ $slide->imagePublicUrl() }}" alt="{{ $slide->title }}" class="h-full w-full object-cover opacity-75">
                    @else
                    <div class="h-full w-full bg-linear-to-br from-slate-800 to-[#01223b]" role="img" aria-label="{{ $slide->title }}"></div>
                    @endif
                    <div class="absolute inset-0 bg-[#01223b]/45"></div>
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white drop-shadow-2xl sm:text-6xl md:text-7xl">
                            {!! nl2br(e($slide->title)) !!}
                        </h1>
                        @if (filled($slide->description))
                        <p class="mt-6 text-base font-bold tracking-normal text-white drop-shadow-lg sm:text-lg md:text-xl">
                            {!! nl2br(e($slide->description)) !!}
                        </p>
                        @endif
                        @if (filled($slide->button_label))
                        <div class="mt-10">
                            <a href="{{ $slide->resolvedButtonHref() }}" class="inline-block rounded bg-[#3eb0e3] px-10 py-4 text-sm font-bold uppercase tracking-widest text-white shadow-xl transition-all hover:scale-105 hover:bg-[#2b9bc9]">
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
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop" alt="Cargo Port" class="h-full w-full object-cover opacity-70">
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white drop-shadow-2xl sm:text-6xl md:text-7xl">
                            One Partner. Every Need.
                        </h1>
                        <p class="mt-6 text-base font-bold tracking-normal text-white drop-shadow-lg sm:text-lg md:text-xl">
                            24/7 maritime solutions across all Bangladeshi ports.
                        </p>
                        <div class="mt-10">
                            <a href="#services" class="inline-block rounded bg-[#3eb0e3] px-10 py-4 text-sm font-bold uppercase tracking-widest text-white shadow-xl transition-all hover:scale-105 hover:bg-[#2b9bc9]">
                                EXPLORE OUR SERVICES
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2: Built on Trust. Delivered with Precision. --}}
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=2070&auto=format&fit=crop" alt="Container Ship" class="h-full w-full object-cover opacity-80">
                    <div class="absolute inset-0 bg-[#01223b]/50"></div>
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white drop-shadow-2xl sm:text-6xl md:text-7xl">
                            Built on Trust.<br>Delivered with Precision.
                        </h1>
                        <p class="mt-6 text-base font-bold tracking-normal text-white drop-shadow-lg sm:text-lg md:text-xl">
                            Quality ship supplies, spares &amp; repairs — all in one window.
                        </p>
                        <div class="mt-10">
                            <a href="#supplies" class="inline-block rounded bg-[#3eb0e3] px-10 py-4 text-sm font-bold uppercase tracking-widest text-white shadow-xl transition-all hover:scale-105 hover:bg-[#2b9bc9]">
                                VIEW WHAT WE SUPPLY
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3: Bangladesh's Maritime Experts Since 2012. --}}
            <div class="swiper-slide relative">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=2070&auto=format&fit=crop" alt="Warehouse" class="h-full w-full object-cover opacity-75">
                    <div class="absolute inset-0 bg-[#01223b]/55"></div>
                </div>
                <div class="relative flex h-full items-center justify-center text-center">
                    <div class="max-w-5xl px-4">
                        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white drop-shadow-2xl sm:text-6xl md:text-7xl">
                            Bangladesh's Maritime<br>Experts Since 2012.
                        </h1>
                        <p class="mt-6 text-base font-bold tracking-normal text-white drop-shadow-lg sm:text-lg md:text-xl">
                            Serving global fleets with reliability, speed &amp; full compliance.
                        </p>
                        <div class="mt-10">
                            <a href="{{ route('contact.create') }}" class="inline-block rounded bg-[#3eb0e3] px-10 py-4 text-sm font-bold uppercase tracking-widest text-white shadow-xl transition-all hover:scale-105 hover:bg-[#2b9bc9]">
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
@endphp

@if ($section->block_type === 'carousel' && $section->variant === 'simple')
@include('site.home-sections.carousel-simple', ['section' => $section, 'items' => $items])
@elseif ($section->block_type === 'carousel' && $section->variant === 'content_2')
@include('site.home-sections.carousel-content-2', ['section' => $section, 'items' => $items])
@elseif ($section->block_type === 'carousel' && $section->variant === 'news')
@include('site.home-sections.carousel-news', ['section' => $section, 'items' => $items])
@elseif ($section->block_type === 'two_column' && $section->two_column_mode === 'image_details')
@include('site.home-sections.two-column-about', ['section' => $section])
@elseif ($section->block_type === 'two_column' && $section->two_column_mode === 'both_sides_details')
@include('site.home-sections.two-column-mission-vision', ['section' => $section])
@endif
@endforeach

{{-- Video Showcase Section --}}
<section class="relative bg-white py-16 sm:py-24">
    <div class="site-container">
        <div class="mb-12 text-center">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">Maritime Excellence</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold text-slate-900 sm:text-5xl">Our Operations in Action</h2>
            <div class="mx-auto mt-4 h-1 w-20 bg-[#3eb0e3]"></div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-2xl">
            {{-- High-quality maritime stock video --}}
            <video
                class="h-[260px] w-full object-cover sm:h-[340px] md:h-[420px] lg:h-[480px]"
                autoplay
                muted
                loop
                playsinline
                poster="https://images.unsplash.com/photo-1559139225-30071e443546?q=80&w=2070&auto=format&fit=crop">
                <source src="https://assets.mixkit.co/videos/preview/mixkit-large-cargo-ship-in-the-middle-of-the-ocean-40012-large.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            {{-- Overlay for a premium feel --}}
            <div class="absolute inset-0 bg-linear-to-t from-slate-900/60 via-transparent to-transparent"></div>

            <div class="absolute bottom-0 left-0 p-8 text-white">
                <p class="text-lg font-medium opacity-90">Providing 24/7 reliability across all Bangladeshi ports.</p>
            </div>
        </div>
    </div>
</section>

{{-- Full Width Video Section --}}
<section class="relative h-[480px] w-full overflow-hidden sm:h-[560px] md:h-[620px]">
    {{-- Video Background --}}
    <video
        class="absolute inset-0 h-full w-full object-cover"
        autoplay
        muted
        loop
        playsinline
        poster="https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=2070&auto=format&fit=crop">
        <source src="https://assets.mixkit.co/videos/preview/mixkit-cargo-ship-leaving-the-port-40013-large.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    {{-- Cinematic Overlay --}}
    <div class="absolute inset-0 bg-slate-900/40"></div>

    {{-- Content Overlay --}}
    <div class="relative flex h-full items-center justify-center text-center">
        <div class="max-w-4xl px-4">
            <h2 class="font-sans text-3xl font-extrabold text-white drop-shadow-2xl sm:text-5xl md:text-6xl">
                Global Reach. Local Expertise.
            </h2>
            <p class="mt-6 text-lg font-medium text-white/90 drop-shadow-lg sm:text-xl md:text-2xl">
                Serving the world's leading fleets with unmatched precision since 2012.
            </p>
            <div class="mt-10">
                <a href="#" class="inline-block rounded-full bg-white px-8 py-3 text-sm font-bold uppercase tracking-widest text-slate-900 shadow-xl transition-all hover:scale-105 hover:bg-[#3eb0e3] hover:text-white">
                    Connect With Us
                </a>
            </div>
        </div>
    </div>
</section>

@include('site.home-sections.visual-frames', ['visualFrames' => $visualFrames ?? ['show' => false]])

{{-- Service Areas & Locations Section --}}
<section class="relative overflow-hidden bg-slate-900 py-16 sm:py-24">
    <!-- Background Texture -->
    <div class="absolute inset-0">
        <!-- Ocean surface background to match the design -->
        <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=2070&auto=format&fit=crop" class="h-full w-full object-cover opacity-20 mix-blend-overlay" alt="Ocean Texture">
    </div>

    <div class="relative z-10 site-container">
        <!-- Header -->
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">Service Areas</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold text-white sm:text-5xl">Locations</h2>
        </div>

        <!-- Map Area Placeholder -->
        <div class="relative mx-auto mt-12 flex h-[300px] w-full max-w-4xl items-center justify-center sm:h-[400px] lg:h-[500px]">
            <!-- User will replace this with their actual map graphic -->
            <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-4 h-12 w-12 text-white/50">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                </svg>
                <p class="font-medium text-white/70">Map graphic will be placed here</p>
                <p class="mt-2 text-sm text-white/40">Replace this placeholder with the Europe map image.</p>
            </div>
        </div>

        <!-- End to End Supply block -->
        <div class="mt-16 border-t border-white/10 pt-12 sm:mt-24 md:border-none md:pt-0">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-12">
                <h3 class="shrink-0 font-sans text-2xl font-bold text-white sm:text-3xl">End to end supply</h3>
                <div class="hidden h-12 w-px bg-white/30 md:block"></div>
                <div class="h-px w-16 bg-white/30 md:hidden"></div>
                <p class="text-base leading-relaxed text-slate-300 md:max-w-3xl">
                    We pride ourselves on our delivery and operate 365 days, 24 hours non-stop in all of the ports and straits of Turkey and the ARA area.
                </p>
            </div>
        </div>

        <!-- 4 Steps -->
        <div class="mt-16 grid grid-cols-2 gap-8 text-white sm:grid-cols-4 sm:gap-6 lg:gap-10">
            <div class="text-base font-medium leading-snug">Getting Order</div>
            <div class="text-base font-medium leading-snug">Preparing order and<br>packaging process</div>
            <div class="text-base font-medium leading-snug">Safe delivery service in<br>the refrigerated trucks</div>
            <div class="text-base font-medium leading-snug">On-time delivery</div>
        </div>
    </div>
</section>


{{-- Note: “Why Choose Us” is rendered via Home Sections now --}}

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