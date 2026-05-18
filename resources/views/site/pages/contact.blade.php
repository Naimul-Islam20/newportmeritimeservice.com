@extends('site.layouts.app', [
    'title' => \App\Models\SiteDetail::pageTitle('Contact'),
    'metaDescription' => 'Send us a message — we respond as soon as possible.',
])

@push('styles')
    <style>
        /* Full-bleed Google Maps; remove inline-gap below iframe */
        .contact-map-section {
            line-height: 0;
        }

        .contact-map-section__heading {
            line-height: normal;
        }

        .contact-map-embed iframe,
        .contact-map-section__frame {
            display: block;
            width: 100% !important;
            max-width: 100% !important;
            height: 360px !important;
            min-height: 280px;
            margin: 0;
            padding: 0;
            border: 0;
            vertical-align: bottom;
        }
    </style>
@endpush

@section('content')
    @php($sd = $siteDetails ?? null)
    @php($loc = $sd ? trim((string) ($sd->location ?? '')) : '')
    @php($emails = $sd && is_array($sd->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [])
    @php($phones = $sd && is_array($sd->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [])
    @php($mapRaw = $sd ? trim((string) ($sd->map ?? '')) : '')
    @php($mapIsIframe = $mapRaw !== '' && stripos($mapRaw, '<iframe') !== false)
    @php($mapIsUrl = $mapRaw !== '' && ! $mapIsIframe && filter_var($mapRaw, FILTER_VALIDATE_URL))
    @php($mapEmbedUrl = null)
    @if ($mapRaw === '' && $loc !== '')
        @php($mapEmbedUrl = 'https://maps.google.com/maps?q=' . rawurlencode(preg_replace('/\s+/u', ' ', $loc)) . '&output=embed')
    @endif
    @php($showMap = $mapIsIframe || $mapIsUrl || filled($mapEmbedUrl))

    <section class="overflow-x-hidden bg-background pt-14 pb-14 sm:pt-20 {{ $showMap ? 'sm:pb-12' : 'sm:pb-20' }}">
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
                    <h2 class="text-base font-semibold uppercase tracking-wide text-secondary sm:text-lg">Contact details</h2>

                    <div class="mt-8 space-y-6 text-base text-foreground/80 sm:text-[1.05rem]">
                        @if ($loc !== '')
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-wide text-foreground/60">Location</h3>
                                <div class="mt-2 text-base leading-relaxed text-foreground sm:text-lg">
                                    {!! nl2br(e($loc)) !!}
                                </div>
                            </div>
                        @endif

                        @if (count($emails) > 0)
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-wide text-foreground/60">Email</h3>
                                <ul class="mt-2 space-y-2 text-base sm:text-lg">
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
                                <h3 class="text-sm font-bold uppercase tracking-wide text-foreground/60">Phone</h3>
                                <ul class="mt-2 space-y-2 text-base sm:text-lg">
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
                </aside>

                <div class="lg:col-span-3">
                <div class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5 sm:p-10">
                    @if (session('status'))
                        <div class="mb-8 rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm font-medium text-secondary">
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
                            class="w-full rounded-xl bg-primary px-6 py-3.5 text-sm font-bold text-secondary shadow-md transition hover:brightness-95 sm:w-auto sm:min-w-[200px]">
                            Send message
                        </button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </section>

    @if ($showMap)
        <section class="contact-map-section w-full border-t border-foreground/10" aria-label="Google Maps">
            <div class="site-container contact-map-section__heading py-6">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-secondary">Find us on Google Maps</h2>
            </div>
                <div class="relative left-1/2 w-screen max-w-[100vw] -translate-x-1/2 overflow-hidden">
                @if ($mapIsIframe)
                    <div class="contact-map-embed w-full min-w-0">
                        {!! $mapRaw !!}
                    </div>
                @else
                    <iframe
                        title="Google Maps"
                        src="{{ $mapIsUrl ? $mapRaw : $mapEmbedUrl }}"
                        class="contact-map-section__frame w-full min-w-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
                @endif
            </div>
        </section>
    @endif
@endsection
