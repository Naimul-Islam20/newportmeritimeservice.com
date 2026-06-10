{{-- Mobile sidebar drawer (lg and below) --}}
<details id="siteMobileNav" class="site-mobile-nav lg:hidden">
    <summary class="site-mobile-nav__toggle" aria-label="Open menu">
        <span class="site-mobile-nav__toggle-icon site-mobile-nav__toggle-icon--open" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </span>
        <span class="site-mobile-nav__toggle-icon site-mobile-nav__toggle-icon--close" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </span>
    </summary>

    <button type="button" class="site-mobile-nav__backdrop" data-mobile-nav-close aria-label="Close menu"></button>

    <aside class="site-mobile-nav__panel" role="dialog" aria-modal="true" aria-label="Navigation menu">
        <div class="site-mobile-nav__head">
            <span class="site-mobile-nav__title">Menu</span>
            <button type="button" class="site-mobile-nav__close" data-mobile-nav-close aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="site-mobile-nav__body" aria-label="Primary">
            @foreach ($headerMenus as $menu)
                @if ($menu->subMenus->isNotEmpty())
                    <details class="site-mobile-nav__group">
                        <summary class="site-mobile-nav__link site-mobile-nav__link--parent">
                            <span>{{ $menu->label }}</span>
                            <svg class="site-mobile-nav__chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </summary>
                        <div class="site-mobile-nav__sub">
                            @foreach ($menu->subMenus as $child)
                                @if ($child->hasChildren() && $child->showsChildItemsInNav())
                                    <details class="site-mobile-nav__nested">
                                        <summary class="site-mobile-nav__sublink site-mobile-nav__sublink--parent">
                                            <span>{{ $child->label }}</span>
                                            <svg class="site-mobile-nav__chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </summary>
                                        <div class="site-mobile-nav__nested-sub">
                                            @unless ($child->isDropdownOnlyParentNav())
                                                <a href="{{ $child->siteNavHref() }}" class="site-mobile-nav__sublink site-mobile-nav__sublink--muted">{{ $child->label }} overview</a>
                                            @endunless
                                            @foreach ($child->children as $grandchild)
                                                <a href="{{ $grandchild->siteNavHref() }}"
                                                    @class([
                                                        'site-mobile-nav__sublink',
                                                        'site-mobile-nav__sublink--active' => $grandchild->isCurrent(),
                                                    ])>
                                                    {{ $grandchild->label }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <a href="{{ $child->siteNavHref() }}"
                                        @class([
                                            'site-mobile-nav__sublink',
                                            'site-mobile-nav__sublink--quote' => $child->isQuoteNavItem(),
                                            'site-mobile-nav__sublink--active' => ! $child->isQuoteNavItem() && $child->isCurrent(),
                                        ])>
                                        {{ $child->label }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </details>
                @else
                    <a href="{{ $menu->siteNavHref() }}"
                        @class([
                            'site-mobile-nav__link',
                            'site-mobile-nav__link--quote' => $menu->isQuoteNavItem(),
                            'site-mobile-nav__link--active' => ! $menu->isQuoteNavItem() && $menu->isCurrent(),
                        ])>
                        {{ $menu->label }}
                    </a>
                @endif
            @endforeach
        </nav>
    </aside>
</details>
