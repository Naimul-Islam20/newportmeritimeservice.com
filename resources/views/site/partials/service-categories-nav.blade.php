@php
    $groups = $serviceCategoryGroups ?? [];
    $links = $serviceCategoryLinks ?? [];
@endphp

<nav class="service-detail__nav service-detail__nav--accordion" data-service-detail-nav aria-label="Our service categories">
    @foreach ($groups as $group)
        <div
            @class([
                'service-detail__nav-group',
                'service-detail__nav-group--open' => ! empty($group['open']),
            ])
            data-service-nav-group
        >
            <button
                type="button"
                class="service-detail__nav-parent"
                data-service-nav-toggle
                aria-expanded="{{ ! empty($group['open']) ? 'true' : 'false' }}"
                aria-controls="service-nav-{{ $group['id'] }}"
            >
                <span>{{ $group['label'] }}</span>
                <svg class="service-detail__nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <ul id="service-nav-{{ $group['id'] }}" class="service-detail__nav-children">
                @foreach ($group['children'] ?? [] as $child)
                    <li>
                        <a href="{{ $child['href'] }}" class="service-detail__nav-child">{{ $child['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    @foreach ($links as $link)
        <a
            href="{{ $link['href'] }}"
            @class([
                'service-detail__nav-parent',
                'service-detail__nav-parent--link',
                'is-active' => ! empty($link['is_active']),
            ])
        >
            <span>{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>
