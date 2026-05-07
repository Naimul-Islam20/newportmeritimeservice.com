{{-- Two column About section (full vs short width) --}}
@php($width = $section->layout_width ?: 'full')
@php($img = (is_string($section->image_path) && $section->image_path !== '') ? asset($section->image_path) : 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop')
@php($alt = $section->image_alt ?: 'About image')

@if ($width === 'short')
    <section class="bg-white py-16 sm:py-24">
        <div class="site-container">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <div class="relative h-[360px] w-full sm:h-[520px] lg:h-[640px]">
                    <img src="{{ $img }}" class="absolute inset-0 h-full w-full rounded-md object-cover" alt="{{ $alt }}">
                </div>

                <div class="flex flex-col justify-center py-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $section->mini_title ?: 'Why Choose Us?' }}</h3>
                    <h2 class="mt-4 font-sans text-4xl font-bold leading-tight text-[#112a6d] sm:text-5xl lg:text-[3.25rem]">
                        {!! nl2br(e($section->title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                    </h2>

                    @php($points = is_array($section->points ?? null) ? array_values(array_filter($section->points, fn ($v) => is_string($v) && trim($v) !== '')) : [])

                    @if (count($points) > 0)
                        <ul class="mt-8 max-w-2xl list-disc space-y-4 pl-5 text-base leading-relaxed text-slate-600">
                            @foreach ($points as $p)
                                <li>{{ $p }}</li>
                            @endforeach
                        </ul>
                    @elseif (filled($section->description))
                        <p class="mt-8 max-w-2xl text-base leading-relaxed text-slate-600">
                            {{ $section->description }}
                        </p>
                    @endif

                    @if (filled($section->button_label))
                        <a href="{{ $section->resolvedButtonHref() }}" class="mt-10 inline-block rounded-sm bg-[#3eb0e3] px-10 py-4 text-xs font-bold uppercase tracking-widest text-white shadow-md transition-all hover:bg-[#2b9bc9] hover:shadow-lg">
                            {{ $section->button_label }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@else
    <section id="about" class="bg-[#f6f8fa]">
        <div class="grid lg:grid-cols-2">
            <div class="relative h-[550px] w-full lg:h-[600px]">
                <img src="{{ $img }}" class="h-full w-full object-cover" alt="{{ $alt }}">
            </div>

            <div class="flex flex-col justify-start pb-12 pt-12 px-8 sm:p-12 lg:p-16 lg:pt-20 xl:p-24">
                <div class="max-w-xl">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-[#3eb0e3]">{{ $section->mini_title ?: 'About Us' }}</h3>
                    <h2 class="mt-4 font-sans text-3xl font-bold leading-tight text-[#112a6d] sm:text-4xl lg:text-[2.6rem]">
                        {{ $section->title ?: 'Built on Trust. Driven by Excellence.' }}
                    </h2>

                    <p class="mt-6 text-[0.95rem] leading-relaxed text-slate-600">
                        Founded in 2012, Newport Maritime Service has grown into one of Bangladesh’s most trusted maritime companies. Over more than a decade, we have earned a strong reputation as a dependable General Ship Supplier, Marine Spares Exporter, and Ship Repair Service provider — built on a consistent commitment to quality, efficiency, and client satisfaction.
                    </p>

                    <p class="mt-4 text-[0.95rem] leading-relaxed text-slate-600">
                        Our global relationships reflect the trust the maritime industry places in us. We understand the demands of vessel operations firsthand, and we deliver comprehensive, tailored solutions designed to keep your fleet running smoothly.
                    </p>

                    @if (filled($section->button_label))
                        <a href="{{ $section->resolvedButtonHref() }}" class="mt-10 inline-block rounded-sm bg-[#3eb0e3] px-10 py-4 text-xs font-bold uppercase tracking-widest text-white shadow-md transition-all hover:bg-[#2b9bc9] hover:shadow-lg">
                            {{ $section->button_label }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif

