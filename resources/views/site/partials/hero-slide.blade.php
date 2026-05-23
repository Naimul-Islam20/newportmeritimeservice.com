@php
    $heroTitlePlain = preg_replace('/\s+/u', ' ', trim($title ?? ''));
    $heroButtonLabel = filled($buttonLabel ?? null) ? trim($buttonLabel) : 'EXPLORE NOW';
    $heroButtonHref = $buttonHref ?? '#';
    $heroImageUrl = $imageUrl ?? '';
    $heroImageAlt = $imageAlt ?? $heroTitlePlain;
@endphp
<div class="swiper-slide hero-slide">
    <div class="hero-slide__media">
        @if ($heroImageUrl !== '')
            <img src="{{ $heroImageUrl }}" alt="{{ $heroImageAlt }}" class="hero-slide__image">
        @else
            <div class="hero-slide__image hero-slide__image--placeholder" role="img" aria-label="{{ $heroImageAlt }}"></div>
        @endif
        <div class="hero-slide__overlay" aria-hidden="true"></div>
    </div>
    <div class="hero-slide__content">
        <div class="site-container hero-slide__container">
            <div class="hero-slide__copy">
                <h1 class="hero-slide__title" title="{{ $heroTitlePlain }}">
                    <span class="hero-slide__title-mobile">{{ $heroTitlePlain }}</span>
                    <span class="hero-slide__title-desktop">{!! nl2br(e($title ?? '')) !!}</span>
                </h1>
                @if ($showCta ?? filled($buttonLabel ?? null))
                    <a href="{{ $heroButtonHref }}" class="hero-slide__cta">{{ $heroButtonLabel }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
