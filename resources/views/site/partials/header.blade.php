<div class="w-full min-w-0">
    <!-- Top Bar (desktop) -->
    <div class="hidden bg-brand-navy text-brand-topbar-muted text-sm lg:block">
        <div class="site-container flex flex-wrap items-center justify-between gap-y-2">
            <div class="flex min-w-0 flex-wrap items-center">
                <a href="tel:+88031724728" class="border-x border-brand-navy-mid px-4 py-2.5 transition hover:text-white sm:px-6">
                    +880-31-724728
                </a>
                <a href="mailto:newportmaritimeservice@gmail.com" class="max-w-[200px] truncate border-r border-brand-navy-mid px-4 py-2.5 transition hover:text-white sm:max-w-none sm:px-6">
                    newportmaritimeservice@gmail.com
                </a>
            </div>
            <div class="flex flex-shrink-0 items-center">
                <a href="{{ route('contact.create') }}" class="border-l border-brand-navy-mid px-4 py-2.5 transition hover:text-white sm:px-6">
                    Contact Us
                </a>
                <a href="#" class="border-l border-brand-navy-mid px-4 py-2.5 transition hover:text-white sm:px-6">
                    Products
                </a>
                <a href="#" class="border-x border-brand-navy-mid px-4 py-2.5 transition hover:text-white sm:px-6">
                    Your Spare Parts
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="sticky top-0 z-50 w-full min-w-0 max-w-full overflow-visible bg-white py-3 shadow-sm sm:py-4">
        <div class="site-container flex items-center justify-between gap-3 overflow-visible">
            @php($headerLogo = is_string($siteDetails->header_logo_path ?? null) ? trim($siteDetails->header_logo_path) : '')
            <a href="{{ route('home') }}" class="flex min-w-0 shrink items-center gap-2 no-underline">
                <img src="{{ $headerLogo !== '' ? asset($headerLogo) : asset('newport-logo.png') }}" alt="{{ config('app.name') }}" class="h-9 w-auto max-w-[min(100%,180px)] object-contain object-left sm:h-10 lg:h-12 lg:max-w-none">
            </a>

            @php($quoteBtnClass = 'inline-flex shrink-0 items-center justify-center rounded-md bg-brand-accent px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-white shadow-sm transition hover:bg-brand-accent-hover hover:text-white sm:px-4 sm:py-2.5 sm:text-xs lg:text-sm')

            {{-- Mobile menu --}}
            <details id="siteMobileNav" class="site-mobile-nav relative lg:hidden">
                <summary
                    class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-lg border border-foreground/15 bg-white text-foreground shadow-sm touch-manipulation [&::-webkit-details-marker]:hidden"
                    aria-label="Open menu">
                    <span class="sr-only">Menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </summary>
                {{-- Mobile: simple list under hamburger (desktop dropdown is separate below) --}}
                <div class="absolute right-0 top-[calc(100%+0.5rem)] z-[100] max-h-[min(75vh,28rem)] w-[min(calc(100vw-2*var(--site-padding-x)),22rem)] overflow-y-auto overscroll-contain rounded-xl border border-foreground/10 bg-white py-2 shadow-xl">
                    @foreach ($headerMenus as $menu)
                    @if ($menu->subMenus->isNotEmpty())
                    <details class="site-mobile-nav__sub border-b border-foreground/10 last:border-b-0">
                        <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 text-base font-medium text-foreground">
                            <span>{{ $menu->label }}</span>
                            <span class="text-xs text-foreground/45" aria-hidden="true">▾</span>
                        </summary>
                        <div class="flex flex-col gap-1 border-t border-foreground/5 bg-foreground/[0.02] px-2 py-2">
                            @foreach ($menu->subMenus as $child)
                            <a href="{{ $child->siteNavHref() }}"
                                @class([ 'block rounded-lg px-3 py-2.5 text-sm font-medium transition' , 'bg-brand-accent font-bold uppercase tracking-wide text-white hover:bg-brand-accent-hover'=> $child->isQuoteNavItem(),
                                'text-foreground/80 hover:bg-foreground/5 hover:text-brand-accent' => ! $child->isQuoteNavItem(),
                                ])>
                                {{ $child->label }}
                            </a>
                            @endforeach
                        </div>
                    </details>
                    @else
                    <a href="{{ $menu->siteNavHref() }}"
                        @class([ 'block px-4 py-3.5 text-base font-medium transition' , 'border-b border-foreground/10 text-foreground hover:bg-foreground/5 hover:text-brand-accent'=> ! $menu->isQuoteNavItem(),
                        $quoteBtnClass => $menu->isQuoteNavItem(),
                        'mx-3 my-3 w-[calc(100%-1.5rem)]' => $menu->isQuoteNavItem(),
                        ])>
                        {{ $menu->label }}
                    </a>
                    @endif
                    @endforeach
                </div>
            </details>

            {{-- Desktop only (lg+): hover dropdown + down caret. z-index on hover so panel is not covered by next menu items. --}}
            <nav class="site-desktop-nav relative z-50 hidden items-center justify-end gap-0.5 lg:flex lg:gap-2 xl:gap-3" aria-label="Primary">
                @foreach ($headerMenus as $menu)
                @if ($menu->subMenus->isNotEmpty())
                <div class="site-desktop-nav__item">
                    <a href="{{ $menu->siteNavHref() }}"
                        aria-haspopup="true"
                        @class([ 'site-desktop-nav__trigger flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2.5 text-[0.9375rem] font-semibold tracking-tight transition-colors duration-200' ,
                        $quoteBtnClass=> $menu->isQuoteNavItem(),
                        'text-foreground/90 hover:bg-slate-100/90 hover:text-brand-accent' => ! $menu->isQuoteNavItem() && ! $menu->isActiveBranch(),
                        'text-brand-accent hover:bg-slate-100/90' => ! $menu->isQuoteNavItem() && $menu->isActiveBranch(),
                        ])>
                        <span class="min-w-0">{{ $menu->label }}</span>
                        <span
                            @class([ 'site-desktop-nav__caret inline-flex shrink-0' , 'text-white/90'=> $menu->isQuoteNavItem(),
                            'text-foreground/45' => ! $menu->isQuoteNavItem(),
                            ])
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </a>
                    <div class="site-desktop-nav__dropdown" role="menu" aria-label="{{ $menu->label }}">
                        <div class="site-desktop-dropdown-panel flex flex-col gap-0.5 rounded-xl border border-black/[0.08] bg-white p-1.5 shadow-lg shadow-slate-900/10 ring-1 ring-black/[0.04]">
                            @foreach ($menu->subMenus as $child)
                            <a href="{{ $child->siteNavHref() }}" role="menuitem"
                                @class([ 'block rounded-lg px-3 py-2.5 text-left text-[0.875rem] font-medium transition' , 'bg-brand-accent text-center font-bold uppercase tracking-wide text-white hover:bg-brand-accent-hover'=> $child->isQuoteNavItem(),
                                'text-foreground/90 hover:bg-slate-50 hover:text-brand-accent' => ! $child->isQuoteNavItem() && ! $child->isCurrent(),
                                'bg-slate-50 font-semibold text-brand-accent' => ! $child->isQuoteNavItem() && $child->isCurrent(),
                                ])>
                                {{ $child->label }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ $menu->siteNavHref() }}"
                    @class([ 'shrink-0 rounded-lg px-3 py-2.5 text-[0.9375rem] font-semibold tracking-tight transition-colors duration-200' ,
                    $quoteBtnClass=> $menu->isQuoteNavItem(),
                    'text-foreground/90 hover:bg-slate-100/90 hover:text-brand-accent' => ! $menu->isQuoteNavItem() && ! $menu->isCurrent(),
                    'text-brand-accent hover:bg-slate-100/90' => ! $menu->isQuoteNavItem() && $menu->isCurrent(),
                    ])>
                    {{ $menu->label }}
                </a>
                @endif
                @endforeach
            </nav>
        </div>
    </header>
</div>