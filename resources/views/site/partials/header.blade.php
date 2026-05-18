<div class="site-header sticky top-0 z-50 w-full min-w-0">
    <!-- Top Bar (desktop) -->
    <div class="hidden bg-secondary text-white/70 text-sm lg:block lg:text-[0.9375rem]">
        <div class="site-container flex flex-wrap items-center gap-y-2">
            <div class="flex min-w-0 flex-wrap items-center">
                @php($sd = $siteDetails ?? null)
                @php($topEmails = is_array($sd?->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [])
                @php($topPhones = is_array($sd?->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [])
                @php($topEmail = $topEmails[0] ?? null)
                @php($topPhone = $topPhones[0] ?? null)
                @if ($topPhone)
                    @php($topTel = preg_replace('/[^0-9+]/', '', $topPhone))
                    <a href="tel:{{ $topTel }}" class="border-x border-white/15 px-4 py-2.5 transition hover:text-white sm:px-6">
                        {{ $topPhone }}
                    </a>
                @endif
                @if ($topEmail)
                    <a href="mailto:{{ $topEmail }}" class="max-w-[200px] truncate border-r border-white/15 px-4 py-2.5 transition hover:text-white sm:max-w-none sm:px-6">
                        {{ $topEmail }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="site-header__main w-full min-w-0 max-w-full overflow-visible bg-white shadow-sm">
        <div class="site-container flex items-center justify-between gap-3 overflow-visible">
            @php($headerLogo = is_string($siteDetails->header_logo_path ?? null) ? trim($siteDetails->header_logo_path) : '')
            <a href="{{ route('home') }}" class="site-header__logo-link shrink-0 no-underline">
                <img src="{{ $headerLogo !== '' ? asset($headerLogo) : asset('newport-logo.png') }}" alt="{{ config('app.name') }}" class="site-header__logo">
            </a>

            @php($quoteBtnClass = 'inline-flex shrink-0 items-center justify-center rounded-md bg-primary px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-secondary shadow-sm transition hover:brightness-95 sm:px-4 sm:py-2.5 sm:text-xs lg:text-sm')

            @include('site.partials.mobile-nav')

            {{-- Desktop only (lg+): hover dropdown + down caret. z-index on hover so panel is not covered by next menu items. --}}
            <nav class="site-desktop-nav relative z-50 hidden items-center justify-end gap-0.5 lg:flex lg:gap-2 xl:gap-3" aria-label="Primary">
                @foreach ($headerMenus as $menu)
                @if ($menu->subMenus->isNotEmpty())
                <div class="site-desktop-nav__item">
                    <a href="{{ $menu->siteNavHref() }}"
                        aria-haspopup="true"
                        @class([ 'site-desktop-nav__trigger flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-3 text-base font-semibold tracking-tight transition-colors duration-200' ,
                        $quoteBtnClass=> $menu->isQuoteNavItem(),
                        'text-foreground/90 hover:bg-foreground/5/90 hover:text-primary' => ! $menu->isQuoteNavItem() && ! $menu->isActiveBranch(),
                        'text-primary hover:bg-foreground/5/90' => ! $menu->isQuoteNavItem() && $menu->isActiveBranch(),
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
                        <div class="site-desktop-dropdown-panel flex flex-col gap-0.5 rounded-xl border border-black/[0.08] bg-white p-1.5 shadow-lg shadow-secondary/10 ring-1 ring-black/[0.04]">
                            @foreach ($menu->subMenus as $child)
                            <a href="{{ $child->siteNavHref() }}" role="menuitem"
                                @class([ 'block rounded-lg px-3 py-2.5 text-left text-[0.875rem] font-medium transition' , 'bg-primary text-center font-bold uppercase tracking-wide text-secondary hover:brightness-95'=> $child->isQuoteNavItem(),
                                'text-foreground/90 hover:bg-foreground/5 hover:text-primary' => ! $child->isQuoteNavItem() && ! $child->isCurrent(),
                                'bg-foreground/5 font-semibold text-primary' => ! $child->isQuoteNavItem() && $child->isCurrent(),
                                ])>
                                {{ $child->label }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ $menu->siteNavHref() }}"
                    @class([ 'shrink-0 rounded-lg px-3 py-3 text-base font-semibold tracking-tight transition-colors duration-200' ,
                    $quoteBtnClass=> $menu->isQuoteNavItem(),
                    'text-foreground/90 hover:bg-foreground/5/90 hover:text-primary' => ! $menu->isQuoteNavItem() && ! $menu->isCurrent(),
                    'text-primary hover:bg-foreground/5/90' => ! $menu->isQuoteNavItem() && $menu->isCurrent(),
                    ])>
                    {{ $menu->label }}
                </a>
                @endif
                @endforeach
            </nav>
        </div>
    </header>
</div>