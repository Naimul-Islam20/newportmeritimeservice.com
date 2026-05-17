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

@foreach (($pageSections ?? []) as $section)
    @php($sectionStrip = $loop->index % 2 === 0 ? 'primary' : 'secondary')
    @if ($section->type === 'two_column_image_details')
        @include('site.menu-page-sections.two-column-image-details', ['section' => $section, 'sectionStrip' => $sectionStrip])
    @elseif ($section->type === 'two_column_two_side_details')
        @include('site.menu-page-sections.two-column-two-side-details', ['section' => $section, 'sectionStrip' => $sectionStrip])
    @elseif ($section->type === 'image')
        @include('site.menu-page-sections.image-block', ['section' => $section, 'sectionStrip' => $sectionStrip])
    @elseif ($section->type === 'text_input')
        @include('site.menu-page-sections.text-input', ['section' => $section, 'sectionStrip' => $sectionStrip])
    @endif
@endforeach

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