@once
    <dialog id="siteImageLightboxDialog" class="m-0 max-h-[100dvh] max-w-[100vw] border-0 bg-transparent p-0 text-white outline-none [&::backdrop]:bg-black/88">
        <div id="siteImageLightboxBackdrop" class="relative flex min-h-[100dvh] min-w-[100dvw] cursor-zoom-out items-center justify-center bg-black/88 p-4 sm:p-8">
            <button type="button" id="siteImageLightboxClose" class="absolute right-3 top-3 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-2xl leading-none text-white backdrop-blur-sm transition hover:bg-white/20 sm:right-5 sm:top-5" aria-label="Close">&times;</button>

            <button type="button" id="siteImageLightboxPrev" class="absolute left-2 top-1/2 z-20 hidden -translate-y-1/2 rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition hover:bg-white/20 sm:left-4 sm:p-3" aria-label="Previous image">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-8 w-8 sm:h-10 sm:w-10" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button type="button" id="siteImageLightboxNext" class="absolute right-2 top-1/2 z-20 hidden -translate-y-1/2 rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition hover:bg-white/20 sm:right-4 sm:p-3" aria-label="Next image">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-8 w-8 sm:h-10 sm:w-10" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <img id="siteImageLightboxImg" src="" alt="" class="max-h-[92dvh] max-w-[92vw] object-contain shadow-2xl">
        </div>
    </dialog>

    @push('scripts')
        <script>
            (function () {
                const dlg = document.getElementById('siteImageLightboxDialog');
                const img = document.getElementById('siteImageLightboxImg');
                const backdrop = document.getElementById('siteImageLightboxBackdrop');
                const closeBtn = document.getElementById('siteImageLightboxClose');
                const prevBtn = document.getElementById('siteImageLightboxPrev');
                const nextBtn = document.getElementById('siteImageLightboxNext');
                if (!dlg || !img || !backdrop || !closeBtn || !prevBtn || !nextBtn) return;

                /** @type {{ src: string, alt: string }[]} */
                let gallery = [];
                let currentIndex = 0;

                function collectGallery(trigger) {
                    const nodes = Array.from(document.querySelectorAll('[data-lightbox-src]'));
                    const items = [];
                    for (const el of nodes) {
                        const src = el.getAttribute('data-lightbox-src');
                        if (!src) continue;
                        items.push({
                            src: src,
                            alt: el.getAttribute('data-lightbox-alt') || '',
                        });
                    }
                    let idx = nodes.indexOf(trigger);
                    if (idx < 0) idx = 0;
                    return { items, idx };
                }

                function showAt(index) {
                    if (!gallery.length) return;
                    currentIndex = (index + gallery.length) % gallery.length;
                    const item = gallery[currentIndex];
                    img.src = item.src;
                    img.alt = item.alt;
                    const multi = gallery.length > 1;
                    prevBtn.classList.toggle('hidden', !multi);
                    nextBtn.classList.toggle('hidden', !multi);
                }

                function openLightbox(trigger) {
                    const { items, idx } = collectGallery(trigger);
                    gallery = items;
                    if (!gallery.length) return;
                    showAt(idx);
                    dlg.showModal();
                    document.body.classList.add('overflow-hidden');
                }

                function closeLightbox() {
                    dlg.close();
                    img.removeAttribute('src');
                    img.alt = '';
                    gallery = [];
                    currentIndex = 0;
                    prevBtn.classList.add('hidden');
                    nextBtn.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                document.addEventListener('click', function (e) {
                    const trigger = e.target.closest('[data-lightbox-src]');
                    if (!trigger) return;
                    e.preventDefault();
                    openLightbox(trigger);
                });

                closeBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    closeLightbox();
                });

                prevBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    showAt(currentIndex - 1);
                });

                nextBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    showAt(currentIndex + 1);
                });

                backdrop.addEventListener('click', function (e) {
                    if (e.target === backdrop) closeLightbox();
                });

                document.addEventListener(
                    'keydown',
                    function (e) {
                        if (!dlg.open || gallery.length < 2) return;
                        if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            showAt(currentIndex - 1);
                        } else if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            showAt(currentIndex + 1);
                        }
                    },
                    true,
                );

                dlg.addEventListener('close', function () {
                    document.body.classList.remove('overflow-hidden');
                });
            })();
        </script>
    @endpush
@endonce
