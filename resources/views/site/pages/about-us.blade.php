@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
@php
    $hasHeroBg = \App\Models\AboutPage::imageSrc($about->hero_background ?? null) !== '';
    $hasHeroTitle = filled($about->hero_title ?? null);
    $hasHero = $hasHeroBg || $hasHeroTitle;
    $hasStat1 = filled($about->stat1_value ?? null) || filled($about->stat1_label ?? null);
    $hasStat2 = filled($about->stat2_value ?? null) || filled($about->stat2_label ?? null);
    $hasStat3 = filled($about->stat3_value ?? null) || filled($about->stat3_label ?? null);
    $hasStats = $hasStat1 || $hasStat2 || $hasStat3;
@endphp

@if ($hasHero)
<section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
    @if ($hasHeroBg)
        @include('site.partials.page-hero-media', [
            'imageUrl' => \App\Models\AboutPage::imageSrc($about->hero_background),
        ])
    @endif

    @if ($hasHeroTitle)
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $about->hero_title }}</h1>
            @include('site.partials.page-hero-crumbs', ['path' => '/about-us', 'current' => $about->hero_title])
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
@endsection
