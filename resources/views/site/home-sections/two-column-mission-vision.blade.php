@php($left = is_array($section->left_content ?? null) ? $section->left_content : [])
@php($right = is_array($section->right_content ?? null) ? $section->right_content : [])
@php($leftPoints = is_array($left['points'] ?? null) ? array_values(array_filter($left['points'], fn ($v) => is_string($v) && trim($v) !== '')) : [])
@php($rightPoints = is_array($right['points'] ?? null) ? array_values(array_filter($right['points'], fn ($v) => is_string($v) && trim($v) !== '')) : [])
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))

<section class="{{ $stripSectionClass }} py-16 sm:py-24">
    <div class="site-container">
        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Box 1 -->
            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                <h3 class="font-sans text-3xl font-bold text-secondary">
                    {{ filled($left['title'] ?? null) ? $left['title'] : 'Our Mission' }}
                </h3>

                @if (filled($left['description'] ?? null))
                    <p class="mt-6 text-base leading-relaxed text-foreground/70">
                        {{ $left['description'] }}
                    </p>
                @endif

                @if (count($leftPoints) > 0)
                    <ul class="mt-6 list-disc space-y-4 pl-5 text-base leading-relaxed text-foreground/70">
                        @foreach ($leftPoints as $p)
                            <li>{{ $p }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Box 2 -->
            <div class="rounded-2xl border p-8 shadow-sm transition hover:shadow-md sm:p-12 {{ $stripCardClass }} {{ $stripCardBorderClass }}">
                <h3 class="font-sans text-3xl font-bold text-secondary">
                    {{ filled($right['title'] ?? null) ? $right['title'] : 'Our Vision' }}
                </h3>

                @if (filled($right['description'] ?? null))
                    <p class="mt-6 text-base leading-relaxed text-foreground/70">
                        {{ $right['description'] }}
                    </p>
                @endif

                @if (count($rightPoints) > 0)
                    <ul class="mt-6 list-disc space-y-4 pl-5 text-base leading-relaxed text-foreground/70">
                        @foreach ($rightPoints as $p)
                            <li>{{ $p }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>

