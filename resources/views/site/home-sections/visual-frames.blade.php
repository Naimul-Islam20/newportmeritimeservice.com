@if (! empty($visualFrames['show']))
<section class="bg-white py-16 sm:py-24">
    <div class="site-container">
        @php($vfHasHeader = filled($visualFrames['mini_title'] ?? null) || filled($visualFrames['title'] ?? null) || filled($visualFrames['description'] ?? null))
        @if ($vfHasHeader)
            <div class="mb-12 text-center">
                @if (filled($visualFrames['mini_title'] ?? null))
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $visualFrames['mini_title'] }}</h3>
                @endif
                @if (filled($visualFrames['title'] ?? null))
                    <h2 class="mt-2 font-sans text-4xl font-bold text-slate-900 sm:text-5xl lg:text-6xl">{{ $visualFrames['title'] }}</h2>
                @endif
                @if (filled($visualFrames['mini_title'] ?? null) || filled($visualFrames['title'] ?? null))
                    <div class="mx-auto mt-4 h-1 w-20 bg-[#3eb0e3]"></div>
                @endif
                @if (filled($visualFrames['description'] ?? null))
                    <p class="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-slate-600 sm:mt-8 sm:text-lg lg:mb-14">
                        {!! nl2br(e($visualFrames['description'])) !!}
                    </p>
                @endif
            </div>
        @endif

        @php($tiles = $visualFrames['items'] ?? [])
        @if (count($tiles) > 0)
            <div class="flex flex-col gap-8 sm:gap-10">
                @foreach (array_chunk($tiles, 3) as $rowTiles)
                    <div class="grid grid-cols-1 items-start gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6">
                        @foreach ($rowTiles as $tile)
                            <article class="flex w-full flex-col overflow-hidden rounded-xl bg-slate-200 shadow-[0_16px_44px_-10px_rgba(1,34,59,0.14),0_6px_20px_-6px_rgba(62,176,227,0.12)]">
                                <div class="w-full overflow-hidden rounded-t-xl bg-slate-100 {{ filled($tile['caption'] ?? null) ? '' : 'rounded-b-xl' }}">
                                    @php($lbSrc = $tile['src'])
                                    @php($lbAlt = is_string($tile['caption'] ?? null) ? $tile['caption'] : '')
                                    <button type="button" class="group w-full cursor-zoom-in border-0 bg-transparent p-0 text-left outline-none focus-visible:ring-2 focus-visible:ring-[#3eb0e3] focus-visible:ring-offset-2" data-lightbox-src="{{ e($lbSrc) }}" data-lightbox-alt="{{ e($lbAlt) }}">
                                        <img src="{{ $lbSrc }}" alt="{{ $lbAlt }}" loading="lazy" decoding="async" class="block h-auto w-full max-w-full align-middle transition-opacity group-hover:opacity-95">
                                    </button>
                                </div>
                                @if (filled($tile['caption'] ?? null))
                                    <h3 class="rounded-b-xl bg-slate-100 px-3 py-4 text-center font-sans text-lg font-bold leading-snug text-slate-900 sm:text-xl sm:py-4 lg:text-2xl">{{ $tile['caption'] }}</h3>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @include('site.partials.image-fullscreen-lightbox')
        @endif
    </div>
</section>
@endif
