<article class="blog-article">
    @if ($article->coverImageUrl() !== '')
        <div class="blog-article__hero">
            <img src="{{ $article->coverImageUrl() }}" alt="{{ $article->label }}" class="blog-article__hero-img" loading="lazy">
        </div>
    @endif

    <header class="blog-article__header">
        @if ($article->published_at)
            <p class="blog-article__meta">{{ $article->published_at->format('d M Y') }}</p>
        @endif
        <h1 class="blog-article__title">{{ $article->label }}</h1>
    </header>

    @if (filled($article->page_content))
        <div class="blog-article__body prose max-w-none">
            {!! nl2br(e($article->page_content)) !!}
        </div>
    @endif
</article>
