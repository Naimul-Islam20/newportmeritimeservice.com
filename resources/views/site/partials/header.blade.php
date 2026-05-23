<div class="site-header sticky top-0 z-50 w-full min-w-0">
    {{-- Top utility bar (desktop) — contact left, quick links right --}}
    @php
        $sd = $siteDetails ?? null;
        $topEmails = is_array($sd?->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [];
        $topPhones = is_array($sd?->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [];
        $topEmail = $topEmails[0] ?? null;
        $topPhone = $topPhones[0] ?? null;
        $topBarLinkDefs = [
            ['label' => 'Contact Us', 'path' => '/contact'],
            ['label' => 'Products', 'path' => '/ship-supply'],
            ['label' => 'Your Spare Parts', 'path' => '/get-a-quote'],
        ];
        $topBarLinks = collect($topBarLinkDefs)->map(function (array $item) use ($headerMenus) {
            $path = $item['path'];
            $menu = ($headerMenus ?? collect())->first(fn ($m) => $m->normalizedPath() === $path);
            $href = match ($path) {
                '/contact' => route('contact.create'),
                '/get-a-quote' => route('quote.request'),
                default => $menu?->siteNavHref() ?? $path,
            };

            return ['label' => $item['label'], 'href' => $href];
        })->all();
    @endphp
    <div class="site-header__topbar hidden bg-secondary lg:block">
        <div class="site-container site-header__bar-wrap">
            <div class="site-header__topbar-inner">
                <div class="site-header__topbar-contact">
                    @if ($topPhone)
                        @php($topTel = preg_replace('/[^0-9+]/', '', $topPhone))
                        <a href="tel:{{ $topTel }}" class="site-header__topbar-cell site-header__topbar-cell--start">
                            <span class="site-header__topbar-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                            </span>
                            <span>{{ $topPhone }}</span>
                        </a>
                    @endif
                    @if ($topEmail)
                        <a href="mailto:{{ $topEmail }}" class="site-header__topbar-cell{{ $topPhone ? '' : ' site-header__topbar-cell--start' }}{{ count($topBarLinks) === 0 ? ' site-header__topbar-cell--end' : '' }}">
                            <span class="site-header__topbar-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="m3 7 9 6 9-6" />
                                </svg>
                            </span>
                            <span class="site-header__topbar-email-text">{{ $topEmail }}</span>
                        </a>
                    @endif
                </div>

                @if (count($topBarLinks) > 0)
                    <nav class="site-header__topbar-nav" aria-label="Utility">
                        @foreach ($topBarLinks as $index => $link)
                            <a href="{{ $link['href'] }}"
                                @class([
                                    'site-header__topbar-cell',
                                    'site-header__topbar-cell--start' => $index === 0,
                                    'site-header__topbar-cell--end' => $index === count($topBarLinks) - 1,
                                ])>{{ $link['label'] }}</a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="site-header__main w-full min-w-0 max-w-full overflow-visible bg-white shadow-sm">
        <div class="site-container site-header__bar-wrap site-header__main-inner">
            <a href="{{ route('home') }}" class="site-header__logo-link shrink-0 no-underline">
                <img src="{{ \App\Models\SiteDetail::headerLogoAssetUrl($siteDetails ?? null) }}" alt="{{ $siteMetaName ?? \App\Models\SiteDetail::resolvedSiteName() }}" class="site-header__logo">
            </a>

            @include('site.partials.mobile-nav')

            {{-- Desktop only (lg+): hover dropdown + down caret. z-index on hover so panel is not covered by next menu items. --}}
            <nav class="site-desktop-nav relative z-50 hidden items-stretch justify-end lg:flex" aria-label="Primary">
                @foreach ($headerMenus as $menu)
                @if ($menu->subMenus->isNotEmpty())
                <div class="site-desktop-nav__item">
                    <a href="{{ $menu->siteNavHref() }}"
                        aria-haspopup="true"
                        @class([
                            'site-desktop-nav__trigger',
                            'site-header__quote-btn' => $menu->isQuoteNavItem(),
                            'site-desktop-nav__trigger--active' => ! $menu->isQuoteNavItem() && $menu->isActiveBranch(),
                        ])>
                        <span class="min-w-0">{{ $menu->label }}</span>
                        @unless ($menu->isQuoteNavItem())
                        <span class="site-desktop-nav__caret" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                        @endunless
                    </a>
                    <div class="site-desktop-nav__dropdown" role="menu" aria-label="{{ $menu->label }}">
                        <div class="site-desktop-dropdown-panel flex flex-col gap-0.5 rounded-xl border border-black/[0.08] bg-white p-1.5 shadow-lg shadow-secondary/10 ring-1 ring-black/[0.04]">
                            @foreach ($menu->subMenus as $child)
                            <a href="{{ $child->siteNavHref() }}" role="menuitem"
                                @class([
                                    'site-desktop-dropdown-panel__link',
                                    'site-desktop-dropdown-panel__link--active' => $child->isCurrent(),
                                ])>
                                {{ $child->label }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ $menu->siteNavHref() }}"
                    @class([
                        'site-desktop-nav__trigger',
                        'site-header__quote-btn' => $menu->isQuoteNavItem(),
                        'site-desktop-nav__trigger--active' => ! $menu->isQuoteNavItem() && $menu->isCurrent(),
                    ])>
                    {{ $menu->label }}
                </a>
                @endif
                @endforeach
            </nav>
        </div>
    </header>
</div>