@php
    $d = is_array($section->data ?? null) ? $section->data : [];
    $mini = filled(data_get($d, 'mini_title')) ? trim(data_get($d, 'mini_title')) : null;
    $title = is_string($section->title ?? null) && trim($section->title) !== '' ? trim($section->title) : null;
    $desc = filled(data_get($d, 'description')) ? trim(data_get($d, 'description')) : null;
    $bottom = filled(data_get($d, 'bottom_description')) ? trim(data_get($d, 'bottom_description')) : null;
    $imagePath = data_get($d, 'image_path');
    $imageUrl = public_upload_url(is_string($imagePath) ? $imagePath : null);
    $points = is_array(data_get($d, 'points')) ? array_values(array_filter(data_get($d, 'points'), fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $hasImage = $imageUrl !== '';
    $hasContent = filled($mini) || filled($title) || filled($desc) || $hasImage || count($points) > 0 || filled($bottom);
    $hasTitleBlock = filled($mini) || filled($title);
    $bodyClass = 'image-details-body text-base leading-relaxed sm:text-lg';
    $descMargin = $hasTitleBlock ? 'mt-8' : 'mt-0';
    $afterDesc = filled($desc) || $hasTitleBlock;
    $imageMargin = $afterDesc ? 'mt-8' : 'mt-0';
    $pointsMargin = ($afterDesc || $hasImage) ? 'mt-8' : 'mt-0';
    $bottomMargin = ($afterDesc || $hasImage || count($points) > 0) ? 'mt-8' : 'mt-0';
@endphp
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))

@if ($hasContent)
<section class="{{ $stripSectionClass }} site-section">
    <div class="site-container">
        @if ($hasTitleBlock)
            <div class="mx-auto max-w-3xl text-center">
                @if (filled($mini))
                    <p class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $mini }}</p>
                @endif
                @if (filled($title))
                    <h2 class="mt-3 font-sans text-4xl font-bold leading-tight {{ $stripTitleClass }} sm:text-5xl">
                        {{ $title }}
                    </h2>
                @endif
            </div>
        @endif

        @if (filled($desc))
            <p class="{{ $descMargin }} text-start {{ $bodyClass }}">
                {!! nl2br(e($desc)) !!}
            </p>
        @endif

        @if ($hasImage)
            <figure class="{{ $imageMargin }} overflow-hidden rounded-lg {{ $stripCardClass }} shadow-lg shadow-secondary/10">
                <div class="bg-foreground/5">
                    <img src="{{ $imageUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                </div>
            </figure>
        @endif

        @if (count($points) > 0)
            <ul class="{{ $pointsMargin }} list-disc space-y-3 pl-5 text-start {{ $bodyClass }}">
                @foreach ($points as $p)
                    <li>{{ $p }}</li>
                @endforeach
            </ul>
        @endif

        @if (filled($bottom))
            <p class="{{ $bottomMargin }} text-start {{ $bodyClass }}">
                {!! nl2br(e($bottom)) !!}
            </p>
        @endif
    </div>
</section>
@endif
