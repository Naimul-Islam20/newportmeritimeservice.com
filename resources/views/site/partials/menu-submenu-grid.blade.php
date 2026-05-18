{{-- Submenu grid: same cards as home carousel-simple; no section titles; 3 per row on desktop --}}
<section class="bg-white site-section">
    <div class="site-container">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($submenuPaginator as $item)
                @php($hasDesc = filled($item->description))
                <div class="group relative flex h-[380px] w-full flex-col overflow-hidden rounded-xl bg-secondary p-8 shadow-lg">
                    <img src="{{ $item->pageHeroBackgroundUrl() }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $item->label }}">
                    <div class="absolute inset-0 bg-secondary/28 mix-blend-multiply"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-secondary/35 via-secondary/18 to-secondary/35"></div>

                    <div @class([
                        'relative z-10 flex h-full flex-col',
                        'justify-between' => $hasDesc,
                        'justify-end gap-4' => ! $hasDesc,
                    ])>
                        @if ($hasDesc)
                            <h4 class="text-2xl font-bold uppercase leading-snug text-white">{{ $item->label }}</h4>
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                {{ \Illuminate\Support\Str::limit($item->description, 140) }}
                            </p>
                            <a href="{{ $item->siteNavHref() }}" class="mt-auto font-bold text-white transition hover:text-primary">View details</a>
                        @else
                            <h4 class="text-2xl font-bold uppercase leading-snug text-white">{{ $item->label }}</h4>
                            <a href="{{ $item->siteNavHref() }}" class="font-bold text-white transition hover:text-primary">View details</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($submenuPaginator->hasPages())
            <div class="site-section-after-title flex justify-center">
                {{ $submenuPaginator->links() }}
            </div>
        @endif
    </div>
</section>
