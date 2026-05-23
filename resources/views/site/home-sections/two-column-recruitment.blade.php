{{-- Home: text left | image right | dual CTAs (Recruitment-style) --}}
@php
    $sectionData = is_array($section->data ?? null) ? $section->data : [];
    $sectionImageUrl = $section->imagePublicUrl();
    $hasImage = $sectionImageUrl !== '';
    $imgUrl = $hasImage
        ? $sectionImageUrl
        : 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1200&auto=format&fit=crop';
    $title = filled($section->title) ? trim($section->title) : null;
    $descPlain = filled($section->description) ? trim(strip_tags((string) $section->description)) : '';
    $secondaryRaw = data_get($sectionData, 'secondary_description');
    $secondaryHtml = is_string($secondaryRaw) && trim($secondaryRaw) !== ''
        ? strip_tags(trim($secondaryRaw), '<strong><b><a><br><em>')
        : '';
    $btnPrimaryLabel = filled($section->button_label) ? trim($section->button_label) : null;
    $btnPrimaryHref = $section->resolvedButtonHref();
    $btnSecondaryLabel = filled(data_get($sectionData, 'secondary_button_label'))
        ? trim((string) data_get($sectionData, 'secondary_button_label'))
        : null;
    $btnSecondaryHref = $section->resolvedButtonHrefFor(data_get($sectionData, 'secondary_button_url'));
    $showPrimaryBtn = filled($btnPrimaryLabel);
    $showSecondaryBtn = filled($btnSecondaryLabel);
    $hasContent = filled($title) || $descPlain !== '' || $secondaryHtml !== '' || $showPrimaryBtn || $showSecondaryBtn;
@endphp

@if ($hasContent || $hasImage)
<section class="home-recruitment-split" aria-labelledby="home-recruitment-title-{{ $section->id }}">
    <div class="site-container home-recruitment-split__inner">
        <div class="home-recruitment-split__grid">
            @if ($hasContent)
                <div class="home-recruitment-split__content">
                    @if (filled($title))
                        <h2 id="home-recruitment-title-{{ $section->id }}" class="home-recruitment-split__title">{!! nl2br(e($title)) !!}</h2>
                    @endif
                    @if ($descPlain !== '')
                        <div class="home-recruitment-split__body">
                            <p>{{ $descPlain }}</p>
                        </div>
                    @endif
                    @if ($secondaryHtml !== '')
                        <div class="home-recruitment-split__body home-recruitment-split__body--secondary">
                            {!! $secondaryHtml !!}
                        </div>
                    @endif
                    @if ($showPrimaryBtn || $showSecondaryBtn)
                        <div class="home-recruitment-split__actions">
                            @if ($showPrimaryBtn)
                                <a href="{{ $btnPrimaryHref }}" class="home-recruitment-split__btn">{{ $btnPrimaryLabel }}</a>
                            @endif
                            @if ($showSecondaryBtn)
                                <a href="{{ $btnSecondaryHref }}" class="home-recruitment-split__btn">{{ $btnSecondaryLabel }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="home-recruitment-split__media">
                <img src="{{ $imgUrl }}" alt="{{ $section->image_alt ?: ($title ?? 'Recruitment') }}" class="home-recruitment-split__image" loading="lazy" decoding="async">
            </div>
        </div>
    </div>
</section>
@endif

