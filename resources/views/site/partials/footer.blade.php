<footer class="relative mt-auto overflow-hidden text-white">

    {{-- ── Port background image ── --}}
    <div class="absolute inset-0">
        <img
            src="https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2000&auto=format&fit=crop"
            alt="Port"
            class="h-full w-full object-cover object-center"
        >
        {{-- semi-transparent navy overlay so text stays readable but image shows through --}}
        <div class="absolute inset-0" style="background:rgba(10,25,70,0.65);"></div>
    </div>

    {{-- ── Main content ── --}}
    <div class="relative z-10 site-container py-20">
        <div class="flex flex-col justify-between gap-16 lg:flex-row">

            {{-- Left column --}}
            <div>
                <p class="mb-6 text-2xl font-extrabold leading-tight">Newport Maritime Service</p>

                <div class="mt-4 space-y-1 text-[15px] font-normal leading-relaxed text-white/90">
                    <p>Mabud Chy. Center, 5th Floor, Nur Meah Lane, #3 No.</p>
                    <p>Fakirhat, Bandar, Chittagong– 4100, Chittagong,</p>
                    <p>Bangladesh.</p>
                </div>

                <div class="mt-5 space-y-1 text-[15px] font-normal text-white/90">
                    <p><a href="mailto:supply@newportmaritimeservice.com" class="hover:text-[#3eb0e3]">supply@newportmaritimeservice.com</a></p>
                    <p><a href="mailto:newportmaritimeservice@gmail.com"  class="hover:text-[#3eb0e3]">newportmaritimeservice@gmail.com</a></p>
                    <p><a href="mailto:tech@newportmaritimeservice.com"   class="hover:text-[#3eb0e3]">tech@newportmaritimeservice.com</a></p>
                </div>

                <div class="mt-5 space-y-1 text-[15px] font-normal text-white/90">
                    <p><a href="tel:+8801321286667" class="hover:text-[#3eb0e3]">+880 1321 28 66 67</a></p>
                    <p><a href="tel:+8801977434272" class="hover:text-[#3eb0e3]">+880 1977434272</a></p>
                    <p><a href="tel:+88031724728"   class="hover:text-[#3eb0e3]">+880-31-724728</a></p>
                </div>
            </div>

            {{-- Right column: Quick Links --}}
            <div class="lg:min-w-[280px]">
                <p class="mb-6 text-xl font-extrabold leading-tight">Quick Links</p>
                <ul class="mt-4 space-y-3 text-[15px] font-normal text-white/90">
                    <li><a href="{{ route('home') }}"          class="hover:text-[#3eb0e3]">Home</a></li>
                    <li><a href="{{ route('ship-supply') }}"   class="hover:text-[#3eb0e3]">Ship Supply</a></li>
                    <li><a href="#"                            class="hover:text-[#3eb0e3]">Our Services</a></li>
                    <li><a href="#"                            class="hover:text-[#3eb0e3]">Award</a></li>
                    <li><a href="{{ route('contact.create') }}" class="hover:text-[#3eb0e3]">Contact</a></li>
                </ul>
            </div>

        </div>
    </div>

    {{-- ── Bottom copyright bar ── --}}
    <div class="relative z-10 border-t border-[#213b86] bg-[#112a6d] py-4">
        <div class="site-container">
            <p class="text-[13px] font-semibold tracking-widest text-[#b8c6e6]">
                &copy;{{ date('Y') }} NEWPORT MARITIME SERVICE
            </p>
        </div>
    </div>

</footer>