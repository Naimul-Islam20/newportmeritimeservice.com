@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Contact Us'),
    'metaDescription' => 'Contact Newport Maritime Service — phone, email and message form for enquiries worldwide.',
])

@section('content')
    <section class="contact-page-hero relative flex min-h-[300px] w-full items-center overflow-hidden bg-secondary sm:min-h-[400px]">
        @include('site.partials.page-hero-media', [
            'imageUrl' => $heroImageUrl ?? 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop',
        ])
        <div class="relative z-10 site-container">
            <h1 class="contact-page-hero__title">Contact Us</h1>
            <nav class="contact-page-hero__crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Contact Us</span>
            </nav>
        </div>
    </section>

    <section class="contact-page site-section">
        <div class="site-container">
            <h2 class="contact-page__title">Contact Us</h2>



            <div class="contact-page__layout">
                <div class="contact-page__info-col">
                    @foreach ($offices as $office)
                        <div
                            id="contact-panel-{{ $office['id'] }}"
                            class="contact-page__office-panel"
                            data-contact-panel="{{ $office['id'] }}"
                            role="tabpanel"
                            aria-labelledby="contact-tab-{{ $office['id'] }}"
                            {{ ($office['active'] ?? false) ? '' : 'hidden' }}
                        >
                            <h3 class="contact-page__info-title">Contact Info</h3>

                            @if (filled($office['phone'] ?? ''))
                                <p class="contact-page__phone">
                                    <span class="contact-page__phone-label">Phone :</span>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office['phone']) }}" class="contact-page__phone-number">{{ $office['phone'] }}</a>
                                </p>
                            @endif

                            @if (filled($office['email'] ?? ''))
                                <p class="contact-page__email">
                                    <span class="contact-page__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/>
                                        </svg>
                                    </span>
                                    <a href="mailto:{{ $office['email'] }}">{{ $office['email'] }}</a>
                                </p>
                            @endif

                            @if (filled($office['address'] ?? ''))
                                <p class="contact-page__address">
                                    <span class="contact-page__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>
                                        </svg>
                                    </span>
                                    <span class="contact-page__address-text">
                                        {{ $office['address'] }}
                                        <a href="#contact-map" class="contact-page__map-link">(View map)</a>
                                    </span>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="contact-page__form-wrap">
                    <h3 class="contact-page__form-title">Contact form</h3>

                    @if (session('status'))
                        <div class="contact-page__status">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="contact-page__form" novalidate>
                        @csrf

                        <div class="contact-page__form-row contact-page__form-row--2">
                            <label class="contact-page__field">
                                <span class="contact-page__sr-only">Name (required)</span>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" required autocomplete="given-name">
                            </label>
                            <label class="contact-page__field">
                                <span class="contact-page__sr-only">Surname (required)</span>
                                <input type="text" name="surname" value="{{ old('surname') }}" placeholder="Surname" required autocomplete="family-name">
                            </label>
                        </div>
                        @error('name')
                            <p class="contact-page__error">{{ $message }}</p>
                        @enderror
                        @error('surname')
                            <p class="contact-page__error">{{ $message }}</p>
                        @enderror

                        <div class="contact-page__form-row">
                            <label class="contact-page__field">
                                <span class="contact-page__sr-only">Company</span>
                                <input type="text" name="company" value="{{ old('company') }}" placeholder="Company" autocomplete="organization">
                            </label>
                        </div>

                        <div class="contact-page__form-row contact-page__form-row--2">
                            <label class="contact-page__field">
                                <span class="contact-page__sr-only">E-Mail (required)</span>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="E-Mail" required autocomplete="email">
                            </label>
                            <label class="contact-page__field">
                                <span class="contact-page__sr-only">Phone Number</span>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone Number" autocomplete="tel">
                            </label>
                        </div>
                        @error('email')
                            <p class="contact-page__error">{{ $message }}</p>
                        @enderror

                        <div class="contact-page__form-row">
                            <label class="contact-page__field contact-page__field--message">
                                <span class="contact-page__sr-only">Your Message (required)</span>
                                <textarea name="message" rows="6" placeholder="Your Message" required>{{ old('message') }}</textarea>
                            </label>
                        </div>
                        @error('message')
                            <p class="contact-page__error">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="contact-page__submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="contact-map" class="contact-page__map-section" aria-label="Map">
        <div class="site-container contact-page__map-inner">
            <h3 class="contact-page__map-heading">View map:</h3>
            <div class="contact-page__map-embed">
                @foreach ($offices as $office)
                    @php($map = $office['map'])
                    @if (\App\Support\MapEmbed::hasDisplay($map))
                        <div
                            class="contact-page__map-panel"
                            data-contact-map-panel="{{ $office['id'] }}"
                            {{ ($office['active'] ?? false) ? '' : 'hidden' }}
                        >
                            @if ($map->type === 'iframe')
                                <div class="contact-page__map-iframe-wrap">{!! $map->html !!}</div>
                            @else
                                <iframe
                                    title="{{ $map->title ?? 'Map' }} — {{ $office['label'] }}"
                                    src="{{ $map->src }}"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    allowfullscreen
                                ></iframe>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection
