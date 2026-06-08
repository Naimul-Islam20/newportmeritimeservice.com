<aside class="blog-sidebar" aria-label="{{ $categorySub->label }} navigation">
    <div class="blog-sidebar__block">
        <h3 class="blog-sidebar__heading">{{ $categorySub->label }}</h3>
        <ul class="blog-sidebar__list">
            @if ($sidebarItems->isEmpty())
                <li>
                    <a href="{{ $categorySub->siteNavHref() }}"
                        @class([
                            'blog-sidebar__link',
                            'blog-sidebar__link--active' => ! $currentArticle,
                        ])>
                        {{ $categorySub->label }}
                    </a>
                </li>
            @endif
            @foreach ($sidebarItems as $item)
                @php
                    $isActive = $currentArticle && (int) $currentArticle->id === (int) $item->id;
                @endphp
                <li>
                    <a href="{{ $item->siteNavHref() }}"
                        @class([
                            'blog-sidebar__link',
                            'blog-sidebar__link--active' => $isActive,
                        ])>
                        {{ $item->label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if (! empty($recentlyViewed) && $recentlyViewed->isNotEmpty())
        <div class="blog-sidebar__block">
            <h3 class="blog-sidebar__heading">Recently Viewed</h3>
            <ul class="blog-sidebar__recent">
                @foreach ($recentlyViewed as $recent)
                    <li class="blog-sidebar__recent-item">
                        <a href="{{ $recent->siteNavHref() }}" class="blog-sidebar__recent-link">
                            @if ($recent->coverImageUrl() !== '')
                                <img src="{{ $recent->coverImageUrl() }}" alt="" class="blog-sidebar__recent-img" loading="lazy">
                            @endif
                            <span class="blog-sidebar__recent-text">
                                <span class="blog-sidebar__recent-title">{{ $recent->label }}</span>
                                @if ($recent->published_at)
                                    <span class="blog-sidebar__recent-date">{{ $recent->published_at->format('d M Y') }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! empty($blogTags) && count($blogTags) > 0)
        <div class="blog-sidebar__block">
            <h3 class="blog-sidebar__heading">Tags</h3>
            <div class="blog-sidebar__tags">
                @foreach ($blogTags as $tag)
                    <span class="blog-sidebar__tag">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
    @endif
</aside>
