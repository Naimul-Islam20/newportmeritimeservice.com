{{-- Two column: image + details (home sections from admin) --}}
@php
    $layout = $section->layout_width ?: 'full';
    $imgUrl = (is_string($section->image_path) && $section->image_path !== '')
        ? asset($section->image_path)
        : 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop';
    $mini = filled($section->mini_title) ? trim($section->mini_title) : null;
    $title = filled($section->title) ? trim($section->title) : null;
    $desc = filled($section->description) ? trim($section->description) : null;
    $points = is_array($section->points ?? null)
        ? array_values(array_filter($section->points, fn ($v) => is_string($v) && trim($v) !== ''))
        : [];
    $detailsBodyClass = 'image-details-body text-base leading-relaxed sm:text-lg';
    $hasHeadings = filled($mini) || filled($title);
    $descMargin = $hasHeadings ? 'mt-3' : 'mt-0';
    $pointsMargin = ($hasHeadings || filled($desc)) ? 'mt-3' : 'mt-0';
    $buttonMargin = ($hasHeadings || filled($desc) || count($points) > 0) ? 'mt-8' : 'mt-0';
    $stackTextFirstOnMobile = ! $hasHeadings;
    $sectionData = is_array($section->data ?? null) ? $section->data : [];
    $imageSide = strtolower(trim((string) ($sectionData['image_side'] ?? 'left')));
    $imageOnRight = $imageSide === 'right';
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
    <section class="{{ $stripSectionClass }} site-section image-details-section image-details-short">
        <div class="site-container">
            <div @class([
                'flex flex-col gap-5 sm:gap-8 lg:flex-row lg:items-start lg:gap-10',
                'flex-col-reverse' => $stackTextFirstOnMobile,
                'lg:flex-row-reverse' => $imageOnRight && ! $stackTextFirstOnMobile,
            ])>
                <div class="image-details-mobile-image w-full shrink-0 overflow-hidden rounded-md bg-background/90 lg:w-[48%] lg:shrink-0">
                    <img src="{{ $imgUrl }}" alt="{{ $section->image_alt ?: '' }}" loading="lazy" decoding="async" class="image-details-mobile-image__img">
                </div>
                <div class="image-details-details-col flex w-full min-w-0 flex-col justify-start flex-1">
                    @include('site.menu-page-sections.partials.image-details-headings', [
                        'mini' => $mini,
                        'title' => $title,
                        'miniClass' => $stripMiniClass,
                        'titleClass' => $stripTitleClass,
                        'titleSize' => 'lg',
                    ])
                    @include('site.menu-page-sections.partials.image-details-content', [
                        'desc' => $desc,
                        'points' => $points,
                        'detailsBodyClass' => $detailsBodyClass,
                        'descMargin' => $descMargin,
                        'pointsMargin' => $pointsMargin,
                        'buttonMargin' => $buttonMargin,
                        'maxWidth' => 'max-w-2xl',
                        'buttonLabel' => $section->button_label,
                        'buttonHref' => $section->resolvedButtonHref(),
                    ])
                </div>
            </div>
        </div>
    </section>
@else
    <section class="{{ $stripSectionClass }} site-section image-details-section image-details-full">
        <div @class([
            'flex flex-col gap-3 sm:gap-4 lg:flex-row lg:items-start lg:gap-5',
            'lg:flex-row-reverse' => $imageOnRight,
        ])>
            <div class="image-details-mobile-image w-full shrink-0 overflow-hidden bg-background/90 lg:w-[48%] lg:shrink-0">
                <img src="{{ $imgUrl }}" alt="{{ $section->image_alt ?: '' }}" loading="lazy" decoding="async" class="image-details-mobile-image__img">
            </div>
            <div class="image-details-details-col flex w-full min-w-0 flex-col justify-start px-4 flex-1 sm:px-8 lg:pr-10 xl:pr-12">
                <div class="max-w-xl">
                    @include('site.menu-page-sections.partials.image-details-headings', [
                        'mini' => $mini,
                        'title' => $title,
                        'miniClass' => $stripMiniClass,
                        'titleClass' => $stripTitleClass,
                        'titleSize' => 'full',
                    ])
                    @include('site.menu-page-sections.partials.image-details-content', [
                        'desc' => $desc,
                        'points' => $points,
                        'detailsBodyClass' => $detailsBodyClass,
                        'descMargin' => $descMargin,
                        'pointsMargin' => $pointsMargin,
                        'buttonMargin' => $buttonMargin,
                        'buttonLabel' => $section->button_label,
                        'buttonHref' => $section->resolvedButtonHref(),
                    ])
                </div>
            </div>
        </div>
    </section>
@endif
