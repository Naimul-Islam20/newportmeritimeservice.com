@extends('site.layouts.app', [
'title' => $title,
'metaDescription' => $metaDescription ?? null,
])

@section('content')
@include('site.partials.menu-page-hero', [
    'heading' => $heading ?? '',
    'lead' => $lead ?? null,
    'heroImageUrl' => $heroImageUrl ?? null,
])

@if (isset($submenuPaginator) && $submenuPaginator->total() > 0)
    @include('site.partials.menu-submenu-grid', ['submenuPaginator' => $submenuPaginator])
@endif

@include('site.partials.page-sections-loop', ['pageSections' => $pageSections ?? []])

@if (filled($pageContent ?? null))
    <section class="bg-white py-14 sm:py-20">
        <div class="site-container max-w-3xl">
            <div class="text-base leading-relaxed text-foreground/80">
                {!! nl2br(e($pageContent)) !!}
            </div>
        </div>
    </section>
@endif
@endsection