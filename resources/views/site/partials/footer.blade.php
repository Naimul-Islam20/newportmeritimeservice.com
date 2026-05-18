@php
    $sd = $siteDetails ?? null;
    $footerSiteName = $siteMetaName ?? ($sd instanceof \App\Models\SiteDetail ? $sd->siteNameForMeta() : \App\Models\SiteDetail::resolvedSiteName());
    $footerLogo = is_string($sd?->footer_logo_path) ? trim($sd->footer_logo_path) : '';
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
        'instagram' => ['label' => 'Instagram', 'url' => $footerSocialUrl($social['instagram'] ?? null)],
        'twitter' => ['label' => 'Twitter', 'url' => $footerSocialUrl($social['twitter'] ?? null)],
        'linkedin' => ['label' => 'LinkedIn', 'url' => $footerSocialUrl($social['linkedin'] ?? null)],
    ];
@endphp

<footer class="site-footer relative mt-auto min-w-0 overflow-hidden text-white">

    <div class="site-footer__inner site-container">
        <div class="site-footer__brand">
            <a href="{{ route('home') }}" class="site-footer__logo-link">
                @if ($footerLogo !== '')
                <img src="{{ asset($footerLogo) }}" alt="{{ $footerSiteName }}" class="site-footer__logo">
                @else
                <img src="{{ asset('newport-logo.png') }}" alt="{{ $footerSiteName }}" class="site-footer__logo">
                @endif
            </a>

            <div class="site-footer__socials">
                @foreach ($socialNetworks as $key => $network)
                    @if (filled($network['url']))
                        <a href="{{ $network['url'] }}" target="_blank" rel="noopener noreferrer" class="site-footer__social" aria-label="{{ $network['label'] }}" title="{{ $network['label'] }}">
                            @include('site.partials.footer-social-icon', ['icon' => $key])
                        </a>
                    @else
                        <span class="site-footer__social site-footer__social--inactive" title="{{ $network['label'] }}">
                            @include('site.partials.footer-social-icon', ['icon' => $key])
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="site-footer__info">
            @if ($loc !== '')
            <div class="site-footer__info-item">
                <span class="site-footer__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10.5 12 3l9 7.5" />
                        <path d="M5 9.5V20h14V9.5" />
                        <path d="M10 20v-6h4v6" />
                    </svg>
                </span>
                <div class="site-footer__info-text">{!! nl2br(e($loc)) !!}</div>
            </div>
            @endif

            @if (count($emails) > 0)
            <div class="site-footer__info-item site-footer__info-item--border">
                <span class="site-footer__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="m3 7 9 6 9-6" />
                    </svg>
                </span>
                <ul class="site-footer__info-text site-footer__info-list">
                    @foreach ($emails as $email)
                    <li>
                        <a href="mailto:{{ $email }}">{{ $email }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (count($phones) > 0)
            <div class="site-footer__info-item site-footer__info-item--border">
                <span class="site-footer__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A15 15 0 015 6a2 2 0 012-2z" />
                    </svg>
                </span>
                <ul class="site-footer__info-text site-footer__info-list">
                    @foreach ($phones as $phone)
                        @php($tel = preg_replace('/[^0-9+]/', '', $phone))
                        <li><a href="tel:{{ $tel }}">{{ $phone }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <nav class="site-footer__nav" aria-label="Footer">
            @foreach (($footerMenus ?? collect()) as $menu)
                <a href="{{ $menu->siteNavHref() }}" class="site-footer__nav-link">{{ $menu->label }}</a>
            @endforeach
        </nav>
    </div>

    <div class="site-footer__copy border-t border-white/10">
        <div class="site-container py-4 text-center text-sm text-white/65 sm:py-5 sm:text-base">
            &copy; {{ date('Y') }} {{ strtoupper($footerSiteName) }}
        </div>
    </div>
</footer>