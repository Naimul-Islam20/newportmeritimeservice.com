@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    @include('site.partials.menu-page-hero', [
        'heading' => $heading ?? '',
        'lead' => $lead ?? null,
        'heroImageUrl' => $heroImageUrl ?? null,
    ])

    @if (isset($blogNavMenu) && $blogNavMenu)
        @include('site.partials.blog-nav-tabs', ['blogNavMenu' => $blogNavMenu])
    @endif

    <section class="bg-white py-10 sm:py-14">
        <div class="site-container grid gap-10 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)]">
            <aside aria-label="News list" class="blog-news-sidebar">
                <div class="blog-news-sidebar__section">
                    <h2 class="blog-news-sidebar__heading">{{ strtoupper($categorySub->label ?? 'News') }}</h2>
                    <ul class="blog-news-sidebar__list">
                        @foreach ($articles as $article)
                            @php
                                $isActive = $currentArticle && (int) $currentArticle->id === (int) $article->id;
                            @endphp
                            <li>
                                <a href="{{ $article->siteNavHref() }}"
                                    @class([
                                        'blog-news-sidebar__item',
                                        'blog-news-sidebar__item--active' => $isActive,
                                    ])>
                                    {{ $article->label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <article class="blog-article">
                @if ($currentArticle?->coverImageUrl())
                    <div class="blog-article__hero">
                        <img src="{{ $currentArticle->coverImageUrl() }}" alt="{{ $currentArticle->label }}"
                            class="blog-article__hero-img">
                    </div>
                @endif

                <header class="blog-article__header">
                    @if ($currentArticle?->published_at)
                        <p class="blog-article__meta">
                            {{ $currentArticle->published_at->format('d M Y') }}
                        </p>
                    @endif
                    <h1 class="blog-article__title">
                        {{ $currentArticle->label ?? $heading ?? '' }}
                    </h1>
                </header>

                @if (filled($currentArticle?->page_content))
                    <div class="blog-article__body prose max-w-none">
                        {!! nl2br(e($currentArticle->page_content)) !!}
                    </div>
                @elseif (filled($pageContent ?? null))
                    <div class="blog-article__body prose max-w-none">
                        {!! nl2br(e($pageContent)) !!}
                    </div>
                @endif
            </article>
        </div>
    </section>
@endsection

