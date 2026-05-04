@extends('site.layouts.app', [
    'title' => 'Ship Supply — ' . config('app.name'),
    'metaDescription' => 'Provisions, stores, and deck supplies for vessels and port operations.',
])

@section('content')
    {{-- Page Header --}}
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-slate-900 sm:h-[400px]">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop" class="h-full w-full object-cover opacity-60 mix-blend-overlay" alt="Port Background">
            <div class="absolute inset-0 bg-gradient-to-r from-[#071738]/90 via-[#071738]/60 to-transparent"></div>
        </div>

        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold text-white sm:text-5xl lg:text-6xl tracking-tight">Supplies</h1>
            <div class="mt-4 flex items-center gap-3 text-sm sm:text-base font-medium">
                <a href="{{ route('home') }}" class="text-white transition hover:text-[#3eb0e3]">Home</a>
                <span class="text-[#3eb0e3]">Supplies</span>
            </div>
        </div>
    </section>

    {{-- Content Area Placeholder --}}
    <section class="bg-white py-16 sm:py-24">
        <div class="site-container">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Card 1 -->
                <div class="flex flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                    <!-- Icon -->
                    <div class="mb-6 text-[#3eb0e3]">
                        <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="44" y="30" width="16" height="24" />
                            <path d="M60 34 h8 v10 h-8" />
                            <path d="M48 54 L40 64" />
                            <path d="M38 66 L34 70" stroke-dasharray="2 3" opacity="0.6" />
                            <rect x="30" y="72" width="8" height="8" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold uppercase text-[#112a6d]">MARINE LUBES AND<br>GREASES</h4>
                    <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                        Professional removal of unwanted fuel, oil residues, and slop from vessel tanks — handled safely, efficiently, and in full compliance...
                    </p>
                    <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                </div>

                <!-- Card 2 -->
                <div class="flex flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                    <div class="mb-6 text-[#3eb0e3]">
                        <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="50" cy="40" r="22" />
                            <circle cx="50" cy="62" r="22" />
                            <line x1="42" y1="84" x2="58" y2="84" />
                            <line x1="50" y1="84" x2="50" y2="76" />
                            <line x1="28" y1="62" x2="72" y2="62" opacity="0.3" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold uppercase text-[#112a6d]">CHAINS – ROPES –<br>SHACKLES</h4>
                    <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                        Environmentally compliant collection and disposal of sludge, slop, and bilge water from vessels, fully certified and in accordance with MARPOL...
                    </p>
                    <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                </div>

                <!-- Card 3 -->
                <div class="flex flex-col rounded-2xl border border-slate-100 bg-[#f7f8f9] p-8 shadow-sm transition hover:shadow-md">
                    <div class="mb-6 text-[#3eb0e3]">
                        <svg viewBox="0 0 100 100" class="h-28 w-28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="38" y="38" width="24" height="36" rx="6" />
                            <path d="M44 38 V28 h12 v10" />
                            <rect x="47" y="24" width="6" height="4" />
                            <path d="M50 24 C50 15, 65 15, 70 25" stroke-dasharray="3 3" opacity="0.7" />
                            <line x1="30" y1="50" x2="38" y2="52" opacity="0.5" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold uppercase text-[#112a6d]">SAFETY EQUIPMENT</h4>
                    <p class="mt-4 flex-1 text-sm font-medium leading-relaxed text-slate-600">
                        Certified inspection, servicing, recharging, and supply of all types of marine fire extinguishers and fire-fighting equipment in compliance with international...
                    </p>
                    <a href="#" class="mt-8 text-sm font-bold text-[#112a6d] transition hover:text-[#3eb0e3]">View details</a>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex items-center justify-center gap-4 text-sm font-bold text-[#112a6d]">
                <span class="cursor-not-allowed opacity-50">Previous</span>
                <span class="flex h-8 w-8 items-center justify-center text-[#3eb0e3]">1</span>
                <a href="#" class="flex h-8 w-8 items-center justify-center transition hover:text-[#3eb0e3]">2</a>
                <a href="#" class="transition hover:text-[#3eb0e3]">Next</a>
            </div>
        </div>
    </section>
@endsection
