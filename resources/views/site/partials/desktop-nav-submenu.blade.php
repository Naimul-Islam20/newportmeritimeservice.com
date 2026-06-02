{{-- Desktop submenu: simple list OR Gimaş-style flyout when items have children --}}
@php
    $isBlogMenu = $menu->normalizedPath() === '/blog';
    $flyout = ! $isBlogMenu && $menu->hasFlyoutSubMenus();
    $parentsWithChildren = $menu->subMenus->filter(fn ($s) => $s->hasChildren());
@endphp

<div
    @class([
        'site-desktop-nav__dropdown',
        'site-desktop-nav__dropdown--flyout' => $flyout,
    ])
    role="menu"
    aria-label="{{ $menu->label }}"
    @if ($flyout)
        data-nav-flyout
    @endif
>
    @if ($flyout)
        <div class="site-desktop-nav__flyout" role="presentation">
            <div class="site-desktop-nav__flyout-col site-desktop-nav__flyout-col--primary" role="group" aria-label="{{ $menu->label }}">
                @foreach ($menu->subMenus as $child)
                    <a
                        href="{{ $child->siteNavHref() }}"
                        role="menuitem"
                        @class([
                            'site-desktop-nav__flyout-parent',
                            'site-desktop-nav__flyout-parent--current' => $child->isCurrent() || $child->isActiveBranch(),
                        ])
                        @if ($child->hasChildren())
                            data-nav-flyout-parent="{{ $child->id }}"
                            aria-haspopup="true"
                        @endif
                    >
                        <span>{{ $child->label }}</span>
                        @if ($child->hasChildren())
                            <span class="site-desktop-nav__flyout-chevron" aria-hidden="true">&rsaquo;</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="site-desktop-nav__flyout-secondary" data-nav-flyout-secondary hidden>
                @foreach ($parentsWithChildren as $child)
                    <div
                        class="site-desktop-nav__flyout-panel"
                        role="group"
                        aria-label="{{ $child->label }}"
                        data-nav-flyout-panel="{{ $child->id }}"
                        hidden
                    >
                        @foreach ($child->children as $grandchild)
                            <a
                                href="{{ $grandchild->siteNavHref() }}"
                                role="menuitem"
                                @class([
                                    'site-desktop-nav__flyout-child',
                                    'site-desktop-nav__flyout-child--active' => $grandchild->isCurrent(),
                                ])
                            >
                                {{ $grandchild->label }}
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="site-desktop-dropdown-panel">
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
    @endif
</div>
