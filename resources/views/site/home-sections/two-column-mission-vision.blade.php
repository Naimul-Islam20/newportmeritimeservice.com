@php($left = is_array($section->left_content ?? null) ? $section->left_content : [])
@php($right = is_array($section->right_content ?? null) ? $section->right_content : [])
@php($leftPoints = is_array($left['points'] ?? null) ? array_values(array_filter($left['points'], fn ($v) => is_string($v) && trim($v) !== '')) : [])
@php($rightPoints = is_array($right['points'] ?? null) ? array_values(array_filter($right['points'], fn ($v) => is_string($v) && trim($v) !== '')) : [])

<section class="bg-white py-16 sm:py-24">
    <div class="site-container">
        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Box 1 -->
            <div class="rounded-2xl border border-blue-100 bg-[#f4f7fe] p-8 shadow-sm transition hover:shadow-md sm:p-12">
                <h3 class="font-sans text-3xl font-bold text-[#112a6d]">
                    {{ filled($left['title'] ?? null) ? $left['title'] : 'Our Mission' }}
                </h3>

                @if (filled($left['description'] ?? null))
                    <p class="mt-6 text-base leading-relaxed text-slate-600">
                        {{ $left['description'] }}
                    </p>
                @endif

                @if (count($leftPoints) > 0)
                    <ul class="mt-6 list-disc space-y-4 pl-5 text-base leading-relaxed text-slate-600">
                        @foreach ($leftPoints as $p)
                            <li>{{ $p }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Box 2 -->
            <div class="rounded-2xl border border-cyan-100 bg-[#f0f9fb] p-8 shadow-sm transition hover:shadow-md sm:p-12">
                <h3 class="font-sans text-3xl font-bold text-[#112a6d]">
                    {{ filled($right['title'] ?? null) ? $right['title'] : 'Our Vision' }}
                </h3>

                @if (filled($right['description'] ?? null))
                    <p class="mt-6 text-base leading-relaxed text-slate-600">
                        {{ $right['description'] }}
                    </p>
                @endif

                @if (count($rightPoints) > 0)
                    <ul class="mt-6 list-disc space-y-4 pl-5 text-base leading-relaxed text-slate-600">
                        @foreach ($rightPoints as $p)
                            <li>{{ $p }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>

