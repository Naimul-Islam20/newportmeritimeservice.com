@php
    $heroHeading = $heading ?? '';
    $heroLeadRaw = $lead ?? null;
    $heroLeadTitle = is_string($heroLeadRaw) ? trim(strip_tags($heroLeadRaw)) : '';
    $heroLead = $heroLeadTitle !== '' ? trim(preg_replace('/\s+/u', ' ', $heroLeadTitle)) : null;
    if (filled($heroLead) && mb_strlen($heroLead) > 275) {
        $heroLead = \Illuminate\Support\Str::limit($heroLead, 275, '...');
    }
    $heroImage = $heroImageUrl ?? asset('menu-page-cover.jpg');
@endphp

<section class="menu-page-hero relative h-[300px] w-full overflow-hidden bg-secondary">
    <div class="pointer-events-none absolute inset-0 z-0">
        <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-70">
        <div class="absolute inset-0 bg-secondary/65"></div>
    </div>

    <div class="menu-page-hero__content site-container absolute inset-0 z-10">
        @if (filled($heroHeading))
            <h1 class="menu-page-hero__title" title="{{ $heroHeading }}">{{ $heroHeading }}</h1>
        @endif
        @if (filled($heroLead))
            <p class="menu-page-hero__lead" title="{{ $heroLeadTitle }}">{{ $heroLead }}</p>
        @endif
    </div>
</section>
