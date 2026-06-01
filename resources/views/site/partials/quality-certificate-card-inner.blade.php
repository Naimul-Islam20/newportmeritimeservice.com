<span class="quality-certs__cert-visual">
    @include('site.partials.quality-certificate-item-media')
</span>
@if (! empty($showHover))
    <span class="quality-certs__cert-hover" aria-hidden="true">
        <span class="quality-certs__cert-hover-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 8v8M8 12h8"/>
            </svg>
        </span>
    </span>
@endif
