@php
    $d = is_array($section->data ?? null) ? $section->data : [];
    $mini = data_get($d, 'mini_title');
    $title = $section->title;
    $desc = data_get($d, 'description');
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
@endphp

<section class="bg-[#f6f8fa] py-16 sm:py-24">
    <div class="site-container">
        @if (filled($mini) || filled($title) || filled($desc))
            <div class="mx-auto max-w-3xl text-center">
                @if (filled($mini))
                    <p class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $mini }}</p>
                @endif
                @if (filled($title))
                    <h2 class="mt-3 font-sans text-4xl font-bold leading-tight text-[#112a6d] sm:text-5xl">
                        {{ $title }}
                    </h2>
                @endif
                @if (filled($desc))
                    <p class="mt-5 text-base leading-relaxed text-slate-600 sm:text-lg">
                        {!! nl2br(e($desc)) !!}
                    </p>
                @endif
            </div>
        @endif

        @if (count($tiles) > 0)
            <div class="mt-12 grid items-start gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($tiles as $tile)
                    {{-- Box height follows image (column width, natural aspect); light tinted shadow --}}
                    <figure class="flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-[0_14px_40px_-10px_rgba(1,34,59,0.13),0_5px_18px_-5px_rgba(62,176,227,0.11)]">
                        <div class="w-full overflow-hidden rounded-t-lg bg-slate-100 {{ filled($tile['caption'] ?? null) ? '' : 'rounded-b-lg' }}">
                            @php($lbSrc = asset($tile['path']))
                            @php($lbAlt = is_string($tile['caption'] ?? null) ? $tile['caption'] : '')
                            <button type="button" class="group w-full cursor-zoom-in border-0 bg-transparent p-0 text-left outline-none focus-visible:ring-2 focus-visible:ring-[#3eb0e3] focus-visible:ring-offset-2" data-lightbox-src="{{ e($lbSrc) }}" data-lightbox-alt="{{ e($lbAlt) }}">
                                <img src="{{ $lbSrc }}" alt="{{ $lbAlt }}" loading="lazy" decoding="async" class="block h-auto w-full max-w-full align-middle transition-opacity group-hover:opacity-95">
                            </button>
                        </div>
                        @if (filled($tile['caption'] ?? null))
                            <figcaption class="rounded-b-lg px-4 py-3.5 text-center font-sans text-base font-semibold leading-snug text-slate-800 sm:text-lg sm:py-4">
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
