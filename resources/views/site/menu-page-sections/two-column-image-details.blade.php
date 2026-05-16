@php
    $d = is_array($section->data ?? null) ? $section->data : [];
    $layout = data_get($d, 'layout_width', 'full');
    $imageSide = strtolower(trim((string) data_get($d, 'image_side', 'left')));
    $imageRight = in_array($imageSide, ['right', '1', 'true', 'on'], true);
    $imgPath = data_get($d, 'image_path');
    $imgUrl = (is_string($imgPath) && $imgPath !== '') ? asset($imgPath) : 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop';
    $mini = data_get($d, 'mini_title');
    $title = $section->title ?: data_get($d, 'title');
    $desc = data_get($d, 'description');
    $points = is_array(data_get($d, 'points')) ? array_values(array_filter(data_get($d, 'points'), fn ($v) => is_string($v) && trim($v) !== '')) : [];
@endphp

@if ($layout === 'short')
    <section class="bg-white py-16 sm:py-24">
        <div class="site-container">
            <div @class(['flex flex-col gap-12 lg:flex-row lg:items-center lg:gap-16'])>
                @if ($imageRight)
                    <div class="flex w-full flex-col justify-center py-6 lg:w-1/2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $mini ?: 'Why Choose Us?' }}</h3>
                        <h2 class="mt-4 font-sans text-4xl font-bold leading-tight text-[#112a6d] sm:text-5xl lg:text-[3.25rem]">
                            {!! nl2br(e($title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                        </h2>
                        @if (count($points) > 0)
                            <ul class="mt-8 max-w-2xl list-disc space-y-4 pl-5 text-base leading-relaxed text-slate-600">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @elseif (filled($desc))
                            <p class="mt-8 max-w-2xl text-base leading-relaxed text-slate-600">{{ $desc }}</p>
                        @endif
                    </div>
                    <div class="w-full shrink-0 overflow-hidden rounded-md bg-slate-100 lg:w-1/2">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                    </div>
                @else
                    <div class="w-full shrink-0 overflow-hidden rounded-md bg-slate-100 lg:w-1/2">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                    </div>
                    <div class="flex w-full flex-col justify-center py-6 lg:w-1/2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $mini ?: 'Why Choose Us?' }}</h3>
                        <h2 class="mt-4 font-sans text-4xl font-bold leading-tight text-[#112a6d] sm:text-5xl lg:text-[3.25rem]">
                            {!! nl2br(e($title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                        </h2>
                        @if (count($points) > 0)
                            <ul class="mt-8 max-w-2xl list-disc space-y-4 pl-5 text-base leading-relaxed text-slate-600">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @elseif (filled($desc))
                            <p class="mt-8 max-w-2xl text-base leading-relaxed text-slate-600">{{ $desc }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@else
    <section class="bg-[#f6f8fa]">
        <div @class(['flex flex-col lg:flex-row lg:items-stretch'])>
            @if ($imageRight)
                <div class="flex w-full flex-col justify-start px-8 pb-12 pt-12 sm:p-12 lg:w-1/2 lg:p-16 lg:pt-20 xl:p-24">
                    <div class="max-w-xl">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-[#3eb0e3]">{{ $mini ?: 'About Us' }}</h3>
                        <h2 class="mt-4 font-sans text-3xl font-bold leading-tight text-[#112a6d] sm:text-4xl lg:text-[2.6rem]">
                            {{ $title ?: 'Built on Trust. Driven by Excellence.' }}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-6 text-[0.95rem] leading-relaxed text-slate-600">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-6 list-disc space-y-4 pl-5 text-[0.95rem] leading-relaxed text-slate-600">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="w-full shrink-0 overflow-hidden bg-slate-100 lg:w-1/2">
                    <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                </div>
            @else
                <div class="w-full shrink-0 overflow-hidden bg-slate-100 lg:w-1/2">
                    <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                </div>
                <div class="flex w-full flex-col justify-start px-8 pb-12 pt-12 sm:p-12 lg:w-1/2 lg:p-16 lg:pt-20 xl:p-24">
                    <div class="max-w-xl">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-[#3eb0e3]">{{ $mini ?: 'About Us' }}</h3>
                        <h2 class="mt-4 font-sans text-3xl font-bold leading-tight text-[#112a6d] sm:text-4xl lg:text-[2.6rem]">
                            {{ $title ?: 'Built on Trust. Driven by Excellence.' }}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-6 text-[0.95rem] leading-relaxed text-slate-600">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-6 list-disc space-y-4 pl-5 text-[0.95rem] leading-relaxed text-slate-600">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
