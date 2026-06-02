@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($location->hero_title),
    'metaDescription' => $location->meta_description,
])

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        <div class="absolute inset-0">
            <img
                src="{{ $location->hero_background_url }}"
                alt=""
                class="h-full w-full object-cover opacity-60 mix-blend-overlay"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/90 via-secondary/60 to-transparent"></div>
        </div>
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $location->hero_title }}</h1>
            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm font-medium sm:text-base" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-white transition hover:text-primary">Home</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <a href="{{ route('about-us') }}" class="text-white transition hover:text-primary">About Us</a>
                <span class="text-primary" aria-hidden="true">/</span>
                <span class="text-primary">{{ $location->hero_title }}</span>
            </nav>
        </div>
    </section>

    <section class="where-location site-section bg-white">
        <div class="site-container">
            <div class="where-location__layout">
                @include('site.partials.where-we-are-location-sidebar', ['location' => $location])

                <div class="where-location__main">
                    <header class="where-location__header">
                        <p class="where-location__eyebrow">{{ $location->eyebrow }}</p>
                        <h2 class="where-location__title">{{ $location->hero_title }}</h2>
                    </header>

                    @if ($location->has_gallery)
                        <div class="where-location__gallery" role="group" aria-label="{{ $location->hero_title }} gallery">
                            @foreach ($location->gallery_image_urls as $index => $imageUrl)
                                <figure @class([
                                    'where-location__gallery-item',
                                    'where-location__gallery-item--lead' => $index === 0,
                                ])>
                                    <img src="{{ $imageUrl }}" alt="" loading="lazy" class="where-location__gallery-img">
                                </figure>
                            @endforeach
                        </div>
                    @endif

                    @if (filled($location->office_title) || count($location->body_paragraphs) > 0)
                        <article class="where-location__office">
                            @if (filled($location->office_title))
                                <h3 class="where-location__office-title">{{ $location->office_title }}</h3>
                            @endif

                            @if (count($location->body_paragraphs) > 0)
                                <div class="where-location__body">
                                    @foreach ($location->body_paragraphs as $paragraph)
                                        <p>
                                            {{ $paragraph }}
                                            @if ($loop->last && filled($location->body_link_label) && filled($location->body_link_href))
                                                <a href="{{ $location->body_link_href }}" class="where-location__inline-link">{{ $location->body_link_label }}</a>.
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            @endif

                        </article>
                    @endif

                    @if ($location->show_ara_map ?? false)
                        @include('site.partials.where-we-are-ara-overview-map', ['markers' => $location->ara_map_markers])
                    @endif

                    @include('site.partials.where-we-are-map-embed', ['map' => $location->map ?? null])

                    @if ($location->show_quality_block)
                        <section id="where-location-quality" class="where-location__quality" aria-labelledby="where-location-quality-title">
                            <hr class="where-location__rule">
                            <h3 id="where-location-quality-title" class="where-location__quality-title">{{ $location->quality_block_title }}</h3>

                            @if ($location->certificate_group)
                                <div class="where-location__quality-section">
                                    <h4 class="where-location__quality-subtitle">{{ $location->certificate_group->title }}</h4>
                                    @if (filled($location->quality_block_lead))
                                        <p class="where-location__quality-lead">{{ $location->quality_block_lead }}</p>
                                    @endif
                                    <div class="quality-certs__grid where-location__certs-grid" role="list">
                                        @foreach ($location->certificate_group->activeCertificates as $cert)
                                            @include('site.partials.quality-certificate-item', [
                                                'cert' => $cert,
                                                'variant' => 'grid',
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($location->membership_group)
                                <div class="where-location__quality-section">
                                    @if ($location->certificate_group)
                                        <hr class="where-location__rule where-location__rule--inner">
                                    @endif
                                    <h4 class="where-location__quality-subtitle">{{ $location->membership_group->title }}</h4>
                                    <div @class([
                                        'quality-certs__grid where-location__certs-grid' => $location->membership_group->isGridLayout(),
                                        'where-location__membership-row' => ! $location->membership_group->isGridLayout(),
                                    ]) role="list">
                                        @foreach ($location->membership_group->activeCertificates as $cert)
                                            @include('site.partials.quality-certificate-item', [
                                                'cert' => $cert,
                                                'variant' => $location->membership_group->isGridLayout() ? 'grid' : 'grid',
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (! $location->certificate_group && ! $location->membership_group)
                                @if (filled($location->quality_block_lead))
                                    <p class="where-location__quality-lead">{{ $location->quality_block_lead }}</p>
                                @endif
                                <a href="{{ $location->quality_certificates_href }}" class="where-location__quality-link">
                                    <span class="where-location__quality-card">
                                        <span class="where-location__quality-card-label">View quality certificates</span>
                                    </span>
                                </a>
                            @endif
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-scroll-to]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const id = link.getAttribute('data-scroll-to');
            const target = id ? document.getElementById(id) : null;
            if (target) {
                event.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
@endpush
