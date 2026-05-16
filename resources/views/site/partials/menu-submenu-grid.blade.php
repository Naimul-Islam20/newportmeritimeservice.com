{{-- Submenu grid: same cards as home carousel-simple; no section titles; 3 per row on desktop --}}
<section class="bg-white py-16 sm:py-24">
    <div class="site-container">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($submenuPaginator as $item)
                <div class="group relative flex h-[380px] w-full flex-col justify-between overflow-hidden rounded-xl bg-[#01223b] p-8 shadow-lg">
                    @if ($item->coverImageUrl() !== '')
                        <img src="{{ $item->coverImageUrl() }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $item->label }}">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[#01223b] via-slate-900 to-[#01223b]"></div>
                    @endif
                    <div class="absolute inset-0 bg-[#01223b]/85 mix-blend-multiply"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-[#01223b]/90 via-[#01223b]/70 to-[#01223b]/90"></div>

                    <div class="relative z-10 flex h-full flex-col">
                        <h4 class="text-2xl font-bold uppercase leading-snug text-white">{{ $item->label }}</h4>
                        @if (filled($item->description))
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                {{ \Illuminate\Support\Str::limit($item->description, 140) }}
                            </p>
                        @else
                            <div class="mt-4 flex-1"></div>
                        @endif
                        <a href="{{ $item->siteNavHref() }}" class="mt-auto font-bold text-white transition hover:text-[#3eb0e3]">View details</a>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($submenuPaginator->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $submenuPaginator->links() }}
            </div>
        @endif
    </div>
</section>
