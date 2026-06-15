@php
    $sd = $siteDetails ?? null;
    $footerSiteName = $siteMetaName ?? ($sd instanceof \App\Models\SiteDetail ? $sd->siteNameForMeta() : \App\Models\SiteDetail::resolvedSiteName());
    $loc = trim((string) ($sd?->location ?? ''));
    $emails = is_array($sd?->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $phones = is_array($sd?->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $social = is_array($sd?->social_links ?? null) ? $sd->social_links : [];
    $footerSocialUrl = static function (?string $raw): ?string {
        $url = trim((string) ($raw ?? ''));
        if ($url === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return 'https://'.ltrim($url, '/');
    };
    $socialNetworks = [
        'facebook' => ['label' => 'Facebook', 'url' => $footerSocialUrl($social['facebook'] ?? null)],
        'twitter' => ['label' => 'Twitter', 'url' => $footerSocialUrl($social['twitter'] ?? null)],
        'linkedin' => ['label' => 'LinkedIn', 'url' => $footerSocialUrl($social['linkedin'] ?? null)],
        'instagram' => ['label' => 'Instagram', 'url' => $footerSocialUrl($social['instagram'] ?? null)],
    ];
    $footerBgUrl = \App\Support\PublicUploadUrl::fromPath($sd?->default_image_path ?? null);
    if ($footerBgUrl === '') {
        $footerBgUrl = 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop';
    }
    $overlayBase = is_string($sd?->theme_footer_overlay_base ?? null) && preg_match('/^#[0-9A-Fa-f]{6}$/', $sd->theme_footer_overlay_base)
        ? $sd->theme_footer_overlay_base
        : 'var(--secondary)';
    $overlayOpacity = is_numeric($sd?->theme_footer_overlay_opacity ?? null)
        ? max(0, min(100, (int) $sd->theme_footer_overlay_opacity)) / 100
        : 0.88;
    $menus = collect($footerMenus ?? []);
    $menuHalf = (int) max(1, ceil($menus->count() / 2));
    $quickLinkMenus = $menus->take($menuHalf);
    $companyLinkMenus = $menus->skip($menuHalf);
    $targetUrls = [
        '/about-us', 'about-us',
        '/where-we-are', 'where-we-are',
        '/contact', 'contact',
        '/get-a-quote', 'get-a-quote'
    ];
    $menuLabels = \App\Models\Menu::whereIn('url', $targetUrls)->pluck('label', 'url')->toArray();
    $subMenuLabels = \App\Models\SubMenu::whereIn('url', $targetUrls)->pluck('label', 'url')->toArray();
    $labels = array_merge($subMenuLabels, $menuLabels);
    $getLabel = function($url, $default) use ($labels) {
        $path = parse_url($url, PHP_URL_PATH);
        return $labels[$path] ?? $labels[ltrim($path, '/')] ?? $default;
    };

    $allDynamicUrls = $menus->map(fn($m) => parse_url($m->siteNavHref(), PHP_URL_PATH))->toArray();

    $staticCompanyLinks = collect([
        ['label' => $getLabel(route('about-us'), 'About Us'), 'url' => route('about-us')],
        ['label' => $getLabel(route('where-we-are'), 'Where We Are'), 'url' => route('where-we-are')],
        ['label' => $getLabel(route('contact.create'), 'Contact'), 'url' => route('contact.create')],
        ['label' => $getLabel(route('quote.request'), 'Get a Quote'), 'url' => route('quote.request')],
    ])->filter(function($link) use ($allDynamicUrls) {
        return !in_array(parse_url($link['url'], PHP_URL_PATH), $allDynamicUrls);
    })->values()->all();
    $footerPorts = [
        [
            'name' => 'Mongla Port',
            'address' => 'Mongla Port Authority, Mongla, Bagerhat District, Khulna Division, Bangladesh.',
        ],
        [
            'name' => 'Payra Port',
            'address' => 'Payra Port Authority, Itbaria, Kalapara Upazila, Patuakhali District, Bangladesh.',
        ],
        [
            'name' => 'Chattogram Port',
            'address' => 'Chattogram Port Authority, Bandar Area, Chattogram 4100, Bangladesh.',
        ],
        [
            'name' => "Matarbari Port",
            'address' => " Matarbari, Maheshkhali Upazila, Cox's Bazar District, Bangladesh.",
        ],
    ];
@endphp

<footer class="site-footer relative mt-auto min-w-0 overflow-hidden text-white">
    <div class="site-footer__bg" style="background-image: url('{{ e($footerBgUrl) }}');" aria-hidden="true"></div>
    <div class="site-footer__overlay" style="--footer-overlay-color: {{ $overlayBase }}; --footer-overlay-opacity: {{ $overlayOpacity }};" aria-hidden="true"></div>

    <div class="site-footer__content site-container">
        <div class="site-footer__top">
            <div class="site-footer__col site-footer__col--brand">
                <a href="{{ route('home') }}" class="site-footer__logo-link">
                    <img src="{{ \App\Models\SiteDetail::footerLogoAssetUrl($sd instanceof \App\Models\SiteDetail ? $sd : null) }}" alt="{{ $footerSiteName }}" class="site-footer__logo">
                </a>
                @if ($footerSiteName !== '')
                    <p class="site-footer__company-name">{{ $footerSiteName }}</p>
                @endif
                <p class="site-footer__office-label">CHITTAGONG (Head Office)</p>
                @if ($loc !== '')
                    <div class="site-footer__contact-row">
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.5 7-11a7 7 0 10-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </span>
                        <div class="site-footer__contact-text">{!! nl2br(e($loc)) !!}</div>
                    </div>
                @endif
                @foreach ($phones as $phone)
                    @php($tel = preg_replace('/[^0-9+]/', '', $phone))
                    <div class="site-footer__contact-row">
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A15 15 0 015 6a2 2 0 012-2z"/></svg>
                        </span>
                        <a href="tel:{{ $tel }}" class="site-footer__contact-text site-footer__contact-link">{{ $phone }}</a>
                    </div>
                @endforeach
                @foreach ($emails as $email)
                    <div class="site-footer__contact-row">
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        </span>
                        <a href="mailto:{{ $email }}" class="site-footer__contact-text site-footer__contact-link">{{ $email }}</a>
                    </div>
                @endforeach
            </div>

            <div class="site-footer__col">
                <h3 class="site-footer__heading">Quick Links</h3>
                <ul class="site-footer__links">
                    @foreach ($quickLinkMenus as $menu)
                        <li><a href="{{ $menu->siteNavHref() }}">{{ $menu->label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="site-footer__col">
                <h3 class="site-footer__heading">Company</h3>
                <ul class="site-footer__links">
                    @foreach ($staticCompanyLinks as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                    @foreach ($companyLinkMenus as $menu)
                        <li><a href="{{ $menu->siteNavHref() }}">{{ $menu->label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="site-footer__col site-footer__col--newsletter">
                <form action="{{ route('contact.create') }}" method="get" class="site-footer__newsletter">
                    <label class="sr-only" for="footer-newsletter-email">Newsletter email</label>
                    <input id="footer-newsletter-email" type="email" name="email" value="{{ request('email') }}" placeholder="Newsletter Registration" class="site-footer__newsletter-input">
                    <button type="submit" class="site-footer__newsletter-btn" aria-label="Newsletter registration">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
                <p class="site-footer__follow-label">Follow Us</p>
                <div class="site-footer__socials">
                    @foreach ($socialNetworks as $key => $network)
                        @if (filled($network['url']))
                            <a href="{{ $network['url'] }}" target="_blank" rel="noopener noreferrer" class="site-footer__social" aria-label="{{ $network['label'] }}">
                                @include('site.partials.footer-social-icon', ['icon' => $key])
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="site-footer__ports">
            @foreach ($footerPorts as $port)
                <div class="site-footer__port">
                    <h4 class="site-footer__port-name">{{ $port['name'] }}</h4>
                    <div class="site-footer__contact-row site-footer__contact-row--compact">
                        <span class="site-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.5 7-11a7 7 0 10-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </span>
                        <div class="site-footer__contact-text">{{ $port['address'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="site-footer__copy">
        <div class="site-bar-strip">
            <div class="site-bar-strip__row site-footer__copy-inner">
                <div class="site-footer__copy-left">
                    <div class="site-bar-strip__cell site-footer__copy-cell site-footer__copy-cell--start">
                        <span class="site-footer__copy-primary">{{ date('Y') }} &copy; {{ $footerSiteName !== '' ? $footerSiteName : 'Newport Maritime Service' }}</span>
                    </div>
                    <div class="site-bar-strip__cell site-footer__copy-cell">
                        <span class="site-footer__copy-secondary">Maritime Logistics &amp; Port Solutions</span>
                    </div>
                </div>
                <a href="#top" class="site-bar-strip__cell site-footer__copy-cell site-footer__copy-cell--end site-footer__back-to-top" data-back-to-top>
                    <span class="site-footer__back-to-top-label">Back to top</span>
                    <span class="site-footer__back-to-top-icon" aria-hidden="true">
                        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="10" class="site-footer__back-to-top-circle" />
                            <path d="M6.25 11.75 10 8l3.75 3.75" class="site-footer__back-to-top-chevron" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</footer>
