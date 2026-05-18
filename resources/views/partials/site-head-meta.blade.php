@php
    $documentTitle = $title ?? ($siteMetaName ?? config('app.name'));
    $faviconHref = $siteFaviconUrl ?? \App\Models\SiteDetail::faviconAssetUrl();
@endphp
<title>{{ $documentTitle }}</title>
@if ($faviconHref)
    @php($faviconLower = strtolower(parse_url($faviconHref, PHP_URL_PATH) ?? ''))
    @if (str_ends_with($faviconLower, '.svg'))
        <link rel="icon" href="{{ $faviconHref }}" type="image/svg+xml">
    @elseif (str_ends_with($faviconLower, '.ico'))
        <link rel="icon" href="{{ $faviconHref }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ $faviconHref }}" type="image/png">
    @endif
    <link rel="shortcut icon" href="{{ $faviconHref }}">
@endif
