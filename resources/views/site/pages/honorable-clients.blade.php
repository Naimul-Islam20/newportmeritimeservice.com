@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    @php
        $heroTitle = $page->hero_title ?: 'Honorable Clients';
    @endphp

    <section class="honorable-clients-hero relative min-h-[300px] w-full overflow-hidden bg-secondary sm:min-h-[400px]">
        @include('site.partials.page-hero-media', [
            'imageUrl' => $page->resolvedHeroBackgroundUrl(),
        ])
        <div class="honorable-clients-hero__content site-container relative z-10">
            <p class="honorable-clients-hero__eyebrow">Award</p>
            <h1 class="honorable-clients-hero__title">{{ $heroTitle }}</h1>
            <nav class="honorable-clients-hero__crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('award') }}">Award</a>
                <span aria-hidden="true">/</span>
                <span>{{ $heroTitle }}</span>
            </nav>
        </div>
    </section>

    <section class="honorable-clients site-section bg-white">
        <div class="site-container honorable-clients__container">
            @if (filled($page->page_intro))
                <header class="honorable-clients__intro">
                    <p>{{ $page->page_intro }}</p>
                </header>
            @endif

            @if ($clients->total() > 0)
                <div class="honorable-clients__stats" aria-hidden="true">
                    <div class="honorable-clients__stat">
                        <span class="honorable-clients__stat-label">Trusted partners</span>
                    </div>
                </div>

                <ul class="honorable-clients__grid" role="list">
                    @foreach ($clients as $client)
                        <li class="honorable-clients__item" role="listitem">
                            <article class="honorable-clients__card">
                                <div class="honorable-clients__logo-wrap">
                                    @if ($client->hasLogo())
                                        <img
                                            src="{{ $client->logoPublicUrl() }}"
                                            alt="{{ $client->name }} logo"
                                            class="honorable-clients__logo"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    @else
                                        <div class="honorable-clients__logo-placeholder" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15l-.75 18H5.25L4.5 3zm4.5 0v9m6-9v9" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <h2 class="honorable-clients__name">{{ $client->name }}</h2>
                            </article>
                        </li>
                    @endforeach
                </ul>

                @if ($clients->hasPages())
                    <div class="honorable-clients__pagination">
                        {{ $clients->links('vendor.pagination.site') }}
                    </div>
                @endif
            @else
                <p class="honorable-clients__empty">Our honorable clients will be published here soon.</p>
            @endif
        </div>
    </section>
@endsection
