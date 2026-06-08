{{-- Home: Mission & Vision (left) | image (right) --}}
@php
    $mv = \App\Models\AboutPage::missionVisionForPublic();
    $sectionImageUrl = $section->imagePublicUrl();
    $hasImage = $sectionImageUrl !== '';
    $imgUrl = $hasImage
        ? $sectionImageUrl
        : 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1200&auto=format&fit=crop';
    $hasMission = filled($mv->mission_body);
    $hasVision = filled($mv->vision_body);
    $hasContent = $hasMission || $hasVision;
@endphp

@if ($hasContent || $hasImage)
<section class="home-recruitment-split" aria-label="Mission and Vision">
    <div class="site-container home-recruitment-split__inner">
        <div class="home-recruitment-split__grid">
            @if ($hasContent)
                <div class="home-recruitment-split__content">
                    @if ($hasMission)
                        <div class="home-recruitment-split__block">
                            <h2 class="home-recruitment-split__subtitle">{{ $mv->mission_title }}</h2>
                            <div class="home-recruitment-split__body home-recruitment-split__body--after-subtitle">
                                <p>{{ $mv->mission_body }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($hasVision)
                        <div @class(['home-recruitment-split__block', 'home-recruitment-split__block--spaced' => $hasMission])>
                            <h3 class="home-recruitment-split__subtitle">{{ $mv->vision_title }}</h3>
                            <div class="home-recruitment-split__body home-recruitment-split__body--after-subtitle">
                                <p>{{ $mv->vision_body }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="home-recruitment-split__media">
                <img src="{{ $imgUrl }}" alt="Mission and Vision" class="home-recruitment-split__image" loading="lazy" decoding="async">
            </div>
        </div>
    </div>
</section>
@endif
