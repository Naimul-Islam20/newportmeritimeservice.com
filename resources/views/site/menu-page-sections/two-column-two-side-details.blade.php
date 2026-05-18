@php($d = is_array($section->data ?? null) ? $section->data : [])
@php($leftTitle = filled(data_get($d, 'left_title')) ? trim(data_get($d, 'left_title')) : null)
@php($rightTitle = filled(data_get($d, 'right_title')) ? trim(data_get($d, 'right_title')) : null)
@php($leftDesc = filled(data_get($d, 'left_description')) ? trim(data_get($d, 'left_description')) : null)
@php($rightDesc = filled(data_get($d, 'right_description')) ? trim(data_get($d, 'right_description')) : null)
@php($sectionTitle = is_string($section->title ?? null) && trim($section->title) !== '' ? trim($section->title) : null)
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))
@php($bodyClass = 'image-details-body text-base leading-relaxed')

<section class="{{ $stripSectionClass }} site-section">
    <div class="site-container">
        @if (filled($sectionTitle))
            <div class="mb-10">
                <h2 class="font-sans text-4xl font-bold {{ $stripTitleClass }} sm:text-5xl">{{ $sectionTitle }}</h2>
            </div>
        @endif

        <div class="site-section-gap grid lg:grid-cols-2">
            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                @if (filled($leftTitle))
                    <h3 class="font-sans text-3xl font-bold text-secondary">{{ $leftTitle }}</h3>
                @endif
                @if (filled($leftDesc))
                    <p @class([filled($leftTitle) ? 'mt-6' : 'mt-0', $bodyClass])>{!! nl2br(e($leftDesc)) !!}</p>
                @endif
            </div>

            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                @if (filled($rightTitle))
                    <h3 class="font-sans text-3xl font-bold text-secondary">{{ $rightTitle }}</h3>
                @endif
                @if (filled($rightDesc))
                    <p @class([filled($rightTitle) ? 'mt-6' : 'mt-0', $bodyClass])>{!! nl2br(e($rightDesc)) !!}</p>
                @endif
            </div>
        </div>
    </div>
</section>
