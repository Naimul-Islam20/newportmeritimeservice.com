@php($d = is_array($section->data ?? null) ? $section->data : [])
@php($leftTitle = data_get($d, 'left_title'))
@php($rightTitle = data_get($d, 'right_title'))
@php($leftDesc = data_get($d, 'left_description'))
@php($rightDesc = data_get($d, 'right_description'))
@php($sectionTitle = $section->title)
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))

<section class="{{ $stripSectionClass }} py-16 sm:py-24">
    <div class="site-container">
        @if (filled($sectionTitle))
            <div class="mb-10">
                <h2 class="font-sans text-4xl font-bold {{ $stripTitleClass }} sm:text-5xl">{{ $sectionTitle }}</h2>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                <h3 class="font-sans text-3xl font-bold text-secondary">{{ $leftTitle ?: 'Our Mission' }}</h3>
                @if (filled($leftDesc))
                    <p class="mt-6 text-base leading-relaxed text-foreground/70">{!! nl2br(e($leftDesc)) !!}</p>
                @endif
            </div>

            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                <h3 class="font-sans text-3xl font-bold text-secondary">{{ $rightTitle ?: 'Our Vision' }}</h3>
                @if (filled($rightDesc))
                    <p class="mt-6 text-base leading-relaxed text-foreground/70">{!! nl2br(e($rightDesc)) !!}</p>
                @endif
            </div>
        </div>
    </div>
</section>
