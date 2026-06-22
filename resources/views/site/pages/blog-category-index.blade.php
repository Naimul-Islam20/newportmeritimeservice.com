@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    @include('site.partials.menu-page-hero', [
        'heading' => $heading ?? '',
        'lead' => $lead ?? null,
        'heroImageUrl' => $heroImageUrl ?? null,
        'breadcrumbs' => $breadcrumbs ?? null,
    ])

    @if (isset($blogNavMenu) && $blogNavMenu)
        @include('site.partials.blog-nav-tabs', ['blogNavMenu' => $blogNavMenu])
    @endif

    @if ($layoutType === 'sidebar_article' && $categorySub->usesBlogSidebar())
        <section class="blog-gimas bg-white py-10 sm:py-14">
            <div class="site-container blog-gimas__grid">
                @include('site.partials.blog-category-sidebar', [
                    'categorySub' => $categorySub,
                    'sidebarItems' => $articles,
                    'currentArticle' => $featuredArticle,
                    'recentlyViewed' => $recentlyViewed ?? collect(),
                    'blogTags' => $blogTags ?? [],
                ])

                <div class="blog-gimas__main">
                    @if ($featuredArticle)
                        @include('site.partials.blog-article-body', ['article' => $featuredArticle])
                    @else
                        <p class="blog-gimas__empty">No {{ strtolower($heading ?? 'news') }} published yet.</p>
                    @endif
                </div>
            </div>
        </section>
    @else
        <section class="blog-gimas bg-white py-10 sm:py-14">
            <div class="site-container">
                @if ($layoutType === 'recipes')
                    @include('site.partials.blog-recipes-grid', ['articles' => $articles])
                    @if ($articles->isEmpty())
                        <p class="blog-gimas__empty">No recipes published yet.</p>
                    @endif
                @elseif ($layoutType === 'gallery')
                    @include('site.partials.page-sections-loop', ['pageSections' => $pageSections ?? []])
                    @if (filled($pageContent ?? null))
                        <div class="max-w-3xl py-8">
                            <div class="blog-article__body">{!! nl2br(e($pageContent)) !!}</div>
                        </div>
                    @endif
                    @if ($articles->isNotEmpty())
                        <div class="py-8">
                            @include('site.partials.category-posts-grid', ['articles' => $articles])
                        </div>
                    @endif
                @elseif ($layoutType === 'sidebar_content')
                    @if (filled($pageContent ?? null))
                        <div class="blog-article__body prose max-w-none mb-8 max-w-3xl">
                            {!! nl2br(e($pageContent)) !!}
                        </div>
                    @endif
                    @include('site.partials.page-sections-loop', ['pageSections' => $pageSections ?? []])
                    @if ($articles->isEmpty() && empty($pageSections?->count()) && ! filled($pageContent ?? null))
                        <p class="blog-gimas__empty">No content published yet.</p>
                    @endif
                @else
                    @include('site.partials.category-posts-grid', ['articles' => $articles])
                    @if ($articles->isEmpty())
                        <p class="blog-gimas__empty">No {{ strtolower($heading ?? 'items') }} published yet.</p>
                    @endif
                @endif
            </div>
        </section>
    @endif
@endsection
