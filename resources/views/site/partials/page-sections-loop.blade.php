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
