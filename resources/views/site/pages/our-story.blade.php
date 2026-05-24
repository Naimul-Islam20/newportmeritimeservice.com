@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Our Story'),
    'metaDescription' => 'Our story since 1992 — maritime supply, provision and logistics across ports worldwide.',
])

@php
    $milestones = [
        [
            'year' => '2020',
            'title' => 'Mersin (new warehouse)',
            'text' => 'A new warehouse very close to the Port of Mersin was opened in 2020. This has a 2000 m² capacity in order to service your needs efficiently.',
            'image' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=900&auto=format&fit=crop',
        ],
        [
            'year' => '2016',
            'title' => 'Rotterdam',
            'text' => 'We crowned decades of experience in ship supply with our Rotterdam operations centre.',
            'image' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=900&auto=format&fit=crop',
        ],
        [
            'year' => '2015',
            'title' => 'Mersin',
            'text' => 'Our services were rewarded by our customers and requests increased. We invested in an office, warehouse and entrepôt in 2015 to speed up services to vessels calling ports in the Mediterranean area.',
            'image' => null,
        ],
        [
            'year' => '2014',
            'title' => 'Athens',
            'text' => 'Our Athens marketing office has continued to serve the shipping industry with the support of our partners since 2014.',
            'image' => null,
        ],
        [
            'year' => '2005',
            'title' => 'Tuzla',
            'text' => 'In order to maintain and improve our services, we opened our Tuzla branch office close to the shipyards of this area.',
            'image' => null,
        ],
        [
            'year' => '1992',
            'title' => 'Istanbul',
            'text' => 'Our company was founded in 1992 with a commitment to honest service and quality supply for every vessel we support.',
            'image' => 'https://images.unsplash.com/photo-1494412574643-6529190d0eb9?q=80&w=900&auto=format&fit=crop',
        ],
    ];
@endphp

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">Our Story</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Our Story</span>
            </nav>
        </div>
    </section>

    <section class="our-story site-section bg-white">
        <div class="site-container our-story__container">
            <header class="our-story__header">
                <p class="our-story__eyebrow">Our Story</p>
                <h2 class="our-story__title">Since 1992</h2>
                <div class="our-story__intro">
                    <p>
                        Since the beginning of the story, many things have changed… Our experiences on this way have carried us to be a pioneer company that can provide all kinds of requirements of our clients.
                    </p>
                    <p>
                        Today, we are operating in three different countries and hundreds of ports with the same enthusiasm and motivation like the first day.
                    </p>
                    <p>
                        With our intense efforts, dynamic professional staff and primary principles of honesty and service quality, we are happy to be a part of maritime industry also in the future.
                    </p>
                </div>
            </header>

            <div class="our-story__timeline" role="list">
                <div class="our-story__timeline-rail" aria-hidden="true"></div>
                @foreach ($milestones as $milestone)
                    <article class="our-story__milestone" role="listitem">
                        <div class="our-story__milestone-year">
                            <span class="our-story__year-label">{{ $milestone['year'] }} -</span>
                        </div>
                        <div class="our-story__milestone-body">
                            @if (! empty($milestone['image']))
                                <figure class="our-story__milestone-figure">
                                    <img
                                        src="{{ $milestone['image'] }}"
                                        alt=""
                                        class="our-story__milestone-img"
                                        loading="lazy"
                                    >
                                </figure>
                            @endif
                            <h3 class="our-story__milestone-title">{{ $milestone['title'] }}</h3>
                            <p class="our-story__milestone-text">{{ $milestone['text'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
