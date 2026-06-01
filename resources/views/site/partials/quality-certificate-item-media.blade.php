@php
    $imgUrl = isset($cert) ? $cert->imagePublicUrl() : '';
@endphp
@if ($imgUrl !== '')
    <img src="{{ $imgUrl }}" alt="{{ $cert->title }}" class="quality-certs__cert-image" loading="lazy" decoding="async">
@else
    <span class="quality-certs__cert-placeholder">{{ $cert->title }}</span>
@endif
