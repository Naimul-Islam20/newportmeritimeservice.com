@php
    $heroHeading = $heading ?? '';
    $heroLeadRaw = $lead ?? null;
    $heroLeadTitle = is_string($heroLeadRaw) ? trim(strip_tags($heroLeadRaw)) : '';
    $heroLead = $heroLeadTitle !== '' ? trim(preg_replace('/\s+/u', ' ', $heroLeadTitle)) : null;
    if (filled($heroLead) && mb_strlen($heroLead) > 275) {
        $heroLead = \Illuminate\Support\Str::limit($heroLead, 275, '...');
    }
    $heroImage = filled($heroImageUrl ?? null)
        ? $heroImageUrl
        : \App\Models\SubMenu::defaultPageHeroBackgroundUrl();
@endphp

<section class="menu-page-hero service-detail-hero relative flex min-h-[300px] w-full items-center overflow-hidden bg-secondary sm:min-h-[400px]">
    @include('site.partials.page-hero-media', ['imageUrl' => $heroImage])

    <div class="relative z-10 site-container">
        @if (filled($heroHeading))
            <h1 class="menu-page-hero__title">{{ $heroHeading }}</h1>
        @endif
        @if (filled($heroLead))
            <p class="menu-page-hero__lead" title="{{ $heroLeadTitle }}">{{ $heroLead }}</p>
        @endif
    </div>
</section>
