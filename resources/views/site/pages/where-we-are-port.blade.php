@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle($port->title),
    'metaDescription' => $port->meta_description,
])

@section('content')
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-secondary sm:h-[400px]">
        @include('site.partials.page-hero-media', ['imageUrl' => $port->hero_background_url])
        <div class="relative z-10 site-container">
            <h1 class="font-sans text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                We are in {{ $port->hero_title }}!
            </h1>
            @include('site.partials.page-hero-crumbs', [
                'items' => [
                    ['label' => 'Home', 'url' => route('home')],
                    ['label' => 'Locations', 'url' => route('locations')],
                    ['label' => strtoupper($port->title)],
                ],
            ])
        </div>
    </section>

    <section class="where-location site-section bg-white">
        <div class="site-container">
            <div class="where-location__layout">
                @include('site.partials.where-we-are-location-sidebar', ['location' => $port])

                <div class="where-location__main">
                    <header class="where-location__header">
                        <p class="where-location__eyebrow">{{ $port->eyebrow }}</p>
                        <h2 class="where-location__title">{{ $port->title }}</h2>
                    </header>

                    @if (count($port->body_paragraphs) > 0)
                        <article class="where-location__office">
                            <div class="where-location__body">
                                @foreach ($port->body_paragraphs as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                                @if (filled($port->footer_link_label) && filled($port->footer_link_href))
                                    <p>
                                        <a href="{{ $port->footer_link_href }}" class="where-location__inline-link">{{ $port->footer_link_label }}</a>.
                                    </p>
                                @endif
                            </div>
                        </article>
                    @endif

                    @if ($port->show_ara_map ?? false)
                        @include('site.partials.where-we-are-ara-overview-map', ['markers' => $port->ara_map_markers])
                    @endif

                    @include('site.partials.where-we-are-map-embed', ['map' => $port->map ?? null])
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
