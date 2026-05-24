@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Contact Us'),
    'metaDescription' => 'Contact Newport Maritime Service — phone, email and message form for enquiries worldwide.',
])

@php
    $sd = $siteDetails ?? null;
    $defaultEmails = $sd && is_array($sd->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $defaultPhones = $sd && is_array($sd->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $defaultLocation = $sd ? trim((string) ($sd->location ?? '')) : '';
    $defaultMap = $sd ? trim((string) ($sd->map ?? '')) : '';

    $offices = [
        [
            'id' => 'istanbul',
            'label' => 'Istanbul',
            'active' => true,
            'phone' => $defaultPhones[0] ?? '+90 212 671 24 80',
            'email' => $defaultEmails[0] ?? 'gimas@gimas.com',
            'address' => $defaultLocation !== '' ? $defaultLocation : 'Ikitelli OSB Aykosan San.Sit. 4 lu A-Blok No:232 Istanbul-Turkey',
            'map' => $defaultMap,
        ],
        [
            'id' => 'rotterdam',
            'label' => 'Rotterdam',
            'phone' => '+31 10 3027820',
            'email' => 'rotterdam@gimas.com',
            'address' => 'Jan Van Galenstraat 9, 3115 JG Schiedam, The Netherlands',
            'map' => '',
        ],
        [
            'id' => 'hamburg',
            'label' => 'Hamburg',
            'phone' => '',
            'email' => 'hamburg@gimas.com',
            'address' => 'Schlengendeich 13, 21107 Hamburg-Wilhelmsburg, Germany',
            'map' => '',
        ],
        [
            'id' => 'athens',
            'label' => 'Athens',
            'phone' => '+30 210 9403522',
            'email' => 'athens@gimas.com',
            'address' => 'Imittou 6, 3rd Floor, Palaio Faliro 175 64, Attica, Greece',
            'map' => '',
        ],
        [
            'id' => 'mersin',
            'label' => 'Mersin',
            'phone' => '+90 324 221 50 60',
            'email' => 'mersin@gimas.com',
            'address' => 'Karaduvar, Cumhuriyet Blv. No:133, Bakliyatcilar Sitesi E-Blok No:4 161-D, 33020 Mersin, Turkey',
            'map' => '',
        ],
        [
            'id' => 'tuzla',
            'label' => 'Tuzla',
            'phone' => '+90 216 513 12 92',
            'email' => 'tuzla@gimas.com',
            'address' => 'Aydıntepe, Sahil Blv. No:126/19, Denizciler Ticaret Merkezi 34947 Tuzla/Istanbul, Turkey',
            'map' => '',
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
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">Contact Us</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">Contact Us</span>
            </nav>
        </div>
    </section>

    <section class="contact-page site-section">
        <div class="site-container">
            <h2 class="contact-page__title">Contact Us</h2>

            <div class="contact-page__tabs-wrap">
            <div class="contact-page__tabs" role="tablist" aria-label="Office locations">
                @foreach ($offices as $office)
                    <button
                        type="button"
                        role="tab"
                        id="contact-tab-{{ $office['id'] }}"
                        @class([
                            'contact-page__tab',
                            'contact-page__tab--active' => $office['active'] ?? false,
                        ])
                        data-contact-tab="{{ $office['id'] }}"
                        aria-selected="{{ ($office['active'] ?? false) ? 'true' : 'false' }}"
                        aria-controls="contact-panel-{{ $office['id'] }}"
                    >
                        {{ $office['label'] }}
                    </button>
                @endforeach
            </div>
            </div>

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
        <div class="site-container">
            <h3 class="contact-page__map-heading">View map:</h3>
        </div>
        <div class="contact-page__map-embed">
            @foreach ($offices as $office)
                @php
                    $officeMap = trim((string) ($office['map'] ?? ''));
                    $officeMapIframe = $officeMap !== '' && stripos($officeMap, '<iframe') !== false;
                    $officeMapUrl = $officeMap !== '' && ! $officeMapIframe && filter_var($officeMap, FILTER_VALIDATE_URL);
                    $officeEmbed = $officeMap === '' && filled($office['address'] ?? '')
                        ? 'https://maps.google.com/maps?q=' . rawurlencode(preg_replace('/\s+/u', ' ', $office['address'])) . '&output=embed'
                        : null;
                    $officeShowMap = $officeMapIframe || $officeMapUrl || filled($officeEmbed);
                @endphp
                @if ($officeShowMap)
                    <div
                        class="contact-page__map-panel"
                        data-contact-map-panel="{{ $office['id'] }}"
                        {{ ($office['active'] ?? false) ? '' : 'hidden' }}
                    >
                        @if ($officeMapIframe)
                            <div class="contact-page__map-iframe-wrap">{!! $officeMap !!}</div>
                        @else
                            <iframe
                                title="Map — {{ $office['label'] }}"
                                src="{{ $officeMapUrl ? $officeMap : $officeEmbed }}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                            ></iframe>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
<script>
(() => {
    const tabs = document.querySelectorAll('[data-contact-tab]');
    const panels = document.querySelectorAll('[data-contact-panel]');
    const mapPanels = document.querySelectorAll('[data-contact-map-panel]');
    if (tabs.length === 0) return;

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const id = tab.getAttribute('data-contact-tab');
            tabs.forEach((t) => {
                const active = t === tab;
                t.setAttribute('aria-selected', active ? 'true' : 'false');
                t.classList.toggle('contact-page__tab--active', active);
            });
            panels.forEach((panel) => {
                panel.hidden = panel.getAttribute('data-contact-panel') !== id;
            });
            mapPanels.forEach((map) => {
                map.hidden = map.getAttribute('data-contact-map-panel') !== id;
            });
        });
    });

})();
</script>
@endpush
