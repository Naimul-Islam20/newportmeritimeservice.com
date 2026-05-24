@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Message from the CEO'),
    'metaDescription' => 'A message from our CEO on experience, trust, and our vision for maritime supply and logistics.',
])

@php
    $letterParagraphs = [
        'I am proud to express our Company with these qualifications; more than 30 years experience, deep rooted corporate culture, quality & trust based road map and global working vision.',
        'We have been taking firm steps forward since our establishment. Currently, with the vision of “Global Reach Personal Touch”, we continue our operations in four different countries. Transparency, trust and customer satisfaction which are the basic principles of the Company, lead us as a leader in the Sector.',
        'Our Company increases productivity in its logistic operations with advanced technology. Thus, smooth transportation and delivery process of the company carry the customer satisfaction to new high levels. Furthermore, our working principle is tailor made solutions to the Customers with the high qualified team and experience. Products are certified for using in the marine environment and supported with approvals from the major classification societies.',
        'We are responsible for the impacts of all our activities, related with products, transportation, storage and operations, regionally and internationally. Awareness in sustainability is the first and most important step of the Company. We go on our road with synergy which is coming from devoted human resources.',
        'As Newport Maritime Service, we are proud of our more than 30 years of experience which will be our light for the coming years. Moreover, service with high business ethics will continue to be our indispensable value.',
        'We would like to thank our colleagues for their solidarity in all our achievements and our business partners & customers for their support and trust.',
        'We wish many successful years together.',
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
            <h1 class="font-sans text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                &ldquo;We believe in the future&rdquo;
            </h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <a href="{{ route('about-us') }}" class="text-white transition hover:text-primary">Who We Are</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Message from the CEO</span>
            </nav>
        </div>
    </section>

    <section class="ceo-message site-section bg-white">
        <div class="site-container">
            <div class="ceo-message__layout">
                <div class="ceo-message__content">
                    <header class="ceo-message__header">
                        <p class="ceo-message__eyebrow">Message from the CEO</p>
                        <h2 class="ceo-message__salutation">Dear Business Partners and Our Esteemed Colleagues;</h2>
                    </header>

                    <div class="ceo-message__body">
                        @foreach ($letterParagraphs as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <footer class="ceo-message__signature">
                        <div class="ceo-message__signature-text">
                            <p class="ceo-message__name">Zihni Memisoglu</p>
                            <p class="ceo-message__role">CEO, Founder GIMAS</p>
                        </div>
                        <span class="ceo-message__signature-divider" aria-hidden="true"></span>
                        <div class="ceo-message__social">
                            <a href="#" class="ceo-message__social-link" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 4.126 0 2.062 2.062 0 0 1-2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="ceo-message__social-link" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                                </svg>
                            </a>
                        </div>
                    </footer>
                </div>

                <figure class="ceo-message__portrait">
                    <img
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=900&auto=format&fit=crop"
                        alt="Portrait of the CEO"
                        class="ceo-message__portrait-img"
                        loading="lazy"
                    >
                </figure>
            </div>
        </div>
    </section>
@endsection
