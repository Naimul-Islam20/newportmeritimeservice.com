@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Where We Are'),
    'metaDescription' => 'Our service areas and locations across the region.',
])

@section('content')
    {{-- Page Header --}}
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <!-- Background Image -->
        @include('site.partials.page-hero-media', [
            'imageUrl' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2000&auto=format&fit=crop',
            'imageAlt' => 'Port Background',
        ])

        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold text-white sm:text-5xl lg:text-6xl tracking-tight">Where We Are</h1>
            @include('site.partials.page-hero-crumbs', ['path' => '/where-we-are', 'current' => 'Where We Are'])
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="bg-white site-section">
        <div class="site-container">
            <div class="flex flex-col items-start gap-12 lg:flex-row">
                <!-- Left Image -->
                <div class="w-full lg:w-5/12">
                    <div class="overflow-hidden rounded-3xl shadow-xl">
                        <img src="https://images.unsplash.com/photo-1580674684081-7617fbf3d745?q=80&w=1000&auto=format&fit=crop" alt="Warehouse Chittagong" class="w-full object-cover">
                    </div>
                </div>

                <!-- Right Content -->
                <div class="w-full lg:w-7/12">
                    <h2 class="font-sans text-4xl font-extrabold uppercase tracking-tight text-secondary">WHERE WE ARE</h2>
                    
                    <div class="mt-8 space-y-8">
                        <!-- Location Info -->
                        <div>
                            <h4 class="text-lg font-bold text-[#334155]">Our Location</h4>
                            <p class="mt-4 text-[17px] font-normal leading-relaxed text-[#475569]">
                                Newport Maritime Service is strategically located in the heart of Chittagong, Bangladesh’s premier port city — giving us direct access to one of South Asia’s busiest maritime hubs.
                            </p>
                        </div>

                        <hr class="border-foreground/15">

                        <!-- Address Info -->
                        <div>
                            <h4 class="text-lg font-bold text-[#334155]">Office Address</h4>
                            <p class="mt-4 text-[17px] font-normal leading-relaxed text-[#475569]">
                                Mabud Chy. Center, 5th Floor, Nur Meah Lane, #3 No. Fakirhat, Bandar, Chittagong– 4100, Chittagong, Bangladesh.
                            </p>
                        </div>

                        <hr class="border-foreground/15">

                        <!-- Contact Info -->
                        <div>
                            <h4 class="mb-6 text-lg font-bold text-[#334155]">Get In Touch</h4>
                            <div class="space-y-6">
                                <!-- Phone row -->
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-4 text-[17px] font-medium text-[#475569]">
                                    <a href="tel:+8801321286667" class="flex items-center gap-2.5 transition hover:text-primary">
                                        <svg class="h-5 w-5 text-[#d946ef]" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79a15.15 15.15 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27 11.72 11.72 0 003.7.59 1 1 0 011 1V20a1 1 0 01-1 1A16 16 0 013 5a1 1 0 011-1h3.41a1 1 0 011 1 11.72 11.72 0 00.59 3.7 1 1 0 01-.27 1.11z"/></svg>
                                        +880 1321 28 66 67
                                    </a>
                                    <a href="tel:+8801977434272" class="flex items-center gap-2.5 transition hover:text-primary">
                                        <svg class="h-5 w-5 text-[#d946ef]" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79a15.15 15.15 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27 11.72 11.72 0 003.7.59 1 1 0 011 1V20a1 1 0 01-1 1A16 16 0 013 5a1 1 0 011-1h3.41a1 1 0 011 1 11.72 11.72 0 00.59 3.7 1 1 0 01-.27 1.11z"/></svg>
                                        +880 1977434272
                                    </a>
                                    <a href="tel:+88031724728" class="flex items-center gap-2.5 transition hover:text-primary">
                                        <svg class="h-5 w-5 text-[#d946ef]" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79a15.15 15.15 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27 11.72 11.72 0 003.7.59 1 1 0 011 1V20a1 1 0 01-1 1A16 16 0 013 5a1 1 0 011-1h3.41a1 1 0 011 1 11.72 11.72 0 00.59 3.7 1 1 0 01-.27 1.11z"/></svg>
                                        +880-31-724728
                                    </a>
                                </div>
                                <!-- Email list -->
                                <div class="space-y-4 text-[17px] font-medium">
                                    <a href="mailto:supply@newportmaritimeservice.com" class="flex items-center gap-3 text-primary transition hover:text-primary">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-sm bg-[#93c5fd] text-[10px] font-bold text-[#1e1e6d]">@</div>
                                        supply@newportmaritimeservice.com
                                    </a>
                                    <a href="mailto:newportmaritimeservice@gmail.com" class="flex items-center gap-3 text-primary transition hover:text-primary">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-sm bg-[#93c5fd] text-[10px] font-bold text-[#1e1e6d]">@</div>
                                        newportmaritimeservice@gmail.com
                                    </a>
                                    <a href="mailto:tech@newportmaritimeservice.com" class="flex items-center gap-3 text-primary transition hover:text-primary">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-sm bg-[#93c5fd] text-[10px] font-bold text-[#1e1e6d]">@</div>
                                        tech@newportmaritimeservice.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if (! empty($locations))
                            <hr class="border-foreground/15">
                            <div>
                                <h4 class="text-lg font-bold text-[#334155]">Our offices</h4>
                                <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                                    @foreach ($locations as $loc)
                                        <li>
                                            <a href="{{ route('where-we-are.location', $loc->slug) }}" class="text-[17px] font-medium text-primary transition hover:underline">
                                                {{ $loc->hero_title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr class="border-foreground/15">

                        <!-- Why Chittagong -->
                        <div>
                            <h4 class="text-lg font-bold text-[#334155]">Why Chittagong?</h4>
                            <p class="mt-4 text-[17px] font-normal leading-relaxed text-[#475569]">
                                Chittagong Port is the lifeline of Bangladesh’s maritime trade. Our central location allows us to respond swiftly to vessel requirements, ensuring minimum downtime and maximum efficiency for our clients.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
