<aside class="service-detail__sidebar" aria-label="Page sidebar">
    <div class="service-detail__sidebar-upper">
        <div class="service-detail__widget service-detail__widget--nav">
            <h2 class="service-detail__widget-title">{{ $sidebar->categories_title }}</h2>
            @include('site.partials.service-categories-nav', [
                'serviceCategoryGroups' => $sidebar->groups,
                'serviceCategoryLinks' => $sidebar->links,
            ])
        </div>

        <div class="service-detail__widget service-detail__widget--panel">
            <h2 class="service-detail__widget-title service-detail__widget-title--bar">{{ $sidebar->spare_parts_title }}</h2>
            @if (filled($sidebar->spare_parts_text))
                <p class="service-detail__widget-text">{{ $sidebar->spare_parts_text }}</p>
            @endif
            <a href="{{ route('quote.request') }}" class="service-detail__btn service-detail__btn--accent">{{ $sidebar->spare_parts_button_label }}</a>
        </div>

        <div class="service-detail__widget service-detail__widget--panel">
            <h2 class="service-detail__widget-title service-detail__widget-title--bar">{{ $sidebar->brochures_title }}</h2>
            @if (filled($sidebar->brochures_text))
                <p class="service-detail__widget-text">{{ $sidebar->brochures_text }}</p>
            @endif
            <a href="{{ $sidebar->brochure_url }}" class="service-detail__download" @if($sidebar->brochure_url !== '#') target="_blank" rel="noopener" @endif>
                <span>{{ $sidebar->brochure_label }}</span>
                <span class="service-detail__download-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3v12M8 11l4 4 4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <div class="service-detail__sidebar-quote">
        <div class="service-detail__widget service-detail__widget--quote">
            <h2 class="service-detail__widget-title service-detail__widget-title--bar">{{ $sidebar->quote_title }}</h2>
            <form action="{{ route('quote.request') }}" method="get" class="service-detail__quote-form">
                <label class="service-detail__quote-field">
                    <span class="sr-only">Name</span>
                    <input type="text" name="name" placeholder="Name" autocomplete="given-name">
                </label>
                <label class="service-detail__quote-field">
                    <span class="sr-only">Surname</span>
                    <input type="text" name="surname" placeholder="Surname" autocomplete="family-name">
                </label>
                <label class="service-detail__quote-field">
                    <span class="sr-only">Company</span>
                    <input type="text" name="company" placeholder="Company" autocomplete="organization">
                </label>
                <label class="service-detail__quote-field">
                    <span class="sr-only">Email address</span>
                    <input type="email" name="email" placeholder="Email Address" autocomplete="email">
                </label>
                <button type="submit" class="service-detail__btn service-detail__btn--accent service-detail__btn--block">Get a quote</button>
            </form>
        </div>
    </div>
</aside>
