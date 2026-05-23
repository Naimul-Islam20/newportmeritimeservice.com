@php
    $modalId = $modalId ?? 'youtube-video-modal';
@endphp
<div id="{{ $modalId }}"
    data-youtube-modal
    class="about-video-modal fixed inset-0 z-[300] hidden items-center justify-center bg-black/85 p-4 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-hidden="true">
    <div class="relative w-full max-w-5xl">
        <button type="button" data-youtube-modal-close class="about-video-modal__close" aria-label="Close video">
            <span aria-hidden="true">&times;</span>
            <span class="about-video-modal__close-hint">Close (Esc)</span>
        </button>
        <div class="overflow-hidden rounded-xl bg-black shadow-2xl ring-1 ring-white/15">
            <div class="aspect-video w-full bg-black">
                <iframe data-youtube-iframe class="h-full w-full" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
