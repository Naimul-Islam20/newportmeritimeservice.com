@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
@php
    $aboutVideo = \App\Models\AboutPage::videoModalPayload($about->cta_video_url ?? null);
    $hasHeroBg = filled($about->hero_background ?? null);
    $hasHeroTitle = filled($about->hero_title ?? null);
    $hasHero = $hasHeroBg || $hasHeroTitle;
    $hasTrustImage = filled($about->trust_image ?? null);
    $hasTrustTitle = filled($about->trust_title ?? null);
    $hasTrustDesc = filled($about->trust_description ?? null);
    $hasTrust = $hasTrustImage || $hasTrustTitle || $hasTrustDesc;
    $hasStat1 = filled($about->stat1_value ?? null) || filled($about->stat1_label ?? null);
    $hasStat2 = filled($about->stat2_value ?? null) || filled($about->stat2_label ?? null);
    $hasStat3 = filled($about->stat3_value ?? null) || filled($about->stat3_label ?? null);
    $hasStats = $hasStat1 || $hasStat2 || $hasStat3;
    $hasMission = filled($about->mission_title ?? null) || filled($about->mission_body ?? null);
    $hasVision = filled($about->vision_title ?? null) || filled($about->vision_body ?? null);
    $hasMissionVision = $hasMission || $hasVision;
    $hasCtaBg = filled($about->cta_background ?? null);
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

@if ($hasTrust)
<section class="bg-white site-section">
    <div class="site-container">
        <div @class([
            'flex flex-col items-center gap-12',
            'lg:flex-row' => $hasTrustImage,
        ])>
            @if ($hasTrustImage)
                <div class="w-full lg:w-1/2">
                    <div class="overflow-hidden rounded-3xl shadow-2xl">
                        <img src="{{ \App\Models\AboutPage::imageSrc($about->trust_image) }}" alt="" class="w-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                </div>
            @endif
            @if ($hasTrustTitle || $hasTrustDesc)
                <div @class(['w-full', 'lg:w-1/2' => $hasTrustImage])>
                    @if ($hasTrustTitle)
                        <h2 class="font-sans text-3xl font-black leading-tight text-secondary sm:text-4xl">
                            {!! nl2br(e($about->trust_title)) !!}
                        </h2>
                    @endif
                    @if ($hasTrustDesc)
                        <div @class(['text-base font-medium leading-relaxed text-foreground/70', 'mt-8' => $hasTrustTitle])>
                            {!! nl2br(e($about->trust_description)) !!}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endif

@if ($hasStats)
<section class="bg-white pb-16 lg:pb-24">
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

@if ($hasMissionVision)
<section class="bg-white pb-16 lg:pb-24">
    <div class="site-container">
        <div class="grid gap-8 lg:grid-cols-2">
            @if ($hasMission)
                <div class="rounded-3xl bg-primary/10 p-10 lg:p-14">
                    @if (filled($about->mission_title ?? null))
                        <h3 class="text-3xl font-black text-secondary">{{ $about->mission_title }}</h3>
                    @endif
                    @if (filled($about->mission_body ?? null))
                        <p @class(['text-base font-medium leading-relaxed text-foreground/70', 'mt-6' => filled($about->mission_title ?? null)])>{{ $about->mission_body }}</p>
                    @endif
                </div>
            @endif
            @if ($hasVision)
                <div class="rounded-3xl bg-primary/10 p-10 lg:p-14">
                    @if (filled($about->vision_title ?? null))
                        <h3 class="text-3xl font-black text-secondary">{{ $about->vision_title }}</h3>
                    @endif
                    @if (filled($about->vision_body ?? null))
                        <p @class(['text-base font-medium leading-relaxed text-foreground/70', 'mt-6' => filled($about->vision_title ?? null)])>{{ $about->vision_body }}</p>
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

        @if ($aboutVideo['type'] !== 'none' || filled($about->cta_button_label ?? null))
            <div class="mt-12">
                @if ($aboutVideo['type'] !== 'none')
                    <button type="button" id="about-open-video" class="group inline-flex items-center gap-4 text-white transition hover:opacity-95" data-embed="{{ e($aboutVideo['embed_url']) }}">
                        <span class="flex h-20 w-20 items-center justify-center rounded-full bg-white text-primary shadow-xl transition-transform group-hover:scale-110">
                            <svg class="h-8 w-8 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </span>
                        @if (filled($about->cta_button_label ?? null))
                            <span class="text-lg font-bold tracking-wider">{{ $about->cta_button_label }}</span>
                        @endif
                    </button>
                @elseif (filled($about->cta_button_label ?? null))
                    <span class="inline-flex items-center gap-4 text-lg font-bold tracking-wider text-white">{{ $about->cta_button_label }}</span>
                @endif
            </div>
        @endif
    </div>
</section>
@endif

@if ($aboutVideo['type'] !== 'none')
<div id="about-video-modal" class="fixed inset-0 z-[300] hidden items-center justify-center bg-black/85 p-4 sm:p-6" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="relative w-full max-w-4xl">
        <button type="button" id="about-video-close" class="absolute -right-1 -top-12 z-10 flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-2xl font-light text-white transition hover:bg-white/20 sm:-right-2 sm:-top-2 sm:bg-black/50" aria-label="Close video">&times;</button>
        <div class="overflow-hidden rounded-xl bg-black shadow-2xl ring-1 ring-white/15">
            <div class="aspect-video w-full bg-black">
                <iframe id="about-modal-iframe" class="h-full w-full" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const openBtn = document.getElementById('about-open-video');
        const modal = document.getElementById('about-video-modal');
        const closeBtn = document.getElementById('about-video-close');
        const iframeEl = document.getElementById('about-modal-iframe');
        if (!openBtn || !modal || !closeBtn || !iframeEl) return;

        const closeModal = () => {
            iframeEl.src = '';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        const openModal = () => {
            const embed = openBtn.getAttribute('data-embed') || '';
            if (!embed) return;
            iframeEl.src = embed;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('flex')) closeModal();
        });
    })();
</script>
@endif
@endsection
