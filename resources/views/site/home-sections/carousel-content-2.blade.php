{{-- 2 content carousel: Ship Supplies / What We Supply --}}
<section id="supplies" class="bg-white pb-16 sm:pb-24">
    <div class="site-container">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $section->mini_title ?: 'Ship Supplies' }}</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold text-[#112a6d] sm:text-5xl">{{ $section->title ?: 'What We Supply' }}</h2>
        </div>

        <div class="mt-10 swiper supplies-swiper">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                            @if ($item->coverImageUrl() !== '')
                                <div class="-mx-8 -mt-8 mb-6 overflow-hidden rounded-t-2xl">
                                    <img src="{{ $item->coverImageUrl() }}" alt="{{ $item->label }}" class="h-44 w-full object-cover sm:h-52">
                                </div>
                            @endif
                            <h4 class="text-xl font-bold uppercase text-[#112a6d]">{{ $item->label }}</h4>
                            @if (filled($item->description))
                                <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                                    {{ \Illuminate\Support\Str::limit($item->description, 160) }}
                                </p>
                            @else
                                <div class="mt-4 flex-1"></div>
                            @endif
                            <a href="{{ $item->resolvedHref() }}" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 text-center text-slate-600">
                            No items found for this section.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

