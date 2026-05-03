@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    <section class="border-b border-foreground/10 bg-background py-16 sm:py-24">
        <div class="site-container">
            <h1 class="font-serif text-4xl font-semibold text-foreground sm:text-5xl">
                {{ $heading }}
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-foreground/70">
                {{ $lead }}
            </p>
        </div>
    </section>
@endsection
