{{-- Home: Mission & Vision showcase cards --}}
@php
    $mv = \App\Models\AboutPage::missionVisionForPublic();
    $missionImageUrl = \App\Models\AboutPage::missionImageForPublic();
    $visionImageUrl = \App\Models\AboutPage::visionImageForPublic();
    $sectionImageUrl = $section->imagePublicUrl();
    $missionImg = $missionImageUrl !== ''
        ? $missionImageUrl
        : ($sectionImageUrl !== ''
            ? $sectionImageUrl
            : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=1200&auto=format&fit=crop');
    $visionImg = $visionImageUrl !== ''
        ? $visionImageUrl
        : 'https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=1200&auto=format&fit=crop';
    $hasMission = filled($mv->mission_body);
    $hasVision = filled($mv->vision_body);
    $hasContent = $hasMission || $hasVision;
@endphp

@if ($hasContent)
<section class="home-mv-showcase" aria-label="Mission and Vision">
    <div class="site-container home-mv-showcase__inner">
        <header class="home-mv-showcase__header">
            <p class="home-mv-showcase__eyebrow">Our Purpose</p>
            <div class="home-mv-showcase__anchor" aria-hidden="true">
                <span class="home-mv-showcase__anchor-line"></span>
                <svg class="home-mv-showcase__anchor-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22V8" />
                    <path d="M5 12H2a10 10 0 0 0 20 0h-3" />
                    <circle cx="12" cy="5" r="3" />
                </svg>
                <span class="home-mv-showcase__anchor-line"></span>
            </div>
            <h2 class="home-mv-showcase__title">Our Mission &amp; Vision</h2>
            <p class="home-mv-showcase__intro">Guiding principles that drive our maritime excellence.</p>
        </header>

        <div class="home-mv-showcase__cards">
            @if ($hasMission)
                <article class="home-mv-card home-mv-card--mission">
                    <div class="home-mv-card__content">
                        <div class="home-mv-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M12 2v2M12 20v2M2 12h2M20 12h2" />
                            </svg>
                        </div>
                        <p class="home-mv-card__label">Our Mission</p>
                        <h3 class="home-mv-card__heading">{{ $mv->mission_title }}</h3>
                        <div class="home-mv-card__body">
                            <p>{{ $mv->mission_body }}</p>
                        </div>
                    </div>
                    <div class="home-mv-card__media">
                        <img src="{{ $missionImg }}" alt="{{ $mv->mission_title }}" class="home-mv-card__image" loading="lazy" decoding="async">
                    </div>
                </article>
            @endif

            @if ($hasVision)
                <article class="home-mv-card home-mv-card--vision">
                    <div class="home-mv-card__content">
                        <div class="home-mv-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 11h2l2-5h10l2 5h2" />
                                <circle cx="7" cy="15" r="3" />
                                <circle cx="17" cy="15" r="3" />
                                <path d="M10 15h4" />
                            </svg>
                        </div>
                        <p class="home-mv-card__label">Our Vision</p>
                        <h3 class="home-mv-card__heading">{{ $mv->vision_title }}</h3>
                        <div class="home-mv-card__body">
                            <p>{{ $mv->vision_body }}</p>
                        </div>
                    </div>
                    <div class="home-mv-card__media">
                        <img src="{{ $visionImg }}" alt="{{ $mv->vision_title }}" class="home-mv-card__image" loading="lazy" decoding="async">
                    </div>
                </article>
            @endif
        </div>
    </div>
</section>
@endif
