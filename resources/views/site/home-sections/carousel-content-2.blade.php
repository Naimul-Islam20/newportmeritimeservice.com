{{-- 2 content carousel: Ship Supplies / What We Supply --}}
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))
<section id="supplies" class="{{ $stripSectionClass }} pb-16 pt-16 sm:pb-24 sm:pt-24">
    <div class="site-container">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $section->mini_title ?: 'Ship Supplies' }}</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold {{ $stripTitleClass }} sm:text-5xl">{{ $section->title ?: 'What We Supply' }}</h2>
        </div>

        <div class="mt-10 swiper supplies-swiper">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-foreground/10 bg-secondary/5 p-8 shadow-sm transition hover:shadow-md">
                            @if ($item->coverImageUrl() !== '')
                                <div class="-mx-8 -mt-8 mb-6 overflow-hidden rounded-t-2xl">
                                    <img src="{{ $item->coverImageUrl() }}" alt="{{ $item->label }}" class="h-44 w-full object-cover sm:h-52">
                                </div>
                            @endif
                            <h4 class="text-xl font-bold uppercase text-secondary">{{ $item->label }}</h4>
                            @if (filled($item->description))
                                <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-foreground/70">
                                    {{ \Illuminate\Support\Str::limit($item->description, 160) }}
                                </p>
                            @else
                                <div class="mt-4 flex-1"></div>
                            @endif
                            <a href="{{ $item->resolvedHref() }}" class="mt-8 text-sm font-bold text-secondary transition hover:text-primary">View details</a>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="flex h-full w-full flex-col rounded-2xl border border-foreground/10 bg-secondary/5 p-8 text-center text-foreground/70">
                            No items found for this section.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

