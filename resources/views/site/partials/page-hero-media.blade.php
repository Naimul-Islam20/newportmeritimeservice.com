@php
    $heroMediaImage = $imageUrl ?? '';
    $heroMediaAlt = $imageAlt ?? '';
@endphp

@if ($heroMediaImage !== '')
    <div class="menu-page-hero__media absolute inset-0">
        <img src="{{ $heroMediaImage }}" alt="{{ $heroMediaAlt }}" class="menu-page-hero__image">
        <div class="menu-page-hero__scrim" aria-hidden="true"></div>
    </div>
@endif
