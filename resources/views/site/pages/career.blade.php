@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($career->hero_title),
    'metaDescription' => $career->meta_description,
])

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        @include('site.partials.page-hero-media', ['imageUrl' => $career->hero_background_url])
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $career->hero_title }}</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">{{ $career->hero_title }}</span>
            </nav>
        </div>
    </section>

    <div class="career-page">
    <section class="site-section bg-white">
        <div class="site-container">
            <div class="career-page__grid">
                <div class="career-page__content">
                    <p class="career-page__eyebrow">{{ $career->eyebrow }}</p>
                    <h2 class="career-page__title">{{ $career->section_title }}</h2>

                    <div class="career-page__intro">
                        @foreach ($career->intro_paragraphs as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <div class="career-page__application">
                        <h3 class="career-page__subtitle">{{ $career->application_title }}</h3>
                        <p class="career-page__lead">{{ $career->application_lead }}</p>

                        <ul class="career-page__checklist">
                            @foreach ($career->qualifications as $item)
                                <li class="career-page__checklist-item">
                                    <span class="career-page__check" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12.5 9.5 17 19 7"/>
                                        </svg>
                                    </span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <p class="career-page__note">
                            {!! nl2br(e($career->application_note)) !!}<br>
                            {{ $career->hr_email }}
                        </p>

                        <div class="career-page__actions">
                            @if (filled($career->hr_email) && filled($career->mail_button_label))
                                <a href="mailto:{{ $career->hr_email }}" class="career-page__btn">{{ $career->mail_button_label }}</a>
                            @endif
                            @if (filled($career->kariyer_url) && filled($career->kariyer_button_label))
                                <a href="{{ $career->kariyer_url }}" class="career-page__btn" target="_blank" rel="noopener noreferrer">{{ $career->kariyer_button_label }}</a>
                            @endif
                            @if (filled($career->linkedin_url) && filled($career->linkedin_button_label))
                            <a href="{{ $career->linkedin_url }}" class="career-page__btn career-page__btn--linkedin" target="_blank" rel="noopener noreferrer">
                                <span class="career-page__btn-label">{{ $career->linkedin_button_label }}</span>
                                <span class="career-page__btn-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 4.126 0 2.062 2.062 0 0 1-2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <aside class="career-page__aside">
                    @if (filled($career->team_button_label))
                        <a href="{{ $career->team_button_href }}" class="career-page__team-btn">{{ $career->team_button_label }}</a>
                    @endif
                    <figure class="career-page__aside-figure">
                        <img
                            src="{{ $career->aside_image_url }}"
                            alt="{{ $career->aside_image_alt }}"
                            class="career-page__aside-img"
                            loading="lazy"
                        >
                    </figure>
                </aside>
            </div>
        </div>
    </section>

    <section class="career-page__offers site-section bg-white" aria-labelledby="career-offers-title">
        <div class="site-container">
            <header class="career-page__offers-header">
                <p class="career-page__offers-eyebrow">{{ $career->offers_eyebrow }}</p>
                <h2 id="career-offers-title" class="career-page__offers-title">{{ $career->offers_title }}</h2>
            </header>

            <article class="career-page__offers-card bg-[#f3f5f7]">
                <h3 class="career-page__offers-subtitle">{{ $career->offers_card_title }}</h3>
                <div class="career-page__offers-body">
                    @foreach ($career->offers_paragraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
                @if (filled($career->bottom_cta_label))
                    <a
                        href="{{ $career->bottom_cta_href }}"
                        class="career-page__btn career-page__offers-cta inline-flex min-h-10 items-center justify-center bg-[#46b5e5] px-5 text-xs font-bold tracking-wide text-white uppercase no-underline transition-colors hover:bg-[#3aa8db]"
                    >{{ $career->bottom_cta_label }}</a>
                @endif
            </article>
        </div>
    </section>
    </div>
@endsection
