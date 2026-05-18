{{-- 2 content carousel: Ship Supplies / What We Supply --}}
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))
<section id="supplies" class="{{ $stripSectionClass }} site-section">
    <div class="site-container">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $section->mini_title ?: 'Ship Supplies' }}</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold {{ $stripTitleClass }} sm:text-5xl">{{ $section->title ?: 'What We Supply' }}</h2>
        </div>

        <div class="site-section-after-title swiper supplies-swiper">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    @php($hasDesc = filled($item->description))
                    @php($descPlain = $hasDesc ? preg_replace('/\s+/u', ' ', trim($item->description)) : '')
                    <div class="swiper-slide">
                        <div class="flex h-full min-h-0 w-full flex-col rounded-2xl border border-foreground/10 bg-secondary/5 p-8 shadow-sm transition hover:shadow-md">
                            <div class="-mx-8 -mt-8 mb-6 shrink-0 overflow-hidden rounded-t-2xl">
                                <img src="{{ $item->pageHeroBackgroundUrl() }}" alt="{{ $item->label }}" class="h-44 w-full object-cover sm:h-52">
                            </div>
                            <div class="flex min-h-0 flex-1 flex-col">
                                <h4 class="shrink-0 text-xl font-bold uppercase text-secondary">{{ $item->label }}</h4>
                                <div class="mt-4 min-h-[2.75rem] shrink-0 sm:min-h-[3rem]" @if ($hasDesc) title="{{ $descPlain }}" @endif>
                                    @if ($hasDesc)
                                        <p class="line-clamp-2 text-sm font-medium leading-relaxed text-foreground/70">
                                            {{ $descPlain }}
                                        </p>
                                    @endif
                                </div>
                                <div class="min-h-0 flex-1" aria-hidden="true"></div>
                                <a href="{{ $item->resolvedHref() }}" class="mt-auto shrink-0 pt-6 text-sm font-bold text-secondary transition hover:text-primary">View details</a>
                            </div>
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
