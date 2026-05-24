@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Career'),
    'metaDescription' => 'Career opportunities at Newport Maritime Service — join our team in maritime supply, provision and logistics.',
])

@php
    $sd = $siteDetails ?? null;
    $defaultEmails = $sd && is_array($sd->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $social = is_array($sd?->social_links ?? null) ? $sd->social_links : [];
    $linkedin = filled($social['linkedin'] ?? null) ? trim((string) $social['linkedin']) : 'https://www.linkedin.com';
    $hrEmail = 'careers@newportmeritimeservice.com';
    foreach ($defaultEmails as $email) {
        if (is_string($email) && str_contains($email, '@')) {
            $hrEmail = trim($email);
            break;
        }
    }

    $qualifications = [
        'Reliability',
        'Competence',
        'Team Work',
        'Responsibility Awareness and Business Follow-Up',
        'Customer Focused Service',
    ];

    $asideImage = 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=900&auto=format&fit=crop';
@endphp

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=2070&auto=format&fit=crop"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">Career</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Career</span>
            </nav>
        </div>
    </section>

    <section class="career-page site-section bg-white">
        <div class="site-container">
            <div class="career-page__grid">
                <div class="career-page__content">
                    <p class="career-page__eyebrow">Career</p>
                    <h2 class="career-page__title">Our Human Resources vision</h2>

                    <div class="career-page__intro">
                        <p>
                            To be the most preferred company with innovative and sustainable HR practices that make its employees feel valued, create a common culture.
                        </p>
                        <p>
                            Our aim is to employ employees who adopt Newport Maritime values, who have the competencies required by the dynamism of the company, who know the importance of the customer, who are willing to learn and develop, who can sustain high performance in the long term, to contribute their professional and personal development, and enable them to use their potential in the most efficient way.
                        </p>
                    </div>

                    <div class="career-page__application">
                        <h3 class="career-page__subtitle">General Application</h3>
                        <p class="career-page__lead">The primary qualifications we consider in candidates who want to join us;</p>

                        <ul class="career-page__checklist">
                            @foreach ($qualifications as $item)
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
                            For open positions, please visit our Kariyer.net and Linkedin pages.<br>
                            For general applications send your CV via mail: {{ $hrEmail }}
                        </p>

                        <div class="career-page__actions">
                            <a href="mailto:{{ $hrEmail }}" class="career-page__btn">Send mail</a>
                            <a href="https://www.kariyer.net" class="career-page__btn" target="_blank" rel="noopener noreferrer">Go Kariyer.net page</a>
                            <a href="{{ $linkedin }}" class="career-page__btn career-page__btn--linkedin" target="_blank" rel="noopener noreferrer">
                                <span class="career-page__btn-label">Go LinkedIn</span>
                                <span class="career-page__btn-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 4.126 0 2.062 2.062 0 0 1-2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <aside class="career-page__aside">
                    <a href="{{ route('our-team-management') }}" class="career-page__team-btn">Our Team</a>
                    <figure class="career-page__aside-figure">
                        <img
                            src="{{ $asideImage }}"
                            alt="Professional handshake"
                            class="career-page__aside-img"
                            loading="lazy"
                        >
                    </figure>
                </aside>
            </div>
        </div>
    </section>

    <section class="career-page__offers" aria-labelledby="career-offers-title">
        <div class="site-container">
            <p class="career-page__offers-eyebrow">Newport Maritime offers</p>
            <h2 id="career-offers-title" class="career-page__offers-title">Promising Career Opportunities</h2>

            <div class="career-page__offers-card">
                <h3 class="career-page__offers-subtitle">About our company</h3>
                <div class="career-page__offers-body">
                    <p>
                        We want to protect and continuously improve our company, where everyone is happy to be a part of it. Newport Maritime has built its business through the excellence of service and consistent investment on growing.
                    </p>
                    <p>
                        Maritime Industry is very large and there are thousands of companies functioning in this sector. Everything can change by the time; except the importance of a reliable service.
                    </p>
                    <p>
                        So, perhaps we are not number one in our sector yet, but with our intense efforts, young and dynamic professional staff and primary principles of honesty and service quality, we do not think it will take such a long time before we see our company as the number one.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="career-page__about-bar" aria-label="About us">
        <div class="site-container">
            <a href="{{ route('about-us') }}" class="career-page__btn">About us</a>
        </div>
    </section>
@endsection
