@if ($articles->isNotEmpty())
    <div class="blog-recipes-grid">
        @foreach ($articles as $recipe)
            @php
                $href = $recipe->siteNavHref();
                $img = $recipe->coverImageUrl();
            @endphp
            <article class="blog-recipes-card">
                <a href="{{ $href }}" class="blog-recipes-card__link">
                    @if ($img !== '')
                        <div class="blog-recipes-card__media">
                            <img src="{{ $img }}" alt="" class="blog-recipes-card__img" loading="lazy">
                            <div class="blog-recipes-card__overlay" aria-hidden="true"></div>
                        </div>
                    @endif
                    <div class="blog-recipes-card__body">
                        <p class="blog-recipes-card__eyebrow">Recipes</p>
                        <h3 class="blog-recipes-card__title">{{ $recipe->label }}</h3>
                        <span class="blog-recipes-card__cta">View details</span>
                    </div>
                </a>
            </article>
        @endforeach
    </div>
@endif
