@extends('site.layouts.app', [
'title' => $title,
'metaDescription' => $metaDescription ?? null,
])

@section('content')
<section class="relative flex h-[280px] items-center overflow-hidden bg-slate-900 sm:h-[340px] md:h-[380px]">
    <div class="absolute inset-0">
        <img src="{{ asset('menu-page-cover.jpg') }}" alt="" class="h-full w-full object-cover opacity-70">
        <div class="absolute inset-0 bg-[#01223b]/65"></div>
    </div>

    <div class="relative site-container w-full py-10 sm:py-14">
        <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white drop-shadow-2xl sm:text-5xl">
            {{ $heading }}
        </h1>
        @if (! empty($lead))
        <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/90 drop-shadow">
            {{ $lead }}
        </p>
        @endif
    </div>
</section>

@if (isset($submenuPaginator) && $submenuPaginator->total() > 0)
    @include('site.partials.menu-submenu-grid', ['submenuPaginator' => $submenuPaginator])
@endif

@foreach (($pageSections ?? []) as $section)
    @if ($section->type === 'two_column_image_details')
        @include('site.menu-page-sections.two-column-image-details', ['section' => $section])
    @elseif ($section->type === 'two_column_two_side_details')
        @include('site.menu-page-sections.two-column-two-side-details', ['section' => $section])
    @elseif ($section->type === 'image')
        @include('site.menu-page-sections.image-block', ['section' => $section])
    @elseif ($section->type === 'text_input')
        @include('site.menu-page-sections.text-input', ['section' => $section])
    @endif
@endforeach

@if (filled($pageContent ?? null))
    <section class="bg-white py-14 sm:py-20">
        <div class="site-container max-w-3xl">
            <div class="text-base leading-relaxed text-slate-700">
                {!! nl2br(e($pageContent)) !!}
            </div>
        </div>
    </section>
@endif
@endsection