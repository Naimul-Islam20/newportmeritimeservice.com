@php
    $d = is_array($section->data ?? null) ? $section->data : [];
    $mini = filled(data_get($d, 'mini_title')) ? trim(data_get($d, 'mini_title')) : null;
    $title = is_string($section->title ?? null) && trim($section->title) !== '' ? trim($section->title) : null;
    $desc = filled(data_get($d, 'description')) ? trim(data_get($d, 'description')) : null;
    $mainPath = data_get($d, 'image_path');
    $mainCaption = data_get($d, 'image_caption');
    $extrasRaw = data_get($d, 'extra_gallery');
    $extras = is_array($extrasRaw)
        ? array_values(array_filter($extrasRaw, fn ($x) => is_array($x) && filled(data_get($x, 'path'))))
        : [];

    $tiles = [];
    if (is_string($mainPath) && $mainPath !== '') {
        $cap = is_string($mainCaption) ? trim($mainCaption) : '';
        $tiles[] = [
            'path' => $mainPath,
            'caption' => $cap !== '' ? $cap : null,
        ];
    }
    foreach ($extras as $ex) {
        $p = data_get($ex, 'path');
        if (is_string($p) && $p !== '') {
            $t = data_get($ex, 'title');
            $t = is_string($t) ? trim($t) : '';
            $tiles[] = [
                'path' => $p,
                'caption' => $t !== '' ? $t : null,
            ];
        }
    }

    $hasHeader = filled($mini) || filled($title) || filled($desc);
    $hasTitles = filled($mini) || filled($title);
    $descMargin = $hasTitles ? 'mt-5' : 'mt-0';
    $bodyClass = 'image-details-body text-justify text-base leading-relaxed sm:text-lg';
@endphp
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))

<section class="{{ $stripSectionClass }} site-section">
    <div class="site-container">
        @if ($hasHeader)
            <div class="mx-auto max-w-3xl text-center">
                @if (filled($mini))
                    <p class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $mini }}</p>
                @endif
                @if (filled($title))
                    <h2 class="mt-3 font-sans text-4xl font-bold leading-tight {{ $stripTitleClass }} sm:text-5xl">
                        {{ $title }}
                    </h2>
                @endif
                @if (filled($desc))
                    <p class="{{ $descMargin }} {{ $bodyClass }}">
                        {!! nl2br(e($desc)) !!}
                    </p>
                @endif
            </div>
        @endif

        @if (count($tiles) > 0)
            <div @class(['grid items-start gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3', 'site-section-after-title' => $hasHeader])>
                @foreach ($tiles as $tile)
                    @php($lbSrc = public_upload_url($tile['path'] ?? null))
                    @if ($lbSrc === '')
                        @continue
                    @endif
                    <figure class="flex w-full flex-col overflow-hidden rounded-lg {{ $stripCardClass }} shadow-lg shadow-secondary/10">
                        <div class="w-full overflow-hidden rounded-t-lg bg-foreground/5 {{ filled($tile['caption'] ?? null) ? '' : 'rounded-b-lg' }}">
                            @php($lbAlt = is_string($tile['caption'] ?? null) ? $tile['caption'] : '')
                            <button type="button" class="group w-full cursor-zoom-in border-0 bg-transparent p-0 text-left outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2" data-lightbox-src="{{ e($lbSrc) }}" data-lightbox-alt="{{ e($lbAlt) }}">
                                <img src="{{ $lbSrc }}" alt="{{ $lbAlt }}" loading="lazy" decoding="async" class="block h-auto w-full max-w-full align-middle transition-opacity group-hover:opacity-95">
                            </button>
                        </div>
                        @if (filled($tile['caption'] ?? null))
                            <figcaption class="rounded-b-lg px-4 py-3.5 text-center font-sans text-base font-semibold leading-snug text-foreground sm:text-lg sm:py-4">
                                {{ $tile['caption'] }}
                            </figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
            @include('site.partials.image-fullscreen-lightbox')
        @endif
    </div>
</section>
