@php
    $items = $items ?? [];

    if ($items === [] && filled($path ?? null)) {
        $items = \App\Models\SubMenu::heroBreadcrumbsForPath($path) ?? [];
    }

    if ($items === [] && filled($current ?? null)) {
        $items = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $current],
        ];
    }
@endphp

@if (count($items) > 0)
    <nav class="page-hero__crumbs" aria-label="Breadcrumb">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <span aria-hidden="true">/</span>
            @endif
            @if ($index < count($items) - 1 && filled($item['url'] ?? null))
                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            @else
                <span>{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
