@php($sd = $siteDetails ?? null)
@php($defaultImg = is_string($sd?->default_image_path) ? trim($sd->default_image_path) : '')
@php($showFooterPhoto = $defaultImg !== '')

<footer class="relative mt-auto min-w-0 overflow-hidden text-white">

    {{-- Background: site default image (if set) or solid brand navy --}}
    <div class="absolute inset-0">
        @if ($showFooterPhoto)
            <img src="{{ asset($defaultImg) }}" alt=""
                class="h-full w-full object-cover object-center">
            <div class="absolute inset-0" style="background: var(--brand-footer-overlay);"></div>
        @else
            <div class="absolute inset-0 bg-brand-navy"></div>
        @endif
    </div>

    {{-- Main content --}}
    <div class="relative z-10 site-container py-12 sm:py-16 lg:py-16">
        <div class="flex flex-col justify-between gap-12 sm:gap-14 lg:flex-row lg:gap-16">

            <div class="min-w-0 lg:max-w-xl">
                @php($footerLogo = is_string($sd?->footer_logo_path) ? trim($sd->footer_logo_path) : '')
                @if ($footerLogo !== '')
                    <img src="{{ asset($footerLogo) }}" alt="{{ config('app.name') }}" class="mb-6 h-10 w-auto sm:h-12">
                @else
                    <p class="mb-6 text-xl font-extrabold leading-tight sm:text-2xl">Newport Maritime Service</p>
                @endif

                <div class="mt-6 space-y-3 pl-0 sm:mt-8 sm:pl-4 lg:ml-8 lg:pl-0 xl:ml-[50px]">
                    @php($loc = trim((string) ($sd?->location ?? '')))
                    @php($emails = is_array($sd?->emails ?? null) ? array_values(array_filter($sd->emails, fn ($v) => is_string($v) && trim($v) !== '')) : [])
                    @php($phones = is_array($sd?->phones ?? null) ? array_values(array_filter($sd->phones, fn ($v) => is_string($v) && trim($v) !== '')) : [])

                    @if ($loc !== '')
                        <div class="text-[15px] font-normal leading-relaxed text-white/90 space-y-1 break-words">
                            {!! nl2br(e($loc)) !!}
                        </div>
                    @endif

                    @if (count($emails) > 0)
                        <div class="text-[15px] font-normal text-white/90 space-y-1 break-all sm:break-words">
                            @foreach ($emails as $email)
                                <p>
                                    <a href="mailto:{{ $email }}" class="transition hover:text-brand-accent">{{ $email }}</a>
                                </p>
                            @endforeach
                        </div>
                    @endif

                    @if (count($phones) > 0)
                        <div class="text-[15px] font-normal text-white/90 space-y-1">
                            @foreach ($phones as $phone)
                                @php($tel = preg_replace('/[^0-9+]/', '', $phone))
                                <p>
                                    <a href="tel:{{ $tel }}" class="transition hover:text-brand-accent">{{ $phone }}</a>
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="w-full min-w-0 lg:max-w-xs lg:shrink-0">
                <p class="mb-4 text-lg font-extrabold leading-tight sm:mb-6 sm:text-xl">Quick Links</p>
                <ul class="mt-2 space-y-3 text-[15px] font-normal text-white/90">
                    <li><a href="{{ route('home') }}" class="inline-block py-0.5 transition hover:text-brand-accent">Home</a></li>
                    <li><a href="{{ route('ship-supply') }}" class="inline-block py-0.5 transition hover:text-brand-accent">Ship Supply</a></li>
                    <li><a href="#" class="inline-block py-0.5 transition hover:text-brand-accent">Our Services</a></li>
                    <li><a href="#" class="inline-block py-0.5 transition hover:text-brand-accent">Award</a></li>
                    <li><a href="{{ route('contact.create') }}" class="inline-block py-0.5 transition hover:text-brand-accent">Contact</a></li>
                    <li class="pt-1">
                        <a href="{{ route('quote.request') }}" class="inline-block rounded-md bg-brand-accent px-4 py-2 text-xs font-bold uppercase tracking-widest text-white shadow-sm transition hover:bg-brand-accent-hover">
                            Get a quote
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Copyright bar --}}
    <div class="relative z-10 bg-brand-navy text-brand-topbar-muted text-xs sm:text-sm">
        <div class="site-container flex flex-wrap items-center justify-center py-2 sm:justify-between sm:py-0">
            <div class="flex w-full items-center justify-center sm:w-auto sm:justify-start">
                <span class="border-x border-brand-navy-mid px-4 py-2.5 text-center sm:px-6">
                    &copy;{{ date('Y') }} NEWPORT MARITIME SERVICE
                </span>
            </div>
        </div>
    </div>

</footer>
