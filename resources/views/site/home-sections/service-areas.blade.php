{{-- Service Areas & Locations — data from $serviceArea until DB-backed settings exist --}}
@php($mini = $serviceArea['mini_title'] ?? 'Service Areas')
@php($title = $serviceArea['title'] ?? 'Locations')
@php($mapPath = $serviceArea['map_image_path'] ?? null)
@php($mapPath = is_string($mapPath) ? trim($mapPath) : '')
@php($mapUrl = $mapPath !== '' && (str_starts_with($mapPath, 'http://') || str_starts_with($mapPath, 'https://')) ? $mapPath : ($mapPath !== '' ? asset($mapPath) : null))
@php($highlightTitle = $serviceArea['highlight_title'] ?? '')
@php($highlightDescription = $serviceArea['highlight_description'] ?? '')
@php($steps = is_array($serviceArea['steps'] ?? null) ? $serviceArea['steps'] : [])

<section class="relative overflow-hidden bg-slate-900 py-16 sm:py-24">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=2070&auto=format&fit=crop" class="h-full w-full object-cover opacity-20 mix-blend-overlay" alt="">
    </div>

    <div class="relative z-10 site-container">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $mini }}</h3>
            <h2 class="mt-2 font-sans text-4xl font-bold text-white sm:text-5xl">{{ $title }}</h2>
        </div>

        @if ($mapUrl)
            <div class="mt-10 sm:mt-12">
                <img src="{{ $mapUrl }}" alt="" class="mx-auto w-full max-w-4xl object-contain object-center">
            </div>
        @endif

        @if (filled($highlightTitle) || filled($highlightDescription))
            <div class="mt-16 border-t border-white/10 pt-12 sm:mt-24 md:border-none md:pt-0">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-12">
                    @if (filled($highlightTitle))
                        <h3 class="shrink-0 font-sans text-2xl font-bold text-white sm:text-3xl">{{ $highlightTitle }}</h3>
                    @endif
                    <div class="hidden h-12 w-px bg-white/30 md:block"></div>
                    <div class="h-px w-16 bg-white/30 md:hidden"></div>
                    @if (filled($highlightDescription))
                        <p class="text-base leading-relaxed text-slate-300 md:max-w-3xl">
                            {!! nl2br(e($highlightDescription)) !!}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        @if (count($steps) > 0)
            <div class="mt-16 grid grid-cols-2 gap-8 text-white sm:grid-cols-4 sm:gap-6 lg:gap-10">
                @foreach ($steps as $step)
                    <div class="text-base font-medium leading-snug">{!! nl2br(e($step)) !!}</div>
                @endforeach
            </div>
        @endif
    </div>
</section>
