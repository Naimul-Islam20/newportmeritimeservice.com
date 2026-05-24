{{-- Home: 50% full-height image | 50% copy | YouTube play on center seam (Gimaş-style) --}}
@php
    $sectionData = is_array($section->data ?? null) ? $section->data : [];
    $layout = $section->layout_width ?: 'full';
    $sectionImageUrl = $section->imagePublicUrl();
    $hasImage = $sectionImageUrl !== '';
    $imgUrl = $hasImage
        ? $sectionImageUrl
        : 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop';
    $mini = filled($section->mini_title) ? trim($section->mini_title) : null;
    $title = filled($section->title) ? trim($section->title) : null;
    $descPlain = filled($section->description) ? trim(strip_tags((string) $section->description)) : '';
    $points = is_array($section->points ?? null)
        ? array_values(array_filter($section->points, fn ($v) => is_string($v) && trim($v) !== ''))
        : [];
    $imageSide = strtolower(trim((string) ($sectionData['image_side'] ?? 'left')));
    $imageOnRight = in_array($imageSide, ['right', '1', 'true', 'on'], true);
    $sectionVideo = $section->videoModalPayload();
    $hasVideo = $sectionVideo['type'] !== 'none';
    $modalId = 'home-section-video-'.$section->id;
    $sd = \App\Models\SiteDetail::query()->first();
    $social = is_array($sd?->social_links ?? null) ? $sd->social_links : [];
    $socialUrl = static function (?string $raw): ?string {
        $url = trim((string) ($raw ?? ''));
        if ($url === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return 'https://'.ltrim($url, '/');
    };
    $socialNetworks = [
        'facebook' => $socialUrl($social['facebook'] ?? null),
        'twitter' => $socialUrl($social['twitter'] ?? null),
        'linkedin' => $socialUrl($social['linkedin'] ?? null),
        'instagram' => $socialUrl($social['instagram'] ?? null),
    ];
    $hasContent = filled($mini) || filled($title) || $descPlain !== '' || count($points) > 0;
    $showLeft = $hasImage || $hasVideo;
@endphp

<section @class([
    'home-about-split',
    'home-about-split--short' => $layout === 'short',
])>
    <div class="home-about-split__wrap">
        <div @class([
            'home-about-split__grid',
            'home-about-split__grid--image-right' => $imageOnRight,
            'home-about-split__grid--content-only' => ! $showLeft,
        ])>
            @if ($showLeft)
                <div class="home-about-split__media">
                    @if ($hasImage)
                        <img src="{{ $imgUrl }}" alt="{{ $section->image_alt ?: '' }}" class="home-about-split__image" loading="lazy" decoding="async">
                    @else
                        <div class="home-about-split__media-placeholder" aria-hidden="true"></div>
                    @endif
                </div>
            @endif

            @if ($hasContent)
                <div class="home-about-split__content">
                    @if (filled($mini))
                        <p class="home-about-split__eyebrow">{{ $mini }}</p>
                    @endif
                    @if (filled($title))
                        <h2 class="home-about-split__title">{!! nl2br(e($title)) !!}</h2>
                    @endif
                    @if ($descPlain !== '')
                        <div class="home-about-split__body">
                            <p>{{ $descPlain }}</p>
                        </div>
                    @endif
                    @if (count($points) > 0)
                        <ul class="home-about-split__points">
                            @foreach ($points as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="home-about-split__socials">
                        @foreach ($socialNetworks as $key => $url)
                            @if (filled($url))
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="home-about-split__social" aria-label="{{ ucfirst($key) }}">
                                    @include('site.partials.footer-social-icon', ['icon' => $key])
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($hasVideo)
                <button type="button"
                    class="home-about-split__video-btn"
                    data-home-video-open="{{ $modalId }}"
                    data-embed="{{ e($sectionVideo['embed_url']) }}"
                    aria-label="Play video">
                    <svg class="home-about-split__video-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</section>

@if ($hasVideo)
    @include('site.partials.youtube-video-modal', ['modalId' => $modalId])

    @once
        @push('scripts')
        <script>
            (() => {
                if (window.__homeSectionVideoModalBound) return;
                window.__homeSectionVideoModalBound = true;

                document.addEventListener('click', (e) => {
                    const openBtn = e.target.closest('[data-home-video-open]');
                    if (openBtn) {
                        const modalId = openBtn.getAttribute('data-home-video-open');
                        const embed = openBtn.getAttribute('data-embed') || '';
                        const modal = document.getElementById(modalId);
                        const iframe = modal?.querySelector('[data-youtube-iframe]');
                        if (!modal || !iframe || !embed) return;
                        const sep = embed.includes('?') ? '&' : '?';
                        iframe.src = embed + sep + 'autoplay=0';
                        modal.classList.add('flex');
                        modal.classList.remove('hidden');
                        modal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                        return;
                    }

                    const closeBtn = e.target.closest('[data-youtube-modal-close]');
                    if (closeBtn) {
                        const modal = closeBtn.closest('[data-youtube-modal]');
                        const iframe = modal?.querySelector('[data-youtube-iframe]');
                        if (iframe) iframe.src = '';
                        modal?.classList.add('hidden');
                        modal?.classList.remove('flex');
                        modal?.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        return;
                    }

                    const backdrop = e.target.closest('[data-youtube-modal]');
                    if (backdrop && e.target === backdrop) {
                        const iframe = backdrop.querySelector('[data-youtube-iframe]');
                        if (iframe) iframe.src = '';
                        backdrop.classList.add('hidden');
                        backdrop.classList.remove('flex');
                        backdrop.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    document.querySelectorAll('[data-youtube-modal].flex').forEach((modal) => {
                        const iframe = modal.querySelector('[data-youtube-iframe]');
                        if (iframe) iframe.src = '';
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        modal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    });
                });
            })();
        </script>
        @endpush
    @endonce
@endif
