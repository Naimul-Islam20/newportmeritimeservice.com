@extends('site.layouts.app')

@section('content')
    @php
        $heroBg = $page->heroBackgroundUrl();
        $defaultHero = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';
    @endphp

    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="{{ $heroBg !== '' ? $heroBg : $defaultHero }}"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $heroTitle }}</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">{{ $heroTitle }}</span>
            </nav>
        </div>
    </section>

    <section class="quality-certs site-section bg-white">
        <div class="site-container quality-certs__container">
            <header class="quality-certs__page-header">
                <h2 class="quality-certs__page-title">{{ $pageIntro }}</h2>
            </header>

            @foreach ($groups as $group)
                @if ($group->show_divider_before)
                    <hr class="quality-certs__divider" aria-hidden="true">
                @endif

                <section id="{{ $group->slug }}" class="quality-certs__group">
                    <h3 class="quality-certs__group-title">{{ $group->title }}</h3>
                    @if (filled($group->intro))
                        <p class="quality-certs__group-intro">{{ $group->intro }}</p>
                    @endif

                    <div class="quality-certs__grid" role="list">
                        @foreach ($group->activeCertificates as $cert)
                            @include('site.partials.quality-certificate-item', [
                                'cert' => $cert,
                                'variant' => $group->isGridLayout() ? 'grid' : 'stack',
                            ])
                        @endforeach
                    </div>
                </section>
            @endforeach

            @if ($groups->isEmpty())
                <p class="quality-certs__empty">Certificates will be published here soon.</p>
            @endif
        </div>
    </section>
@endsection
