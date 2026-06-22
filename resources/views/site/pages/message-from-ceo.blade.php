@php
    $ceo = $ceo ?? \App\Models\CeoMessagePage::resolvedForPublic();
@endphp

@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Message from the CEO'),
    'metaDescription' => $ceo->meta_description,
])

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        @include('site.partials.page-hero-media', ['imageUrl' => $ceo->hero_background_url])
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                &ldquo;{{ $ceo->hero_title }}&rdquo;
            </h1>
            @include('site.partials.page-hero-crumbs', ['path' => '/message-from-ceo', 'current' => 'Message from the CEO'])
        </div>
    </section>

    <div class="ceo-message">
    <section class="site-section bg-white">
        <div class="site-container">
            <div class="ceo-message__layout">
                <div class="ceo-message__content">
                    <header class="ceo-message__header">
                        @if (filled($ceo->eyebrow))
                            <p class="ceo-message__eyebrow">{{ $ceo->eyebrow }}</p>
                        @endif
                        @if (filled($ceo->salutation))
                            <h2 class="ceo-message__salutation">{{ $ceo->salutation }}</h2>
                        @endif
                    </header>

                    @if (count($ceo->paragraphs) > 0)
                        <div class="ceo-message__body">
                            @foreach ($ceo->paragraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (filled($ceo->signature_name) || filled($ceo->signature_role) || filled($ceo->linkedin_url) || filled($ceo->instagram_url))
                        <footer class="ceo-message__signature">
                            <div class="ceo-message__signature-text">
                                @if (filled($ceo->signature_name))
                                    <p class="ceo-message__name">{{ $ceo->signature_name }}</p>
                                @endif
                                @if (filled($ceo->signature_role))
                                    <p class="ceo-message__role">{{ $ceo->signature_role }}</p>
                                @endif
                            </div>
                            @if (filled($ceo->linkedin_url) || filled($ceo->instagram_url))
                                <span class="ceo-message__signature-divider" aria-hidden="true"></span>
                                <div class="ceo-message__social">
                                    @if (filled($ceo->linkedin_url))
                                        <a href="{{ $ceo->linkedin_url }}" class="ceo-message__social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 4.126 0 2.062 2.062 0 0 1-2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if (filled($ceo->instagram_url))
                                        <a href="{{ $ceo->instagram_url }}" class="ceo-message__social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </footer>
                    @endif
                </div>

                <aside class="ceo-message__aside">
                    <figure class="ceo-message__portrait">
                        <img
                            src="{{ $ceo->portrait_url }}"
                            alt="{{ $ceo->signature_name ?: 'CEO portrait' }}"
                            class="ceo-message__portrait-img"
                            loading="lazy"
                        >
                    </figure>
                </aside>
            </div>
        </div>
    </section>
    </div>
@endsection
