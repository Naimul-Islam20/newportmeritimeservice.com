@php($left = is_array($section->left_content ?? null) ? $section->left_content : [])
@php($right = is_array($section->right_content ?? null) ? $section->right_content : [])
@php($leftTitle = filled($left['title'] ?? null) ? trim($left['title']) : null)
@php($rightTitle = filled($right['title'] ?? null) ? trim($right['title']) : null)
@php($leftDesc = filled($left['description'] ?? null) ? trim($left['description']) : null)
@php($rightDesc = filled($right['description'] ?? null) ? trim($right['description']) : null)
@php($leftPoints = is_array($left['points'] ?? null) ? array_values(array_filter($left['points'], fn ($v) => is_string($v) && trim($v) !== '')) : [])
@php($rightPoints = is_array($right['points'] ?? null) ? array_values(array_filter($right['points'], fn ($v) => is_string($v) && trim($v) !== '')) : [])
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))
@php($bodyClass = 'image-details-body text-justify text-base leading-relaxed')

<section class="{{ $stripSectionClass }} site-section">
    <div class="site-container">
        <div class="site-section-gap grid lg:grid-cols-2">
            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                @if (filled($leftTitle))
                    <h3 class="font-sans text-3xl font-bold text-secondary">{{ $leftTitle }}</h3>
                @endif
                @if (filled($leftDesc))
                    <p @class([filled($leftTitle) ? 'mt-6' : 'mt-0', $bodyClass])>{{ $leftDesc }}</p>
                @endif
                @if (count($leftPoints) > 0)
                    <ul @class([(filled($leftTitle) || filled($leftDesc)) ? 'mt-6' : 'mt-0', 'list-disc space-y-4 pl-5', $bodyClass])>
                        @foreach ($leftPoints as $p)
                            <li>{{ $p }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                @if (filled($rightTitle))
                    <h3 class="font-sans text-3xl font-bold text-secondary">{{ $rightTitle }}</h3>
                @endif
                @if (filled($rightDesc))
                    <p @class([filled($rightTitle) ? 'mt-6' : 'mt-0', $bodyClass])>{{ $rightDesc }}</p>
                @endif
                @if (count($rightPoints) > 0)
                    <ul @class([(filled($rightTitle) || filled($rightDesc)) ? 'mt-6' : 'mt-0', 'list-disc space-y-4 pl-5', $bodyClass])>
                        @foreach ($rightPoints as $p)
                            <li>{{ $p }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>
