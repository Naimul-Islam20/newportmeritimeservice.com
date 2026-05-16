@extends('site.layouts.app', [
    'title' => 'Contact — ' . config('app.name'),
    'metaDescription' => 'Send us a message — we respond as soon as possible.',
])

@push('styles')
    <style>
        /* Google Maps embeds ship fixed widths; force full width (full-bleed strip) */
        .contact-map-embed iframe {
            display: block;
            width: 100% !important;
            max-width: 100% !important;
            height: 280px !important;
            min-height: 240px;
            border: 0;
        }
    </style>
@endpush

@section('content')
    @php($sd = $siteDetails ?? null)
    @php($loc = $sd ? trim((string) ($sd->location ?? '')) : '')
    @php($emails = $sd && is_array($sd->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [])
    @php($phones = $sd && is_array($sd->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [])
    @php($mapRaw = $sd ? trim((string) ($sd->map ?? '')) : '')

    <section class="overflow-x-hidden bg-background py-14 sm:py-20">
        <div class="site-container">
            <div class="mx-auto max-w-2xl text-center">
                <h1 class="font-serif text-4xl font-semibold text-foreground sm:text-5xl">
                    Contact us
                </h1>
                <p class="mt-4 text-lg text-foreground/70">
                    Share your enquiry—we’ll route it to the right team and get back to you shortly.
                </p>
            </div>

            <div class="mt-14 grid gap-12 lg:grid-cols-5 lg:gap-16">
                <aside class="lg:col-span-2">
                    <div class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-secondary">Contact details</h2>
                       

                        <div class="mt-8 space-y-6 text-sm text-foreground/80">
                            @if ($loc !== '')
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-foreground/60">Location</h3>
                                    <div class="mt-2 leading-relaxed text-foreground">
                                        {!! nl2br(e($loc)) !!}
                                    </div>
                                </div>
                            @endif

                            @if (count($emails) > 0)
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-foreground/60">Email</h3>
                                    <ul class="mt-2 space-y-2">
                                        @foreach ($emails as $email)
                                            <li>
                                                <a href="mailto:{{ $email }}" class="font-medium text-foreground underline decoration-primary/40 underline-offset-2 transition hover:text-primary">{{ $email }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (count($phones) > 0)
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-foreground/60">Phone</h3>
                                    <ul class="mt-2 space-y-2">
                                        @foreach ($phones as $phone)
                                            @php($tel = preg_replace('/[^0-9+]/', '', $phone))
                                            <li>
                                                <a href="tel:{{ $tel }}" class="font-medium text-foreground underline decoration-primary/40 underline-offset-2 transition hover:text-primary">{{ $phone }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($loc === '' && count($emails) === 0 && count($phones) === 0)
                                <p class="text-foreground/60">Contact information will appear here once it is configured.</p>
                            @endif
                        </div>
                    </div>
                </aside>

                <div class="lg:col-span-3">
                    <div class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5 sm:p-10">
                        @if (session('status'))
                            <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label for="full_name" class="mb-2 block text-sm font-semibold text-foreground">Full name</label>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required
                                    class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner shadow-foreground/5 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                    placeholder="Your name" autocomplete="name">
                                @error('full_name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-semibold text-foreground">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                        class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                        placeholder="you@company.com" autocomplete="email">
                                    @error('email')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-semibold text-foreground">Phone</label>
                                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                                        class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                        placeholder="+880 …" autocomplete="tel">
                                    @error('phone')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="mb-2 block text-sm font-semibold text-foreground">Subject</label>
                                <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required
                                    class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                    placeholder="What is this about?">
                                @error('subject')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="message" class="mb-2 block text-sm font-semibold text-foreground">Message</label>
                                <textarea id="message" name="message" rows="6" required
                                    class="w-full resize-y rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                    placeholder="Details, timeline, port or vessel if relevant…">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full rounded-xl bg-primary px-6 py-3.5 text-sm font-bold text-slate-900 shadow-md transition hover:bg-primary-hover sm:w-auto sm:min-w-[200px]">
                                Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Full viewport-width map below the form row (not limited by site-container) --}}
        @if ($mapRaw !== '')
            <div class="mt-16 w-full max-w-none border-t border-foreground/10 bg-foreground/[0.02] pt-8">
                <div class="site-container mb-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-secondary">Map</h2>
                </div>
                <div class="relative left-1/2 w-screen max-w-[100vw] -translate-x-1/2 overflow-hidden">
                    @if (stripos($mapRaw, '<iframe') !== false)
                        <div class="contact-map-embed w-full min-w-0">
                            {!! $mapRaw !!}
                        </div>
                    @elseif (filter_var($mapRaw, FILTER_VALIDATE_URL))
                        <iframe
                            title="Map"
                            src="{{ $mapRaw }}"
                            class="block h-[280px] w-full min-w-0 border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen></iframe>
                    @else
                        <div class="site-container py-6 text-sm text-foreground/70">
                            {!! nl2br(e($mapRaw)) !!}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endsection
