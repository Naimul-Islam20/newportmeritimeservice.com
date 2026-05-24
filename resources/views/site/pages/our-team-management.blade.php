@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Our Team'),
    'metaDescription' => 'Meet our management team and leadership across maritime supply, provision and operations.',
])

@php
    $regionalSubItems = [
        ['label' => 'Rotterdam', 'url' => '#'],
        ['label' => 'Mersin', 'url' => '#'],
        ['label' => 'Tuzla', 'url' => '#'],
        ['label' => 'Athens', 'url' => '#'],
    ];

    $categories = [
        ['label' => 'Sales', 'url' => '#'],
        ['label' => 'Technical Stores', 'url' => route('technical-stores')],
        ['label' => 'Provision', 'url' => route('ship-supply')],
        ['label' => 'Procurement', 'url' => '#'],
        ['label' => 'Customs', 'url' => '#'],
        ['label' => 'Operations', 'url' => route('our-services')],
        ['label' => 'Finance & Accounting', 'url' => '#'],
        ['label' => 'Human Resources', 'url' => '#'],
    ];

    $teamSections = [
        [
            'heading' => 'Management',
            'members' => [
                [
                    'name' => 'Zihni Memişoğlu',
                    'role' => 'Managing Director, CEO',
                    'email' => 'zihnim@gimas.com',
                    'phone' => null,
                    'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=600&auto=format&fit=crop',
                ],
            ],
        ],
        [
            'heading' => 'CEO Office Team',
            'members' => [
                [
                    'name' => 'Sema Mergen',
                    'role' => 'Strategic Analytics Manager',
                    'email' => 'sema.mergen@gimas.com',
                    'phone' => '+90 212 395 5121',
                    'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop',
                ],
            ],
        ],
    ];
@endphp

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">Our Team</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <a href="{{ route('about-us') }}" class="text-white transition hover:text-primary">Who We Are</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Management</span>
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
                                <span>Management</span>
                            </a>

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
                                    @foreach ($regionalSubItems as $item)
                                        <li>
                                            <a href="{{ $item['url'] }}" class="our-team__nav-child">{{ $item['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @foreach ($categories as $category)
                                <a href="{{ $category['url'] }}" class="our-team__nav-link">
                                    <span>{{ $category['label'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <div class="our-team__main">
                    <h2 class="our-team__page-title">Management</h2>

                    @foreach ($teamSections as $section)
                        <section class="our-team__section">
                            <h3 class="our-team__section-heading">{{ $section['heading'] }}</h3>
                            <div class="our-team__grid">
                                @foreach ($section['members'] as $member)
                                    <article class="our-team__card">
                                        <figure class="our-team__card-photo">
                                            <img
                                                src="{{ $member['photo'] }}"
                                                alt="{{ $member['name'] }}"
                                                loading="lazy"
                                            >
                                        </figure>
                                        <div class="our-team__card-body">
                                            <p class="our-team__card-name">{{ $member['name'] }}</p>
                                            <p class="our-team__card-role">{{ $member['role'] }}</p>
                                            @if (! empty($member['phone']))
                                                <p class="our-team__card-phone">Tel: {{ $member['phone'] }}</p>
                                            @endif
                                            <a href="mailto:{{ $member['email'] }}" class="our-team__card-email">{{ $member['email'] }}</a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
