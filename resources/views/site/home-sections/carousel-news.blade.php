{{-- News carousel: The News / Latest News --}}
<section class="bg-[#f4f5f7] py-16 sm:py-24">
    <div class="site-container">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $section->mini_title ?: 'The News' }}</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold text-[#112a6d] sm:text-5xl">{{ $section->title ?: 'Latest News' }}</h2>
        </div>

        <div class="swiper news-swiper mt-10">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    <div class="swiper-slide">
                        <div class="flex flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                            @if ($item->coverImageUrl() !== '')
                                <div class="-mx-5 -mt-5 overflow-hidden rounded-t-2xl">
                                    <img src="{{ $item->coverImageUrl() }}" class="h-64 w-full object-cover" alt="{{ $item->label }}">
                                </div>
                            @endif
                            <div class="mt-6 flex flex-1 flex-col">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ ($item->published_at ?? $item->created_at)?->format('F d, Y') }}
                                </p>
                                <h4 class="mt-4 text-xl font-bold leading-snug text-[#112a6d]">{{ $item->label }}</h4>
                                @if (filled($item->description))
                                    <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                        {{ \Illuminate\Support\Str::limit($item->description, 170) }}
                                    </p>
                                @else
                                    <div class="mt-4 flex-1"></div>
                                @endif
                                <a href="{{ $item->resolvedHref() }}" class="mt-6 text-sm font-bold text-slate-900 transition hover:text-[#3eb0e3]">View details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-slate-100 bg-white p-8 text-center text-slate-600">
                            No items found for this section.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

