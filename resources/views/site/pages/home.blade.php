@extends('site.layouts.app', [
    'title' => config('app.name') . ' — Home',
    'metaDescription' => 'Maritime logistics, port operations, and trusted supply chain support.',
])

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .services-swiper, .supplies-swiper, .news-swiper {
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
        .news-swiper .swiper-slide > div {
            flex: 1;
        }
    </style>
@endpush

@section('content')
    {{-- Hero Carousel --}}
    <section class="relative h-[600px] w-full overflow-hidden bg-slate-900 md:h-[700px] lg:h-[800px]">
        <div class="swiper hero-swiper h-full w-full">
            <div class="swiper-wrapper">
                @if ($heroSlides->isNotEmpty())
                    @foreach ($heroSlides as $slide)
                        <div class="swiper-slide relative">
                            <div class="absolute inset-0">
                                @if ($slide->imagePublicUrl() !== '')
                                <img src="{{ $slide->imagePublicUrl() }}" alt="{{ $slide->title }}" class="h-full w-full object-cover opacity-75">
                                @else
                                <div class="h-full w-full bg-gradient-to-br from-slate-800 to-[#01223b]" role="img" aria-label="{{ $slide->title }}"></div>
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

    {{-- Our Services Section --}}
    <section id="services" class="bg-white py-16 sm:py-24">
        <div class="site-container">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">Our Services</h3>
                <h2 class="mt-2 font-sans text-4xl font-bold text-[#112a6d] sm:text-5xl">What We Do</h2>
            </div>
        </div>

        <div class="mt-10 site-container">
            <div class="swiper services-swiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="group relative flex h-[380px] w-full flex-col justify-between overflow-hidden rounded-xl bg-[#01223b] p-8 shadow-lg">
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="">
                            <div class="absolute inset-0 bg-[#01223b]/85 mix-blend-multiply"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-[#01223b]/90 via-[#01223b]/70 to-[#01223b]/90"></div>
                            
                            <div class="relative z-10 flex h-full flex-col">
                                <h4 class="text-2xl font-bold uppercase leading-snug text-white">LIFE RAFT MAINTENANCE</h4>
                                <div class="mt-4 flex-1"></div>
                                <a href="#" class="mt-auto font-bold text-white transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="group relative flex h-[380px] w-full flex-col justify-between overflow-hidden rounded-xl bg-[#01223b] p-8 shadow-lg">
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="">
                            <div class="absolute inset-0 bg-[#01223b]/85 mix-blend-multiply"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-[#01223b]/90 via-[#01223b]/70 to-[#01223b]/90"></div>
                            
                            <div class="relative z-10 flex h-full flex-col">
                                <h4 class="text-2xl font-bold uppercase leading-snug text-white">BUNKERING SERVICE</h4>
                                <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                    Reliable delivery of LSMGO (0.1%), VLSFO (0.5%), and HSFO via our owned barges. De-bunkering: Safe offloading of off-spec or contaminated...
                                </p>
                                <a href="#" class="mt-auto font-bold text-white transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="group relative flex h-[380px] w-full flex-col justify-between overflow-hidden rounded-xl bg-[#01223b] p-8 shadow-lg">
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="">
                            <div class="absolute inset-0 bg-[#01223b]/85 mix-blend-multiply"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-[#01223b]/90 via-[#01223b]/70 to-[#01223b]/90"></div>
                            
                            <div class="relative z-10 flex h-full flex-col">
                                <h4 class="text-2xl font-bold uppercase leading-snug text-white">WASTE DISPOSAL</h4>
                                <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                    Sludge & Garbage Discharge, De-bunkering, De-Slopping. ...
                                </p>
                                <a href="#" class="mt-auto font-bold text-white transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 (Duplicate for better sliding feel) -->
                    <div class="swiper-slide">
                        <div class="group relative flex h-[380px] w-full flex-col justify-between overflow-hidden rounded-xl bg-[#01223b] p-8 shadow-lg">
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="">
                            <div class="absolute inset-0 bg-[#01223b]/85 mix-blend-multiply"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-[#01223b]/90 via-[#01223b]/70 to-[#01223b]/90"></div>
                            
                            <div class="relative z-10 flex h-full flex-col">
                                <h4 class="text-2xl font-bold uppercase leading-snug text-white">FRESH WATER SUPPLY</h4>
                                <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                    High-quality fresh water delivery to vessels at berth or anchorage, ensuring the health and well-being of your crew.
                                </p>
                                <a href="#" class="mt-auto font-bold text-white transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Ship Supplies Section --}}
    <section id="supplies" class="bg-white pb-16 sm:pb-24">
        <div class="site-container">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">Ship Supplies</h3>
                <h2 class="mt-2 font-sans text-4xl font-bold text-[#112a6d] sm:text-5xl">What We Supply</h2>
            </div>

            <div class="mt-10 swiper supplies-swiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                            <!-- Icon -->
                            <div class="mb-6 text-[#3eb0e3]">
                                <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="50" cy="40" r="22" />
                                    <circle cx="50" cy="62" r="22" />
                                    <line x1="42" y1="84" x2="58" y2="84" />
                                    <line x1="50" y1="84" x2="50" y2="76" />
                                    <line x1="28" y1="62" x2="72" y2="62" opacity="0.3" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold uppercase text-[#112a6d]">Chains – Ropes –<br>Shackles</h4>
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                                Environmentally compliant collection and disposal of sludge, slop, and bilge water from vessels, fully certified and in accordance with MARPOL...
                            </p>
                            <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                            <div class="mb-6 text-[#3eb0e3]">
                                <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="38" y="38" width="24" height="36" rx="6" />
                                    <path d="M44 38 V28 h12 v10" />
                                    <rect x="47" y="24" width="6" height="4" />
                                    <path d="M50 24 C50 15, 65 15, 70 25" stroke-dasharray="3 3" opacity="0.7" />
                                    <line x1="30" y1="50" x2="38" y2="52" opacity="0.5" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold uppercase text-[#112a6d]">Safety Equipment</h4>
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                                Certified inspection, servicing, recharging, and supply of all types of marine fire extinguishers and fire-fighting equipment in compliance with international...
                            </p>
                            <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                            <div class="mb-6 text-[#3eb0e3]">
                                <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="50" cy="50" r="28" />
                                    <circle cx="50" cy="50" r="14" />
                                    <line x1="22" y1="50" x2="36" y2="50" />
                                    <line x1="78" y1="50" x2="64" y2="50" />
                                    <line x1="50" y1="22" x2="50" y2="36" />
                                    <line x1="50" y1="78" x2="50" y2="64" />
                                    <circle cx="50" cy="50" r="28" stroke-dasharray="6 6" opacity="0.3" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold uppercase text-[#112a6d]">Navigation Equipment</h4>
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                                Life-saving appliances and personal protective equipment including life jackets, immersion suits, life rings, fire suits, helmets, and all SOLAS-compliant safety...
                            </p>
                            <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                            <div class="mb-6 text-[#3eb0e3]">
                                <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 30 L80 30 L80 70 L20 70 Z" />
                                    <path d="M30 30 V20 H70 V30" />
                                    <circle cx="35" cy="50" r="5" />
                                    <circle cx="50" cy="50" r="5" />
                                    <circle cx="65" cy="50" r="5" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold uppercase text-[#112a6d]">Marine Spares</h4>
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                                Comprehensive range of genuine and OEM spare parts for main and auxiliary engines, pumps, compressors, and other critical machinery.
                            </p>
                            <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- About Us Section --}}
    <section id="about" class="bg-[#f6f8fa]">
        <div class="grid lg:grid-cols-2">
            <!-- Image Side -->
            <div class="relative h-[550px] w-full lg:h-[600px]">
                <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop" class="h-full w-full object-cover" alt="Warehouse">
            </div>
            
            <!-- Text Side -->
            <div class="flex flex-col justify-start pt-12 lg:pt-20 pb-12 px-8 sm:p-12 lg:p-16 xl:p-24">
                <div class="max-w-xl">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-[#3eb0e3]">About Us</h3>
                    <h2 class="mt-4 font-sans text-3xl font-bold leading-tight text-[#112a6d] sm:text-4xl lg:text-[2.6rem]">
                        Built on Trust.<br>Driven by Excellence.
                    </h2>
                    
                    <p class="mt-6 text-[0.95rem] leading-relaxed text-slate-600">
                        Founded in 2012, Newport Maritime Service has grown into one of Bangladesh’s most trusted maritime companies. Over more than a decade, we have earned a strong reputation as a dependable General Ship Supplier, Marine Spares Exporter, and Ship Repair Service provider — built on a consistent commitment to quality, efficiency, and client satisfaction.
                    </p>
                    
                    <p class="mt-4 text-[0.95rem] leading-relaxed text-slate-600">
                        Our global relationships reflect the trust the maritime industry places in us. We understand the demands of vessel operations firsthand, and we deliver comprehensive, tailored solutions designed to keep your fleet running smoothly.
                    </p>
                    
                    <a href="#" class="mt-10 inline-block rounded-sm bg-[#3eb0e3] px-10 py-4 text-xs font-bold uppercase tracking-widest text-white shadow-md transition-all hover:bg-[#2b9bc9] hover:shadow-lg">
                        LEARN MORE
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & Vision Section --}}
    <section class="bg-white py-16 sm:py-24">
        <div class="site-container">
            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Mission -->
                <div class="rounded-2xl border border-blue-100 bg-[#f4f7fe] p-8 sm:p-12 shadow-sm hover:shadow-md transition">
                    <h3 class="font-sans text-3xl font-bold text-[#112a6d]">Our Mission</h3>
                    <p class="mt-6 text-base leading-relaxed text-slate-600">
                        At Newport Maritime Service, our mission is to ensure uninterrupted vessel operations across Bangladeshi ports by delivering government-certified, round-the-clock marine solutions. From marine spares and ship supplies to waste management and technical services, we are committed to providing every client with unwavering reliability, competitive value, and full regulatory compliance — because we understand that at sea, there is no room for compromise.
                    </p>
                </div>

                <!-- Vision -->
                <div class="rounded-2xl border border-cyan-100 bg-[#f0f9fb] p-8 sm:p-12 shadow-sm hover:shadow-md transition">
                    <h3 class="font-sans text-3xl font-bold text-[#112a6d]">Our Vision</h3>
                    <p class="mt-6 text-base leading-relaxed text-slate-600">
                        Our vision is to redefine maritime support across South Asia by becoming the most trusted single-source partner for global fleets. We are building a future where operational excellence, environmental responsibility, and long-term client partnerships go hand in hand — driving sustainable growth and establishing Newport Maritime Service as a symbol of industry leadership for generations to come.
                    </p>
                </div>
            </div>
        </div>
    </section>

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

    {{-- Why Choose Us Section --}}
    <section class="bg-white py-16 sm:py-24">
        <div class="site-container">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <!-- Image Side -->
                <div class="relative h-[400px] w-full sm:h-[600px] lg:h-[750px]">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=1200&auto=format&fit=crop" class="absolute inset-0 h-full w-full rounded-3xl object-cover" alt="Cargo Ship">
                </div>
                
                <!-- Text Side -->
                <div class="flex flex-col justify-center py-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">Why Choose Us?</h3>
                    <h2 class="mt-4 font-sans text-4xl font-bold leading-tight text-[#112a6d] sm:text-5xl lg:text-[3.25rem]">
                        One partner.<br>Every need.<br>Zero compromise.
                    </h2>
                    
                    <div class="mt-10 space-y-5 text-slate-600">
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">Single-Window Service</span> Everything your vessel needs — supplied, repaired, and managed through one reliable partner.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">24/7 Availability</span> We're always on call. Day or night, your request gets an immediate response.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">Government Certified</span> Fully licensed and compliant with MARPOL, SOLAS, and all port authority regulations.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">4-Port Coverage</span> Chattogram · Mongla · Payra · Matarbari — we're where your fleet needs us.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">12+ Years Experience</span> Trusted by global fleets since 2012, with a proven track record of reliable service.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">Competitive Pricing</span> Premium quality at honest prices, with flexible payment terms that work for you.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">Internationally Recognized</span> IMPA member · MESPAS registered · ProcureShip listed · CCCI member.
                        </p>
                        <p class="text-base leading-relaxed">
                            <span class="font-medium text-slate-800">Guaranteed Quality Parts</span> Every spare — new or reconditioned — undergoes strict quality checks before delivery.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest News Section --}}
    <section class="bg-[#f4f5f7] py-16 sm:py-24">
        <div class="site-container">
            {{-- Section Header --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">The News</h3>
                <h2 class="mt-2 font-sans text-4xl font-bold text-[#112a6d] sm:text-5xl">Latest News</h2>
            </div>

            {{-- Swiper Carousel --}}
            <div class="swiper news-swiper mt-10">
                <div class="swiper-wrapper">

                    {{-- Slide 1 --}}
                    <div class="swiper-slide">
                        <div class="flex flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=800&auto=format&fit=crop" class="h-64 w-full rounded-xl object-cover" alt="Ship Repair">
                            <div class="mt-6 flex flex-1 flex-col">
                                <p class="text-sm font-medium text-slate-800">February 26, 2026</p>
                                <h4 class="mt-4 text-xl font-bold leading-snug text-[#112a6d]">Why 24/7 Ship Supply Matters: Keeping Your Vessel Ready at All Times</h4>
                                <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                    At sea, time is money — and unexpected delays at port can cost vessel operators thousands of dollars per hour....
                                </p>
                                <a href="#" class="mt-6 text-sm font-bold text-slate-900 transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="swiper-slide">
                        <div class="flex flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                            <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=800&auto=format&fit=crop" class="h-64 w-full rounded-xl object-cover" alt="Crew Welfare">
                            <div class="mt-6 flex flex-1 flex-col">
                                <p class="text-sm font-medium text-slate-800">February 26, 2026</p>
                                <h4 class="mt-4 text-xl font-bold leading-snug text-[#112a6d]">Crew Welfare at Sea: Why Provision Quality Is More Important Than You Think</h4>
                                <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                    A well-fed, comfortable crew is a productive crew. Yet provision quality is one of the most overlooked aspects of vessel...
                                </p>
                                <a href="#" class="mt-6 text-sm font-bold text-slate-900 transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="swiper-slide">
                        <div class="flex flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                            <img src="https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=800&auto=format&fit=crop" class="h-64 w-full rounded-xl object-cover" alt="Ship Repair Hub">
                            <div class="mt-6 flex flex-1 flex-col">
                                <p class="text-sm font-medium text-slate-800">February 26, 2026</p>
                                <h4 class="mt-4 text-xl font-bold leading-snug text-[#112a6d]">Ship Repair in Bangladesh: Why Chittagong Is Becoming a Global Maintenance Hub</h4>
                                <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                    Bangladesh has long been known as a global ship breaking destination — but increasingly, the country is earning a new...
                                </p>
                                <a href="#" class="mt-6 text-sm font-bold text-slate-900 transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 4 --}}
                    <div class="swiper-slide">
                        <div class="flex flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                            <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=800&auto=format&fit=crop" class="h-64 w-full rounded-xl object-cover" alt="Spare Parts Guide">
                            <div class="mt-6 flex flex-1 flex-col">
                                <p class="text-sm font-medium text-slate-800">February 26, 2026</p>
                                <h4 class="mt-4 text-xl font-bold leading-snug text-[#112a6d]">The Complete Guide to Marine Spare Parts: New, Reconditioned & What to Choose</h4>
                                <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                    One of the most common questions vessel operators ask us is: “Should I go for new parts or reconditioned?” It’s...
                                </p>
                                <a href="#" class="mt-6 text-sm font-bold text-slate-900 transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 5 --}}
                    <div class="swiper-slide">
                        <div class="flex flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                            <img src="https://images.unsplash.com/photo-1566984426547-6be8db51b5ca?q=80&w=800&auto=format&fit=crop" class="h-64 w-full rounded-xl object-cover" alt="MARPOL Compliance">
                            <div class="mt-6 flex flex-1 flex-col">
                                <p class="text-sm font-medium text-slate-800">February 26, 2026</p>
                                <h4 class="mt-4 text-xl font-bold leading-snug text-[#112a6d]">MARPOL Compliance at Bangladeshi Ports: What Every Ship Owner Needs to Know</h4>
                                <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                    Understanding MARPOL requirements is essential to avoiding costly delays and ensuring your vessel remains compliant...
                                </p>
                                <a href="#" class="mt-6 text-sm font-bold text-slate-900 transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>

                </div>{{-- /.swiper-wrapper --}}
            </div>{{-- /.news-swiper --}}
        </div>
    </section>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
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
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
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
                    640: { slidesPerView: 2, spaceBetween: 24 },
                    1024: { slidesPerView: 3, spaceBetween: 28 },
                }
            });
        });
    </script>
@endpush
@endsection
