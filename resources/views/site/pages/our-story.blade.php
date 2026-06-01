@php
    $story = $story ?? \App\Models\OurStoryPage::resolvedForPublic();
@endphp

@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($story->hero_title),
    'metaDescription' => $story->meta_description,
])

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="{{ $story->hero_background_url }}"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $story->hero_title }}</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">{{ $story->hero_title }}</span>
            </nav>
        </div>
    </section>

    <section class="our-story site-section bg-white">
        <div class="site-container our-story__container">
            @if (filled($story->eyebrow) || filled($story->section_title) || count($story->intro_paragraphs) > 0)
                <header class="our-story__header">
                    @if (filled($story->eyebrow))
                        <p class="our-story__eyebrow">{{ $story->eyebrow }}</p>
                    @endif
                    @if (filled($story->section_title))
                        <h2 class="our-story__title">{{ $story->section_title }}</h2>
                    @endif
                    @if (count($story->intro_paragraphs) > 0)
                        <div class="our-story__intro">
                            @foreach ($story->intro_paragraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    @endif
                </header>
            @endif

            @if (count($story->milestones) > 0)
                <div class="our-story__timeline" role="list">
                    <div class="our-story__timeline-rail" aria-hidden="true"></div>
                    @foreach ($story->milestones as $milestone)
                        <article class="our-story__milestone" role="listitem">
                            <div class="our-story__milestone-year">
                                <span class="our-story__year-label">{{ $milestone['year'] }} -</span>
                            </div>
                            <div class="our-story__milestone-body">
                                <figure class="our-story__milestone-figure">
                                    <img
                                        src="{{ $milestone['image_url'] }}"
                                        alt="{{ $milestone['title'] }}"
                                        class="our-story__milestone-img"
                                        loading="lazy"
                                    >
                                </figure>
                                <div class="our-story__milestone-copy">
                                    @if (filled($milestone['title']))
                                        <h3 class="our-story__milestone-title">{{ $milestone['title'] }}</h3>
                                    @endif
                                    @if (filled($milestone['text']))
                                        <p class="our-story__milestone-text">{{ $milestone['text'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
