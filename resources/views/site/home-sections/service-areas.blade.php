{{-- Apatoto off: Ship Supplies / What We Supply (content_2) — above service area --}}
@if (false && ($content2CarouselSection ?? null))
    @include('site.home-sections.carousel-content-2', [
        'section' => $content2CarouselSection,
        'items' => $content2CarouselItems ?? collect(),
        'sectionStrip' => $content2SectionStrip ?? 'primary',
    ])
@endif

@php
    $mini = $serviceArea['mini_title'] ?? 'Service Areas';
    $title = $serviceArea['title'] ?? 'Locations';
    $mapPath = $serviceArea['map_image_path'] ?? null;
    $mapPath = is_string($mapPath) ? trim($mapPath) : '';
    $mapUrl = $mapPath !== '' && (str_starts_with($mapPath, 'http://') || str_starts_with($mapPath, 'https://'))
        ? $mapPath
        : ($mapPath !== '' ? public_upload_url($mapPath) : null);
    $mapUrl = ($mapUrl ?? '') !== '' ? $mapUrl : null;
    $highlightTitle = $serviceArea['highlight_title'] ?? '';
    $highlightDescription = $serviceArea['highlight_description'] ?? '';
    $steps = is_array($serviceArea['steps'] ?? null) ? $serviceArea['steps'] : [];
    $branches = is_array($serviceArea['branches'] ?? null) ? $serviceArea['branches'] : [];
    $serviceAreaBgUrl = asset('home-service-area/location-background-uzun.png');
@endphp

<section class="service-area site-section">
    <div
        class="service-area__bg"
        style="background-image: url('{{ $serviceAreaBgUrl }}');"
        aria-hidden="true"
    ></div>

    <div class="relative z-10 site-container">
        @include('site.home-sections.service-area-branches-carousel', ['branches' => $branches])

        <div class="service-area__divider" aria-hidden="true"></div>

        <div class="service-area__body">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-primary">{{ $mini }}</h3>
                <h2 class="mt-2 font-sans text-4xl font-bold text-white sm:text-5xl">{{ $title }}</h2>
            </div>

            @if ($mapUrl)
                <div class="site-section-after-title sm:mt-12">
                    <div
                        class="service-area__map"
                        style="background-image: url('{{ e($mapUrl) }}');"
                        role="img"
                        aria-label="{{ $title }}"
                    ></div>
                </div>
            @endif

            @if (filled($highlightTitle) || filled($highlightDescription))
                <div class="mt-10 border-t border-white/10 pt-8 sm:mt-16 sm:pt-12 lg:mt-24 md:border-none md:pt-0">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-12">
                        @if (filled($highlightTitle))
                            <h3 class="shrink-0 font-sans text-2xl font-bold text-white sm:text-3xl">{{ $highlightTitle }}</h3>
                        @endif
                        <div class="hidden h-12 w-px bg-white/30 md:block"></div>
                        <div class="h-px w-16 bg-white/30 md:hidden"></div>
                        @if (filled($highlightDescription))
                            <p class="text-base leading-relaxed text-white/70 md:max-w-3xl">
                                {!! nl2br(e($highlightDescription)) !!}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            @if (count($steps) > 0)
                <div class="mt-10 grid grid-cols-2 gap-5 text-white sm:mt-16 sm:grid-cols-4 sm:gap-6 lg:gap-10">
                    @foreach ($steps as $step)
                        <div class="text-base font-medium leading-snug">{!! nl2br(e($step)) !!}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
