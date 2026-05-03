@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    <section class="border-b border-foreground/10 bg-gradient-to-b from-primary-soft/50 to-background py-16 sm:py-24">
        <div class="site-container">
            <h1 class="font-serif text-4xl font-semibold text-foreground sm:text-5xl">
                Get a quote
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-foreground/70">
                Tell us about your vessel, cargo, timeline, and scope—we will respond with next steps and pricing guidance.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('contact.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-slate-900 shadow-md transition hover:bg-primary-hover">
                    Contact us for a quote
                </a>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-foreground/15 bg-background px-6 py-3 text-sm font-semibold text-foreground transition hover:bg-foreground/5">
                    Back to home
                </a>
            </div>
        </div>
    </section>
@endsection
