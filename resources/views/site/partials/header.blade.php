<div class="w-full">
    <!-- Top Bar -->
    <div class="hidden bg-[#112a6d] text-[#b8c6e6] text-sm lg:block">
        <div class="site-container flex items-center justify-between">
            <div class="flex items-center">
                <a href="tel:+88031724728" class="border-x border-[#213b86] px-6 py-2.5 transition hover:text-white">
                    +880-31-724728
                </a>
                <a href="mailto:newportmaritimeservice@gmail.com" class="border-r border-[#213b86] px-6 py-2.5 transition hover:text-white">
                    newportmaritimeservice@gmail.com
                </a>
            </div>
            <div class="flex items-center">
                <a href="{{ route('contact.create') }}" class="border-l border-[#213b86] px-6 py-2.5 transition hover:text-white">
                    Contact Us
                </a>
                <a href="#" class="border-l border-[#213b86] px-6 py-2.5 transition hover:text-white">
                    Products
                </a>
                <a href="#" class="border-x border-[#213b86] px-6 py-2.5 transition hover:text-white">
                    Your Spare Parts
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="sticky top-0 z-50 flex w-full min-w-0 max-w-full items-center gap-4 bg-white py-4 shadow-sm">
        <div class="site-container flex w-full items-center justify-between">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 no-underline">
                <img src="{{ asset('newport-logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto sm:h-12">
            </a>

            <nav class="flex items-center justify-end gap-6 lg:gap-10"
                aria-label="Primary">
                <div class="group relative">
                    <a href="{{ route('home') }}"
                        class="flex shrink-0 items-center gap-1 text-base font-medium text-black transition hover:text-[#3eb0e3] {{ request()->routeIs('home') ? 'text-[#3eb0e3]' : '' }}">
                        Home
                    </a>
                    <div class="invisible absolute left-0 top-full z-50 mt-2 w-48 origin-top-left translate-y-2 scale-95 rounded-lg border border-gray-100 bg-white p-2 shadow-xl transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:scale-100">
                        <a href="{{ route('about-us') }}"
                            class="block rounded-md px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-[#3eb0e3]">
                            About Us
                        </a>
                        <a href="{{ route('where-we-are') }}"
                            class="block rounded-md px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-[#3eb0e3]">
                            WHERE WE ARE
                        </a>
                    </div>
                </div>
                <a href="{{ route('ship-supply') }}"
                    class="shrink-0 text-base font-medium text-black transition hover:text-[#3eb0e3] {{ request()->routeIs('ship-supply') ? 'text-[#3eb0e3]' : '' }}">
                    Ship Supply
                </a>
                <a href="{{ route('our-services') }}"
                    class="shrink-0 text-base font-medium text-black transition hover:text-[#3eb0e3] {{ request()->routeIs('our-services') ? 'text-[#3eb0e3]' : '' }}">
                    Our Services
                </a>
                <a href="{{ route('award') }}"
                    class="shrink-0 text-base font-medium text-black transition hover:text-[#3eb0e3] {{ request()->routeIs('award') ? 'text-[#3eb0e3]' : '' }}">
                    Award
                </a>
                <a href="{{ route('contact.create') }}"
                    class="shrink-0 text-base font-medium text-black transition hover:text-[#3eb0e3] {{ request()->routeIs('contact.create') ? 'text-[#3eb0e3]' : '' }}">
                    Contact
                </a>
                <a href="{{ route('quote.request') }}"
                    class="ml-2 shrink-0 rounded bg-[#3eb0e3] px-6 py-2.5 text-sm font-bold uppercase tracking-wider text-white shadow-sm transition hover:bg-[#2b9bc9] {{ request()->routeIs('quote.request') ? 'ring-2 ring-secondary/30 ring-offset-2 ring-offset-background' : '' }}">
                    GET A QUOTE
                </a>
            </nav>
        </div>
    </header>
</div>