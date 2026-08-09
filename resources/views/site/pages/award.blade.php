@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
@include('site.partials.menu-page-hero', [
    'heading' => $heading ?? 'Award',
    'lead' => $lead ?? null,
    'heroImageUrl' => $heroImageUrl ?? null,
    'breadcrumbs' => $breadcrumbs ?? null,
])

<section class="bg-white site-section">
    <div class="site-container">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach ($awardSubMenus as $item)
                @php($hasDesc = filled($item->description))
                <a href="{{ $item->siteNavHref() }}"
                    class="group relative flex h-[380px] w-full flex-col overflow-hidden rounded-xl bg-secondary p-8 shadow-lg no-underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                    <img src="{{ $item->pageHeroBackgroundUrl() }}"
                        class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110"
                        alt="{{ $item->label }}">
                    <div class="absolute inset-0 bg-secondary/28 mix-blend-multiply"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-secondary/35 via-secondary/18 to-secondary/35"></div>

                    <div @class([
                        'relative z-10 flex h-full flex-col',
                        'justify-between' => $hasDesc,
                        'justify-end gap-4' => ! $hasDesc,
                    ])>
                        @if ($hasDesc)
                            <h2 class="text-2xl font-bold uppercase leading-snug text-white">{{ $item->label }}</h2>
                            <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-white/90">
                                {{ \Illuminate\Support\Str::limit($item->description, 140) }}
                            </p>
                            <span class="mt-auto font-bold text-white transition group-hover:text-primary">View details</span>
                        @else
                            <h2 class="text-2xl font-bold uppercase leading-snug text-white">{{ $item->label }}</h2>
                            <span class="font-bold text-white transition group-hover:text-primary">View details</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
