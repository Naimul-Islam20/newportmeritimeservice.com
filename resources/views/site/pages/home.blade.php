@extends('site.layouts.app', [
    'title' => config('app.name') . ' — Home',
    'metaDescription' => 'Maritime logistics, port operations, and trusted supply chain support.',
])

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-secondary via-[#25256f] to-slate-950 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-40"
            style="background-image: radial-gradient(circle at 20% 30%, rgba(233, 167, 14, 0.35), transparent 45%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.12), transparent 40%);"></div>
        <div class="relative site-container py-20 sm:py-28">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-widest text-primary/90">Maritime &amp; port solutions</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold leading-tight sm:text-5xl lg:text-[3.25rem]">
                    Move cargo with clarity and confidence
                </h1>
                <p class="mt-5 text-lg leading-relaxed text-white/90">
                    End-to-end coordination for port calls, documentation, and stakeholder communication—built for teams that cannot afford downtime.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('contact.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-slate-900 shadow-lg shadow-amber-900/20 transition hover:bg-primary-hover">
                        Contact us
                    </a>
                    <a href="#services"
                        class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/5 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/10">
                        Our services
                    </a>
                </div>
            </div>
        </div>
        <div class="h-px w-full bg-gradient-to-r from-transparent via-primary/50 to-transparent"></div>
    </section>

    {{-- Trust strip --}}
    <section class="border-b border-foreground/10 bg-background py-8">
        <div class="site-container flex flex-wrap items-center justify-center gap-x-12 gap-y-4 text-center text-sm font-medium text-foreground/70">
            <span class="text-secondary">24/7 operational awareness</span>
            <span class="hidden h-4 w-px bg-foreground/20 sm:block" aria-hidden="true"></span>
            <span>Compliance-focused workflows</span>
            <span class="hidden h-4 w-px bg-foreground/20 sm:block" aria-hidden="true"></span>
            <span>Single place for messages &amp; follow-up</span>
        </div>
    </section>

    {{-- Services / features --}}
    <section id="services" class="scroll-mt-20 bg-background py-16 sm:py-24">
        <div class="site-container">
            <div class="max-w-2xl">
                <h2 class="font-serif text-3xl font-semibold text-foreground sm:text-4xl">
                    Built for port ecosystems
                </h2>
                <p class="mt-3 text-lg text-foreground/70">
                    Practical modules that mirror how your teams already work—fewer handoffs, fewer surprises.
                </p>
            </div>

            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <article class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5 transition hover:border-primary/40 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-soft text-lg font-bold text-secondary">1</div>
                    <h3 class="mt-5 text-lg font-semibold text-foreground">Berth &amp; turnaround</h3>
                    <p class="mt-2 text-sm leading-relaxed text-foreground/70">
                        Align schedules and milestones so vessels, cargo, and crews stay on the same timeline.
                    </p>
                </article>
                <article class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5 transition hover:border-primary/40 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-soft text-lg font-bold text-secondary">2</div>
                    <h3 class="mt-5 text-lg font-semibold text-foreground">Documentation hub</h3>
                    <p class="mt-2 text-sm leading-relaxed text-foreground/70">
                        Keep correspondence and requests structured—easy to trace and share with partners.
                    </p>
                </article>
                <article class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5 transition hover:border-primary/40 hover:shadow-md sm:col-span-2 lg:col-span-1">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-soft text-lg font-bold text-secondary">3</div>
                    <h3 class="mt-5 text-lg font-semibold text-foreground">Responsive support</h3>
                    <p class="mt-2 text-sm leading-relaxed text-foreground/70">
                        Reach out anytime via our contact channel—messages land where your operations team can act.
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-y border-primary/25 bg-gradient-to-r from-primary-soft via-background to-primary-soft py-16">
        <div class="site-container text-center">
            <h2 class="font-serif text-2xl font-semibold text-secondary sm:text-3xl">
                Ready to simplify your next port call?
            </h2>
            <p class="mx-auto mt-3 max-w-xl text-foreground/70">
                Tell us about your route, cargo, or partnership idea—we’ll follow up with next steps.
            </p>
            <a href="{{ route('contact.create') }}"
                class="mt-8 inline-flex items-center justify-center rounded-xl bg-secondary px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-secondary/20 transition hover:opacity-90">
                Start a conversation
            </a>
        </div>
    </section>
@endsection
