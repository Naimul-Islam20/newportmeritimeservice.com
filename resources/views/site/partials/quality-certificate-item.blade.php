@php
    $pdfUrl = $cert->pdfPublicUrl();
    $hasPdf = $cert->hasViewablePdf();
@endphp

@if ($variant === 'stack')
    <article class="quality-certs__stack-cell" role="listitem">
        <h4 class="quality-certs__stack-title">{{ $cert->title }}</h4>
        @if ($hasPdf)
            <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="quality-certs__cert-card quality-certs__cert-card--link" title="View certificate: {{ $cert->title }}">
                @include('site.partials.quality-certificate-card-inner', ['showHover' => true])
            </a>
        @else
            <div class="quality-certs__cert-card">
                @include('site.partials.quality-certificate-card-inner')
            </div>
        @endif
    </article>
@else
    @if ($hasPdf)
        <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="quality-certs__cert-card quality-certs__grid-item quality-certs__cert-card--link" role="listitem" title="View certificate: {{ $cert->title }}">
            @include('site.partials.quality-certificate-card-inner', ['showHover' => true])
        </a>
    @else
        <div class="quality-certs__cert-card quality-certs__grid-item" role="listitem">
            @include('site.partials.quality-certificate-card-inner')
        </div>
    @endif
@endif
