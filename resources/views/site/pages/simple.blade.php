@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    <section class="relative flex h-[280px] items-center overflow-hidden bg-slate-900 sm:h-[340px] md:h-[380px]">
        <div class="absolute inset-0">
            <img src="{{ asset('menu-page-cover.jpg') }}" alt="" class="h-full w-full object-cover opacity-70">
            <div class="absolute inset-0 bg-[#01223b]/65"></div>
        </div>

        <div class="relative site-container w-full py-10 sm:py-14">
            <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white drop-shadow-2xl sm:text-5xl">
                {{ $heading }}
            </h1>
            @if (! empty($lead))
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/90 drop-shadow">
                    {{ $lead }}
                </p>
            @endif
        </div>
    </section>
@endsection
