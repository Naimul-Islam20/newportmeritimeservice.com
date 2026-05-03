<footer class="mt-auto border-t border-foreground/10 bg-secondary text-white/90">
    <div class="site-container grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-3">
        <div>
            <p class="font-serif text-lg font-semibold text-white">About</p>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-white/75">
                We deliver reliable maritime and port operations support—aligned with your supply chain goals.
            </p>
        </div>
        <div>
            <p class="font-serif text-lg font-semibold text-white">Explore</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-white/75 underline-offset-4 hover:text-primary hover:underline">Home</a></li>
                <li><a href="{{ route('contact.create') }}" class="text-white/75 underline-offset-4 hover:text-primary hover:underline">Contact</a></li>
            </ul>
        </div>
        <div class="sm:col-span-2 lg:col-span-1">
            <p class="font-serif text-lg font-semibold text-white">Get in touch</p>
            <p class="mt-3 text-sm text-white/75">
                Questions or partnerships? Reach us through the contact page—we reply as soon as we can.
            </p>
        </div>
    </div>
    <div class="border-t border-white/10 py-4 text-center text-xs text-white/50">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</footer>
