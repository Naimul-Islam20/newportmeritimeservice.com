{{-- News / Events / Gallery … listing (same cards as homepage latest news) --}}
@if ($articles->isNotEmpty())
    <section class="home-latest-news site-section !bg-white !py-10 sm:!py-14">
        <div class="site-container">
            <div class="home-latest-news__grid">
                @foreach ($articles as $post)
                    @php
                        $postHref = $post->siteNavHref();
                        $imgUrl = $post->coverImageUrl();
                        $descRaw = trim(strip_tags((string) ($post->description ?? '')));
                        if ($descRaw === '' && filled($post->page_content)) {
                            $descRaw = trim(strip_tags((string) $post->page_content));
                        }
                        $excerpt = $descRaw !== ''
                            ? \Illuminate\Support\Str::limit(preg_replace('/\s+/u', ' ', $descRaw), 160)
                            : '';
                    @endphp
                    <article class="home-latest-news__card">
                        @if ($imgUrl !== '')
                            <a href="{{ $postHref }}" class="home-latest-news__media" tabindex="-1" aria-hidden="true">
                                <img src="{{ $imgUrl }}" alt="" class="home-latest-news__img" loading="lazy">
                            </a>
                        @endif
                        <div class="home-latest-news__body">
                            @if ($post->published_at)
                                <p class="home-latest-news__date">
                                    {{ $post->published_at->format('d M Y') }}<span aria-hidden="true"> |</span>
                                </p>
                            @endif
                            <h3 class="home-latest-news__card-title">
                                <a href="{{ $postHref }}">{{ $post->label }}</a>
                            </h3>
                            @if ($excerpt !== '')
                                <div class="home-latest-news__excerpt-wrap">
                                    <p class="home-latest-news__excerpt">{{ $excerpt }}</p>
                                </div>
                            @endif
                            <a href="{{ $postHref }}" class="home-latest-news__read-more">
                                <span>Read more</span>
                                <span class="home-latest-news__read-more-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round">
                                        <path d="M12 5v14M5 12h14" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
