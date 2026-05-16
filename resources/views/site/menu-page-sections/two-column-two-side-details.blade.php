@php($d = is_array($section->data ?? null) ? $section->data : [])
@php($leftTitle = data_get($d, 'left_title'))
@php($rightTitle = data_get($d, 'right_title'))
@php($leftDesc = data_get($d, 'left_description'))
@php($rightDesc = data_get($d, 'right_description'))
@php($sectionTitle = $section->title)

<section class="bg-white py-16 sm:py-24">
    <div class="site-container">
        @if (filled($sectionTitle))
            <div class="mb-10">
                <h2 class="font-sans text-4xl font-bold text-[#112a6d] sm:text-5xl">{{ $sectionTitle }}</h2>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-blue-100 bg-[#f4f7fe] p-8 shadow-sm transition hover:shadow-md sm:p-12">
                <h3 class="font-sans text-3xl font-bold text-[#112a6d]">{{ $leftTitle ?: 'Our Mission' }}</h3>
                @if (filled($leftDesc))
                    <p class="mt-6 text-base leading-relaxed text-slate-600">{!! nl2br(e($leftDesc)) !!}</p>
                @endif
            </div>

            <div class="rounded-2xl border border-cyan-100 bg-[#f0f9fb] p-8 shadow-sm transition hover:shadow-md sm:p-12">
                <h3 class="font-sans text-3xl font-bold text-[#112a6d]">{{ $rightTitle ?: 'Our Vision' }}</h3>
                @if (filled($rightDesc))
                    <p class="mt-6 text-base leading-relaxed text-slate-600">{!! nl2br(e($rightDesc)) !!}</p>
                @endif
            </div>
        </div>
    </div>
</section>

