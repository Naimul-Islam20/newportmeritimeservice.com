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
    $detailsBodyClass = 'text-base leading-relaxed text-foreground/85 sm:text-lg';
@endphp
@php(extract(section_strip_view_data($sectionStrip ?? 'primary')))

@if ($layout === 'short')
    <section class="{{ $stripSectionClass }} py-16 sm:py-24">
        <div class="site-container">
            <div @class(['flex flex-col gap-8 lg:flex-row lg:items-start lg:gap-10'])>
                @if ($imageRight)
                    <div class="flex w-full min-w-0 flex-col justify-start pt-4 flex-1 lg:pt-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $mini ?: 'Why Choose Us?' }}</h3>
                        <h2 class="mt-3 font-sans text-3xl font-bold leading-tight {{ $stripTitleClass }} sm:text-4xl lg:text-[2.75rem]">
                            {!! nl2br(e($title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 max-w-2xl {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 max-w-2xl list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="w-full shrink-0 overflow-hidden rounded-md bg-background/90 lg:w-[48%] lg:shrink-0">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                    </div>
                @else
                    <div class="w-full shrink-0 overflow-hidden rounded-md bg-background/90 lg:w-[48%] lg:shrink-0">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                    </div>
                    <div class="flex w-full min-w-0 flex-col justify-start pt-4 flex-1 lg:pt-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider {{ $stripMiniClass }}">{{ $mini ?: 'Why Choose Us?' }}</h3>
                        <h2 class="mt-3 font-sans text-3xl font-bold leading-tight {{ $stripTitleClass }} sm:text-4xl lg:text-[2.75rem]">
                            {!! nl2br(e($title ?: "One partner.\nEvery need.\nZero compromise.")) !!}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 max-w-2xl {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 max-w-2xl list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@else
    <section class="{{ $stripSectionClass }} py-16 sm:py-24">
        <div @class(['flex flex-col gap-6 lg:flex-row lg:items-start lg:gap-6'])>
            @if ($imageRight)
                <div class="flex w-full min-w-0 flex-col justify-start px-6 pb-12 pt-4 sm:px-8 flex-1 lg:pb-14 lg:pl-8 lg:pr-10 lg:pt-6 xl:pl-10 xl:pr-12">
                    <div class="max-w-xl">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] {{ $stripMiniClass }}">{{ $mini ?: 'About Us' }}</h3>
                        <h2 class="mt-3 font-sans text-2xl font-bold leading-tight {{ $stripTitleClass }} sm:text-3xl lg:text-[2.25rem]">
                            {{ $title ?: 'Built on Trust. Driven by Excellence.' }}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
                                @foreach ($points as $p)
                                    <li>{{ $p }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="w-full shrink-0 overflow-hidden bg-background/90 lg:w-[48%] lg:shrink-0">
                    <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                </div>
            @else
                <div class="w-full shrink-0 overflow-hidden bg-background/90 lg:w-[48%] lg:shrink-0">
                    <img src="{{ $imgUrl }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                </div>
                <div class="flex w-full min-w-0 flex-col justify-start px-6 pb-12 pt-4 sm:px-8 flex-1 lg:pb-14 lg:pl-8 lg:pr-10 lg:pt-6 xl:pl-10 xl:pr-12">
                    <div class="max-w-xl">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] {{ $stripMiniClass }}">{{ $mini ?: 'About Us' }}</h3>
                        <h2 class="mt-3 font-sans text-2xl font-bold leading-tight {{ $stripTitleClass }} sm:text-3xl lg:text-[2.25rem]">
                            {{ $title ?: 'Built on Trust. Driven by Excellence.' }}
                        </h2>
                        @if (filled($desc))
                            <p class="mt-3 {{ $detailsBodyClass }}">{!! nl2br(e($desc)) !!}</p>
                        @endif
                        @if (count($points) > 0)
                            <ul class="mt-3 list-disc space-y-2 pl-5 {{ $detailsBodyClass }}">
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
