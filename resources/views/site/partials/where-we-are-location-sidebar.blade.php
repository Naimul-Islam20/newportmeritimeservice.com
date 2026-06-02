<aside class="where-location__sidebar" aria-label="Where we are navigation">
    @foreach ($location->sidebar_regions as $region)
        <div class="where-location__sidebar-block">
            <h2 class="where-location__sidebar-heading">{{ $region->label }}</h2>
            <div class="where-location__sidebar-nav" aria-label="{{ $region->label }}">
                @foreach ($region->items as $item)
                    @if (($item->type ?? 'link') === 'accordion')
                        <div
                            class="service-detail__nav-group service-detail__nav-group--open"
                            data-where-location-nav-group
                        >
                            <button
                                type="button"
                                class="service-detail__nav-parent"
                                data-where-location-nav-toggle
                                aria-expanded="true"
                            >
                                <span>{{ $item->label }}</span>
                                <svg class="service-detail__nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <ul class="service-detail__nav-children">
                                @foreach ($item->children as $child)
                                    <li>
                                        <a
                                            href="{{ $child->href }}"
                                            @class([
                                                'service-detail__nav-child',
                                                'is-active' => ! empty($child->is_active),
                                            ])
                                        >{{ $child->label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a
                            href="{{ $item->href }}"
                            @class([
                                'where-location__sidebar-link',
                                'where-location__sidebar-link--active' => ! empty($item->is_active),
                            ])
                            @if (str_starts_with($item->href, '#'))
                                @if ($item->href === '#where-location-quality')
                                    data-scroll-to="where-location-quality"
                                @endif
                            @elseif (str_starts_with($item->href, 'http'))
                                target="_blank"
                                rel="noopener noreferrer"
                            @endif
                        >
                            {{ $item->label }}
                        </a>
                    @endif
                @endforeach
            </div>

            @if ($region->brochure)
                <div class="where-location__sidebar-brochure">
                    <p class="where-location__sidebar-brochure-lead">{{ $region->brochure->lead }}</p>
                    <a
                        href="{{ $region->brochure->href }}"
                        class="where-location__sidebar-download"
                        @if (str_starts_with($region->brochure->href, 'http')) target="_blank" rel="noopener noreferrer" @endif
                    >
                        <span>{{ $region->brochure->label }}</span>
                        <span class="where-location__sidebar-download-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 3v12M8 11l4 4 4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </a>
                </div>
            @endif
        </div>
    @endforeach
</aside>
