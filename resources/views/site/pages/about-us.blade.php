@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
@php($aboutVideo = \App\Models\AboutPage::videoModalPayload($about->cta_video_url ?? null))
{{-- 1. Hero --}}
<section class="relative flex h-[300px] w-full items-center overflow-hidden bg-slate-900 sm:h-[400px]">
    <div class="absolute inset-0">
        <img src="{{ \App\Models\AboutPage::imageSrc($about->hero_background) }}" class="h-full w-full object-cover opacity-60 mix-blend-overlay" alt="">
        <div class="absolute inset-0 bg-gradient-to-r from-[#071738]/90 via-[#071738]/60 to-transparent"></div>
    </div>

    <div class="relative z-10 site-container">
        <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $about->hero_title }}</h1>
        <div class="mt-4 flex items-center gap-3 text-sm font-medium sm:text-base">
            <a href="{{ route('home') }}" class="text-white transition hover:text-[#3eb0e3]">Home</a>
            <span class="text-[#3eb0e3]">{{ $about->hero_title }}</span>
        </div>
    </div>
</section>

{{-- 2. Trust --}}
<section class="bg-white py-16 lg:py-24">
    <div class="site-container">
        <div class="flex flex-col items-center gap-12 lg:flex-row">
            <div class="w-full lg:w-1/2">
                <div class="overflow-hidden rounded-3xl shadow-2xl">
                    <img src="{{ \App\Models\AboutPage::imageSrc($about->trust_image) }}" alt="" class="w-full object-cover transition-transform duration-500 hover:scale-105">
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <h2 class="font-sans text-3xl font-black leading-tight text-[#112a6d] sm:text-4xl">
                    {!! nl2br(e($about->trust_title)) !!}
                </h2>
                <div class="mt-8 text-base font-medium leading-relaxed text-slate-600">
                    {!! nl2br(e($about->trust_description)) !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. Key numbers --}}
<section class="bg-white pb-16 lg:pb-24">
    <div class="site-container">
        <div class="grid gap-6 sm:grid-cols-3">
            <div class="flex flex-col items-center justify-center rounded-2xl bg-[#f7f8f9] p-10 text-center transition hover:shadow-lg">
                <span class="text-5xl font-black tabular-nums text-[#112a6d] lg:text-6xl">{{ $about->stat1_value }}</span>
                <span class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-500">{{ $about->stat1_label }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded-2xl bg-[#f7f8f9] p-10 text-center transition hover:shadow-lg">
                <span class="text-5xl font-black tabular-nums text-[#112a6d] lg:text-6xl">{{ $about->stat2_value }}</span>
                <span class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-500">{{ $about->stat2_label }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded-2xl bg-[#f7f8f9] p-10 text-center transition hover:shadow-lg">
                <span class="text-5xl font-black tabular-nums text-[#112a6d] lg:text-6xl">{{ $about->stat3_value }}</span>
                <span class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-500">{{ $about->stat3_label }}</span>
            </div>
        </div>
    </div>
</section>

{{-- 4. Mission & vision --}}
<section class="bg-white pb-16 lg:pb-24">
    <div class="site-container">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-3xl bg-[#f0f9ff] p-10 lg:p-14">
                <h3 class="text-3xl font-black text-[#112a6d]">{{ $about->mission_title }}</h3>
                <p class="mt-6 text-base font-medium leading-relaxed text-slate-600">{{ $about->mission_body }}</p>
            </div>
            <div class="rounded-3xl bg-[#f0f9ff] p-10 lg:p-14">
                <h3 class="text-3xl font-black text-[#112a6d]">{{ $about->vision_title }}</h3>
                <p class="mt-6 text-base font-medium leading-relaxed text-slate-600">{{ $about->vision_body }}</p>
            </div>
        </div>
    </div>
</section>

{{-- 5. CTA / video --}}
<section class="relative min-h-[500px] w-full overflow-hidden bg-slate-900 py-24 lg:py-32">
    <div class="absolute inset-0">
        <img src="{{ \App\Models\AboutPage::imageSrc($about->cta_background) }}" alt="" class="h-full w-full object-cover opacity-40">
        <div class="absolute inset-0 bg-[#071738]/60"></div>
    </div>

    <div class="relative z-10 site-container flex flex-col justify-center">
        <span class="text-sm font-bold uppercase tracking-[0.2em] text-[#3eb0e3]">{{ $about->cta_eyebrow }}</span>
        <h2 class="mt-6 max-w-4xl font-sans text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
            {{ $about->cta_heading }}
        </h2>

        <div class="mt-12">
            @if ($aboutVideo['type'] !== 'none')
                <button type="button" id="about-open-video" class="group inline-flex items-center gap-4 text-white transition hover:opacity-95" data-embed="{{ e($aboutVideo['embed_url']) }}">
                    <span class="flex h-20 w-20 items-center justify-center rounded-full bg-white text-[#3eb0e3] shadow-xl transition-transform group-hover:scale-110">
                        <svg class="h-8 w-8 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-wider">{{ $about->cta_button_label }}</span>
                </button>
            @else
                <button type="button" class="group inline-flex cursor-default items-center gap-4 text-white">
                    <span class="flex h-20 w-20 items-center justify-center rounded-full bg-white text-[#3eb0e3] shadow-xl">
                        <svg class="h-8 w-8 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-wider">{{ $about->cta_button_label }}</span>
                </button>
            @endif
        </div>
    </div>
</section>

{{-- Video lightbox: capped width (max-w-4xl), not full-bleed --}}
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
@endsection
