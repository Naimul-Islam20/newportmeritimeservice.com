{{-- Simple carousel: Our Services / What We Do --}}
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))
<section id="services" class="{{ $stripSectionClass }} site-section">
    <div class="site-container">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $section->mini_title ?: 'Our Services' }}</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold {{ $stripTitleClass }} sm:text-5xl">{{ $section->title ?: 'What We Do' }}</h2>
        </div>
    </div>

    <div class="site-section-after-title site-container">
        <div class="swiper services-swiper">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    <div class="swiper-slide">
                        <div class="group relative flex h-[380px] w-full flex-col justify-between overflow-hidden rounded-xl bg-secondary p-8 shadow-lg">
                            @if ($item->coverImageUrl() !== '')
                                <img src="{{ $item->coverImageUrl() }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $item->label }}">
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-secondary via-secondary to-secondary"></div>
                            @endif
                            <div class="absolute inset-0 bg-secondary/85 mix-blend-multiply"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-secondary/90 via-secondary/70 to-secondary/90"></div>

                            <div class="relative z-10 flex h-full flex-col">
                                <h4 class="text-2xl font-bold uppercase leading-snug text-white">{{ $item->label }}</h4>
                                @if (filled($item->description))
                                    <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                        {{ \Illuminate\Support\Str::limit($item->description, 140) }}
                                    </p>
                                @else
                                    <div class="mt-4 flex-1"></div>
                                @endif
                                <a href="{{ $item->resolvedHref() }}" class="mt-auto font-bold text-white transition hover:text-primary">View details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="flex h-[220px] w-full items-center justify-center rounded-xl border border-foreground/10 bg-secondary/5 p-8 text-center text-foreground/70">
                            No items found for this section.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

