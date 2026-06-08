@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    @include('site.partials.menu-page-hero', [
        'heading' => $heading ?? $categorySub->label ?? '',
        'lead' => $lead ?? $categorySub->description ?? null,
        'heroImageUrl' => $heroImageUrl ?? null,
    ])

    @if (isset($blogNavMenu) && $blogNavMenu)
        @include('site.partials.blog-nav-tabs', ['blogNavMenu' => $blogNavMenu])
    @endif

    @if (($layoutType ?? 'sidebar_article') === 'recipes')
        <section class="blog-gimas blog-gimas--recipes bg-white py-10 sm:py-14">
            <div class="site-container">
                @include('site.partials.blog-article-body', ['article' => $currentArticle])
            </div>
        </section>
    @elseif ($categorySub->usesBlogSidebar())
        <section class="blog-gimas bg-white py-10 sm:py-14">
            <div class="site-container blog-gimas__grid">
                @include('site.partials.blog-category-sidebar', [
                    'categorySub' => $categorySub,
                    'sidebarItems' => $sidebarItems,
                    'currentArticle' => $currentArticle,
                    'recentlyViewed' => $recentlyViewed ?? collect(),
                    'blogTags' => $blogTags ?? [],
                ])

                <div class="blog-gimas__main">
                    @include('site.partials.blog-article-body', ['article' => $currentArticle])
                    @if (($layoutType ?? '') === 'sidebar_article')
                        @include('site.partials.blog-article-comments', ['article' => $currentArticle])
                    @endif
                </div>
            </div>
        </section>
    @else
        <section class="bg-white py-10 sm:py-14">
            <div class="site-container max-w-4xl">
                @include('site.partials.blog-article-body', ['article' => $currentArticle])
            </div>
        </section>
    @endif
@endsection
