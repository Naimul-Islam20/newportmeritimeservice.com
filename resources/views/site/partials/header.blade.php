<header
    class=" sticky top-0 z-50 flex w-full min-w-0 max-w-full items-center gap-4 border-b border-foreground/10 bg-background/90 py-3 backdrop-blur-md">
    <div class="flex site-container items-center justify-between w-full">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 no-underline">
            <img src="{{ asset('newport-logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto sm:h-11">
        </a>

        <nav class="flex min-w-0 flex-1 flex-nowrap items-center justify-end gap-1 overflow-x-auto overscroll-x-contain pb-0.5 [-ms-overflow-style:none] [scrollbar-width:none] sm:gap-1.5 lg:gap-2 [&::-webkit-scrollbar]:hidden"
            aria-label="Primary">
            <a href="{{ route('home') }}"
                class="shrink-0 rounded-lg px-2.5 py-2 text-xs font-semibold text-foreground/80 transition hover:bg-primary-soft hover:text-secondary sm:px-3 sm:text-sm {{ request()->routeIs('home') ? 'bg-primary-soft text-secondary' : '' }}">
                Home
            </a>
            <a href="{{ route('ship-supply') }}"
                class="shrink-0 rounded-lg px-2.5 py-2 text-xs font-semibold text-foreground/80 transition hover:bg-primary-soft hover:text-secondary sm:px-3 sm:text-sm {{ request()->routeIs('ship-supply') ? 'bg-primary-soft text-secondary' : '' }}">
                Ship Supply
            </a>
            <a href="{{ route('our-services') }}"
                class="shrink-0 rounded-lg px-2.5 py-2 text-xs font-semibold text-foreground/80 transition hover:bg-primary-soft hover:text-secondary sm:px-3 sm:text-sm {{ request()->routeIs('our-services') ? 'bg-primary-soft text-secondary' : '' }}">
                Our Services
            </a>
            <a href="{{ route('award') }}"
                class="shrink-0 rounded-lg px-2.5 py-2 text-xs font-semibold text-foreground/80 transition hover:bg-primary-soft hover:text-secondary sm:px-3 sm:text-sm {{ request()->routeIs('award') ? 'bg-primary-soft text-secondary' : '' }}">
                Award
            </a>
            <a href="{{ route('contact.create') }}"
                class="shrink-0 rounded-lg px-2.5 py-2 text-xs font-semibold text-foreground/80 transition hover:bg-primary-soft hover:text-secondary sm:px-3 sm:text-sm {{ request()->routeIs('contact.create') ? 'bg-primary-soft text-secondary' : '' }}">
                Contact
            </a>
            <a href="{{ route('quote.request') }}"
                class="shrink-0 rounded-lg bg-primary px-2.5 py-2 text-xs font-bold text-slate-900 shadow-sm transition hover:bg-primary-hover sm:px-3 sm:text-sm {{ request()->routeIs('quote.request') ? 'ring-2 ring-secondary/30 ring-offset-2 ring-offset-background' : '' }}">
                Get a quote
            </a>
        </nav>
    </div>
</header>