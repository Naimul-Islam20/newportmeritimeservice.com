@php
    $d = is_array($section->data ?? null) ? $section->data : [];
    $mini = data_get($d, 'mini_title');
    $title = $section->title;
    $desc = data_get($d, 'description');
    $bottom = data_get($d, 'bottom_description');
    $imagePath = data_get($d, 'image_path');
    $points = is_array(data_get($d, 'points')) ? array_values(array_filter(data_get($d, 'points'), fn ($v) => is_string($v) && trim($v) !== '')) : [];
    $hasImage = is_string($imagePath) && $imagePath !== '';
    $hasContent = filled($mini) || filled($title) || filled($desc) || $hasImage || count($points) > 0 || filled($bottom);
    $hasTitleBlock = filled($mini) || filled($title);
@endphp

@if ($hasContent)
<section class="bg-[#f6f8fa] py-16 sm:py-24">
    <div class="site-container">
        @if ($hasTitleBlock)
            <div class="mx-auto max-w-3xl text-center">
                @if (filled($mini))
                    <p class="text-sm font-bold uppercase tracking-wider text-[#3eb0e3]">{{ $mini }}</p>
                @endif
                @if (filled($title))
                    <h2 class="mt-3 font-sans text-4xl font-bold leading-tight text-[#112a6d] sm:text-5xl">
                        {{ $title }}
                    </h2>
                @endif
            </div>
        @endif

        @if (filled($desc))
            <p class="{{ $hasTitleBlock ? 'mt-8' : '' }} text-start text-base leading-relaxed text-slate-600 sm:text-lg">
                {!! nl2br(e($desc)) !!}
            </p>
        @endif

        @if ($hasImage)
            <figure class="{{ filled($desc) || $hasTitleBlock ? 'mt-8' : '' }} overflow-hidden rounded-lg bg-white shadow-[0_14px_40px_-10px_rgba(1,34,59,0.13),0_5px_18px_-5px_rgba(62,176,227,0.11)]">
                <div class="bg-slate-100">
                    <img src="{{ asset($imagePath) }}" alt="" loading="lazy" decoding="async" class="block h-auto w-full max-w-full object-contain">
                </div>
            </figure>
        @endif

        @if (count($points) > 0)
            <ul class="{{ filled($desc) || $hasImage || $hasTitleBlock ? 'mt-8' : '' }} list-disc space-y-3 pl-5 text-start text-base leading-relaxed text-slate-600 sm:text-lg">
                @foreach ($points as $p)
                    <li>{{ $p }}</li>
                @endforeach
            </ul>
        @endif

        @if (filled($bottom))
            <p class="mt-8 text-start text-base leading-relaxed text-slate-600 sm:text-lg">
                {!! nl2br(e($bottom)) !!}
            </p>
        @endif
    </div>
</section>
@endif
