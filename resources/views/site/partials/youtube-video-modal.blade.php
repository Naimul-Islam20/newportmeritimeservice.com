@php
    $modalId = $modalId ?? 'youtube-video-modal';
@endphp
<div id="{{ $modalId }}"
    data-youtube-modal
    class="about-video-modal"
    role="dialog"
    aria-modal="true"
    aria-hidden="true"
    aria-label="Video player">
    <div class="about-video-modal__dialog">
        <button type="button" data-youtube-modal-close class="about-video-modal__close" aria-label="Close video">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="about-video-modal__frame">
            <div class="about-video-modal__player">
                <iframe
                    data-youtube-iframe
                    class="about-video-modal__iframe"
                    title="YouTube video"
                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</div>
