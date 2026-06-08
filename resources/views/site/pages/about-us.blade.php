@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
@php
    $aboutVideo = \App\Models\AboutPage::videoModalPayload($about->cta_video_url ?? null);
    $hasHeroBg = \App\Models\AboutPage::imageSrc($about->hero_background ?? null) !== '';
    $hasHeroTitle = filled($about->hero_title ?? null);
    $hasHero = $hasHeroBg || $hasHeroTitle;
    $hasStat1 = filled($about->stat1_value ?? null) || filled($about->stat1_label ?? null);
    $hasStat2 = filled($about->stat2_value ?? null) || filled($about->stat2_label ?? null);
    $hasStat3 = filled($about->stat3_value ?? null) || filled($about->stat3_label ?? null);
    $hasStats = $hasStat1 || $hasStat2 || $hasStat3;
    $hasCtaBg = \App\Models\AboutPage::imageSrc($about->cta_background ?? null) !== '';
    $hasCtaText = filled($about->cta_eyebrow ?? null) || filled($about->cta_heading ?? null) || filled($about->cta_button_label ?? null);
    $hasCta = $hasCtaBg || $hasCtaText || $aboutVideo['type'] !== 'none';
@endphp

@if ($hasHero)
<section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
    @if ($hasHeroBg)
        <div class="absolute inset-0">
            <img src="{{ \App\Models\AboutPage::imageSrc($about->hero_background) }}" class="h-full w-full object-cover opacity-60 mix-blend-overlay" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
    @endif

    @if ($hasHeroTitle)
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $about->hero_title }}</h1>
            <div class="mt-4 flex items-center gap-3 text-sm font-medium sm:text-base">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary">{{ $about->hero_title }}</span>
            </div>
        </div>
    @endif
</section>
@endif

@if ($hasStats)
<section id="about-continue" class="bg-white pb-16 lg:pb-24">
    <div class="site-container">
        <div class="grid gap-6 sm:grid-cols-3">
            @if ($hasStat1)
                <div class="flex flex-col items-center justify-center rounded-2xl bg-secondary/5 p-10 text-center transition hover:shadow-lg">
                    @if (filled($about->stat1_value ?? null))
                        <span class="text-5xl font-black tabular-nums text-secondary lg:text-6xl">{{ $about->stat1_value }}</span>
                    @endif
                    @if (filled($about->stat1_label ?? null))
                        <span @class(['text-xs font-bold uppercase tracking-widest text-foreground/60', 'mt-4' => filled($about->stat1_value ?? null)])>{{ $about->stat1_label }}</span>
                    @endif
                </div>
            @endif
            @if ($hasStat2)
                <div class="flex flex-col items-center justify-center rounded-2xl bg-secondary/5 p-10 text-center transition hover:shadow-lg">
                    @if (filled($about->stat2_value ?? null))
                        <span class="text-5xl font-black tabular-nums text-secondary lg:text-6xl">{{ $about->stat2_value }}</span>
                    @endif
                    @if (filled($about->stat2_label ?? null))
                        <span @class(['text-xs font-bold uppercase tracking-widest text-foreground/60', 'mt-4' => filled($about->stat2_value ?? null)])>{{ $about->stat2_label }}</span>
                    @endif
                </div>
            @endif
            @if ($hasStat3)
                <div class="flex flex-col items-center justify-center rounded-2xl bg-secondary/5 p-10 text-center transition hover:shadow-lg">
                    @if (filled($about->stat3_value ?? null))
                        <span class="text-5xl font-black tabular-nums text-secondary lg:text-6xl">{{ $about->stat3_value }}</span>
                    @endif
                    @if (filled($about->stat3_label ?? null))
                        <span @class(['text-xs font-bold uppercase tracking-widest text-foreground/60', 'mt-4' => filled($about->stat3_value ?? null)])>{{ $about->stat3_label }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endif

@include('site.partials.page-sections-loop', ['pageSections' => $pageSections ?? []])

@if ($hasCta)
<section class="relative min-h-[500px] w-full overflow-hidden bg-secondary py-24 lg:py-32">
    @if ($hasCtaBg)
        <div class="absolute inset-0">
            <img src="{{ \App\Models\AboutPage::imageSrc($about->cta_background) }}" alt="" class="h-full w-full object-cover opacity-40">
            <div class="absolute inset-0 bg-secondary/60"></div>
        </div>
    @endif

    <div class="relative z-10 site-container flex flex-col justify-center">
        @if (filled($about->cta_eyebrow ?? null))
            <span class="text-sm font-bold uppercase tracking-[0.2em] text-primary">{{ $about->cta_eyebrow }}</span>
        @endif
        @if (filled($about->cta_heading ?? null))
            <h2 @class(['max-w-4xl font-sans text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl', 'mt-6' => filled($about->cta_eyebrow ?? null)])>
                {{ $about->cta_heading }}
            </h2>
        @endif

        @if (filled($about->cta_button_label ?? null))
            <div class="mt-12">
                <span class="inline-flex items-center gap-4 text-lg font-bold tracking-wider text-white">{{ $about->cta_button_label }}</span>
            </div>
        @endif
    </div>
</section>
@endif
@endsection
