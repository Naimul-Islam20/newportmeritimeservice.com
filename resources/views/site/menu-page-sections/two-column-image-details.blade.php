@php
    $d = is_array($section->data ?? null) ? $section->data : [];
    $layout = data_get($d, 'layout_width', 'full');
    $imageSide = strtolower(trim((string) data_get($d, 'image_side', 'left')));
    $imageRight = in_array($imageSide, ['right', '1', 'true', 'on'], true);
    $imgPath = data_get($d, 'image_path');
    $imgUrl = (is_string($imgPath) && $imgPath !== '') ? asset($imgPath) : 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop';
    $mini = data_get($d, 'mini_title');
    $title = $section->title ?: data_get($d, 'title');
    $desc = data_get($d, 'description');
    $points = is_array(data_get($d, 'points')) ? array_values(array_filter(data_get($d, 'points'), fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $detailsBodyClass = 'text-base leading-relaxed text-foreground/85 sm:text-lg';
@endphp
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))

@push('styles')
<style>
    @media (max-width: 1023px) {
        .image-details-mobile-image {
            display: block !important;
            width: 100% !important;
            height: auto !important;
            max-height: none !important;
            min-height: 0 !important;
            overflow: visible !important;
        }

        .image-details-mobile-image img,
        .image-details-mobile-image__img {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            max-height: none !important;
            object-fit: contain !important;
        }
    }

    @media (min-width: 1024px) {
        .image-details-mobile-image {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            max-height: none !important;
        }

        .image-details-mobile-image img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            max-height: none !important;
            object-fit: contain !important;
        }
    }
</style>
@endpush

@if ($layout === 'short')
    <section class="{{ $stripSectionClass }} site-section image-details-section">
        <div class="site-container">
            <div @class(['flex flex-col gap-5 sm:gap-8 lg:flex-row lg:items-start lg:gap-10'])>
                @if ($imageRight)
                    <div class="image-details-details-col flex w-full min-w-0 flex-col justify-start pt-0 flex-1">
                        <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $mini ?: 'Why Choose Us?' }}</h3>
                        <h2 class="mt-3 font-sans text-3xl font-bold leading-tight {{ $stripTitleClass }} sm:text-4xl lg:text-[2.75rem]">
                            {!! nl2br(e($title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 max-w-2xl {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 max-w-2xl list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="image-details-mobile-image w-full shrink-0 overflow-hidden rounded-md bg-background/90 lg:w-[48%] lg:shrink-0">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="image-details-mobile-image__img">
                    </div>
                @else
                    <div class="image-details-mobile-image w-full shrink-0 overflow-hidden rounded-md bg-background/90 lg:w-[48%] lg:shrink-0">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="image-details-mobile-image__img">
                    </div>
                    <div class="image-details-details-col flex w-full min-w-0 flex-col justify-start pt-0 flex-1">
                        <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $mini ?: 'Why Choose Us?' }}</h3>
                        <h2 class="mt-3 font-sans text-3xl font-bold leading-tight {{ $stripTitleClass }} sm:text-4xl lg:text-[2.75rem]">
                            {!! nl2br(e($title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 max-w-2xl {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 max-w-2xl list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@else
    <section class="{{ $stripSectionClass }} site-section image-details-section image-details-full">
        <div @class(['flex flex-col gap-3 sm:gap-4 lg:flex-row lg:items-start lg:gap-5'])>
            @if ($imageRight)
                <div class="image-details-details-col flex w-full min-w-0 flex-col justify-start px-4 pb-8 pt-0 flex-1 sm:px-8 sm:pb-12 lg:pb-14 lg:pl-10 xl:pl-12">
                    <div class="max-w-xl">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] {{ $stripMiniClass }}">{{ $mini ?: 'About Us' }}</h3>
                        <h2 class="mt-3 font-sans text-2xl font-bold leading-tight {{ $stripTitleClass }} sm:text-3xl lg:text-[2.25rem]">
                            {{ $title ?: 'Built on Trust. Driven by Excellence.' }}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="image-details-mobile-image w-full shrink-0 overflow-hidden bg-background/90 lg:w-[48%] lg:shrink-0">
                    <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="image-details-mobile-image__img">
                </div>
            @else
                <div class="image-details-mobile-image w-full shrink-0 overflow-hidden bg-background/90 lg:w-[48%] lg:shrink-0">
                    <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="image-details-mobile-image__img">
                </div>
                <div class="image-details-details-col flex w-full min-w-0 flex-col justify-start px-4 pb-8 pt-0 flex-1 sm:px-8 sm:pb-12 lg:pb-14 lg:pr-10 xl:pr-12">
                    <div class="max-w-xl">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] {{ $stripMiniClass }}">{{ $mini ?: 'About Us' }}</h3>
                        <h2 class="mt-3 font-sans text-2xl font-bold leading-tight {{ $stripTitleClass }} sm:text-3xl lg:text-[2.25rem]">
                            {{ $title ?: 'Built on Trust. Driven by Excellence.' }}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
