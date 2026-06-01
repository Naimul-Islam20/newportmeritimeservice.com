@php
    $team = $team ?? \App\Models\OurTeamPage::resolvedForPublic();
    $navHref = static function (string $url): string {
        $url = trim($url);
        if ($url === '' || $url === '#') {
            return '#';
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return str_starts_with($url, '/') ? $url : '/'.$url;
    };
@endphp

@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($team->hero_title),
    'metaDescription' => $team->meta_description,
])

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="{{ $team->hero_background_url }}"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $team->hero_title }}</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <a href="{{ route('about-us') }}" class="text-white transition hover:text-primary">Who We Are</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">{{ $team->breadcrumb_label }}</span>
            </nav>
        </div>
    </section>

    <section class="our-team site-section bg-white">
        <div class="site-container">
            <div class="our-team__layout">
                <aside class="our-team__sidebar" aria-label="Team categories">
                    <div class="our-team__sidebar-panel">
                        <h2 class="our-team__widget-title">Categories</h2>
                        <nav class="our-team__nav" data-our-team-nav>
                            <a href="{{ route('our-team-management') }}" class="our-team__nav-link our-team__nav-link--active">
                                <span>{{ $team->page_title }}</span>
                            </a>

                            @if (count($team->regional_nav) > 0)
                                <div class="our-team__nav-group" data-our-team-nav-group>
                                    <button
                                        type="button"
                                        class="our-team__nav-toggle"
                                        data-our-team-nav-toggle
                                        aria-expanded="false"
                                        aria-controls="our-team-nav-regional"
                                    >
                                        <span>Regional Management</span>
                                        <svg class="our-team__nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <ul id="our-team-nav-regional" class="our-team__nav-children">
                                        @foreach ($team->regional_nav as $item)
                                            <li>
                                                <a href="{{ $navHref($item['url']) }}" class="our-team__nav-child">{{ $item['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @foreach ($team->category_nav as $category)
                                <a href="{{ $navHref($category['url']) }}" class="our-team__nav-link">
                                    <span>{{ $category['label'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <div class="our-team__main">
                    <h2 class="our-team__page-title">{{ $team->page_title }}</h2>

                    @foreach ($team->team_sections as $section)
                        <section class="our-team__section">
                            @if (filled($section['heading']))
                                <h3 class="our-team__section-heading">{{ $section['heading'] }}</h3>
                            @endif
                            @if (count($section['members']) > 0)
                                <div class="our-team__grid">
                                    @foreach ($section['members'] as $member)
                                        <article class="our-team__card">
                                            <figure class="our-team__card-photo">
                                                <img
                                                    src="{{ $member['photo_url'] }}"
                                                    alt="{{ $member['name'] }}"
                                                    loading="lazy"
                                                >
                                            </figure>
                                            <div class="our-team__card-body">
                                                <p class="our-team__card-name">{{ $member['name'] }}</p>
                                                @if (filled($member['role']))
                                                    <p class="our-team__card-role">{{ $member['role'] }}</p>
                                                @endif
                                                @if (filled($member['phone']))
                                                    <p class="our-team__card-phone">Tel: {{ $member['phone'] }}</p>
                                                @endif
                                                @if (filled($member['email']))
                                                    <a href="mailto:{{ $member['email'] }}" class="our-team__card-email">{{ $member['email'] }}</a>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
