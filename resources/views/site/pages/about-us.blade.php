@extends('site.layouts.app', [
    'title' => 'About Us — ' . config('app.name'),
    'metaDescription' => 'Learn more about Newport Maritime Service and our commitment to excellence.',
])

@section('content')
    {{-- 1. Hero Section --}}
    <section class="relative flex h-[300px] w-full items-center overflow-hidden bg-slate-900 sm:h-[400px]">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2000&auto=format&fit=crop" class="h-full w-full object-cover opacity-60 mix-blend-overlay" alt="Port Background">
            <div class="absolute inset-0 bg-gradient-to-r from-[#071738]/90 via-[#071738]/60 to-transparent"></div>
        </div>

        <div class="relative z-10 site-container">
            <h1 class="font-sans text-4xl font-bold text-white sm:text-5xl lg:text-6xl tracking-tight">About Us</h1>
            <div class="mt-4 flex items-center gap-3 text-sm sm:text-base font-medium">
                <a href="{{ route('home') }}" class="text-white transition hover:text-[#3eb0e3]">Home</a>
                <span class="text-[#3eb0e3]">About Us</span>
            </div>
        </div>
    </section>

    {{-- 2. Built on Trust Section --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="site-container">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <!-- Image Side -->
                <div class="w-full lg:w-1/2">
                    <div class="overflow-hidden rounded-3xl shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1580674684081-7617fbf3d745?q=80&w=1000&auto=format&fit=crop" alt="Warehouse Operations" class="w-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                </div>

                <!-- Text Side -->
                <div class="w-full lg:w-1/2">
                    <h2 class="font-sans text-3xl font-black leading-tight text-[#112a6d] sm:text-4xl">
                        Built on Trust.<br>Driven by Excellence.
                    </h2>
                    <div class="mt-8 space-y-6 text-base font-medium leading-relaxed text-slate-600">
                        <p>
                            Founded in 2012, Newport Maritime Service has grown into one of Bangladesh's most trusted maritime companies. Over more than a decade, we have earned a strong reputation as a dependable General Ship Supplier, Marine Spares Exporter, and Ship Repair Service provider — built on a consistent commitment to quality, efficiency, and client satisfaction.
                        </p>
                        <p>
                            Our global relationships reflect the trust the maritime industry places in us. We understand the demands of vessel operations firsthand, and we deliver comprehensive, tailored solutions designed to keep your fleet running smoothly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Stats Section --}}
    <section class="bg-white pb-16 lg:pb-24">
        <div class="site-container">
            <div class="grid gap-6 sm:grid-cols-3">
                <!-- Stat 1 -->
                <div class="flex flex-col items-center justify-center rounded-2xl bg-[#f7f8f9] p-10 text-center transition hover:shadow-lg">
                    <span class="text-5xl font-black text-[#112a6d] lg:text-6xl">6</span>
                    <span class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-500">Offices & Warehouses</span>
                </div>
                <!-- Stat 2 -->
                <div class="flex flex-col items-center justify-center rounded-2xl bg-[#f7f8f9] p-10 text-center transition hover:shadow-lg">
                    <span class="text-5xl font-black text-[#112a6d] lg:text-6xl">150+</span>
                    <span class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-500">Employees</span>
                </div>
                <!-- Stat 3 -->
                <div class="flex flex-col items-center justify-center rounded-2xl bg-[#f7f8f9] p-10 text-center transition hover:shadow-lg">
                    <span class="text-5xl font-black text-[#112a6d] lg:text-6xl">25</span>
                    <span class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-500">Trucks</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Mission & Vision Section --}}
    <section class="bg-white pb-16 lg:pb-24">
        <div class="site-container">
            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Mission -->
                <div class="rounded-3xl bg-[#f0f9ff] p-10 lg:p-14">
                    <h3 class="text-3xl font-black text-[#112a6d]">Our Mission</h3>
                    <p class="mt-6 text-base font-medium leading-relaxed text-slate-600">
                        At Newport Maritime Service, our mission is to ensure uninterrupted vessel operations across Bangladeshi ports by delivering government-certified, round-the-clock marine solutions. From marine spares and ship supplies to waste management and technical services, we are committed to providing every client with unwavering reliability, competitive value, and full regulatory compliance.
                    </p>
                </div>
                <!-- Vision -->
                <div class="rounded-3xl bg-[#f0f9ff] p-10 lg:p-14">
                    <h3 class="text-3xl font-black text-[#112a6d]">Our Vision</h3>
                    <p class="mt-6 text-base font-medium leading-relaxed text-slate-600">
                        Our vision is to redefine maritime support across South Asia by becoming the most trusted single-source partner for global fleets. We are building a future where operational excellence, environmental responsibility, and long-term client partnerships go hand in hand — driving sustainable growth and establishing Newport Maritime Service as a symbol of industry leadership.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Video/Experience Section --}}
    <section class="relative min-h-[500px] w-full overflow-hidden bg-slate-900 py-24 lg:py-32">
        <!-- Background Placeholder for Video -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=2000&auto=format&fit=crop" alt="Video Background" class="h-full w-full object-cover opacity-40">
            <div class="absolute inset-0 bg-[#071738]/60"></div>
        </div>

        <div class="relative z-10 site-container flex flex-col justify-center">
            <span class="text-sm font-bold uppercase tracking-[0.2em] text-[#3eb0e3]">13 Years of Experience</span>
            <h2 class="mt-6 max-w-4xl font-sans text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
                We're NewPort, a ship supply company with a proud history.
            </h2>

            <!-- Video Play Button Placeholder -->
            <div class="mt-12">
                <button class="group flex items-center gap-4 text-white">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-white text-[#3eb0e3] shadow-xl transition-transform group-hover:scale-110">
                        <svg class="h-8 w-8 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-wider">Watch our story</span>
                </button>
            </div>
        </div>
    </section>
@endsection
