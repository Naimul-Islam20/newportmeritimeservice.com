@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    <section class="bg-background py-14 sm:py-20">
        <div class="site-container">
            <div class="mx-auto max-w-2xl text-center">
                <h1 class="font-serif text-4xl font-semibold text-foreground sm:text-5xl">
                    Get a quote
                </h1>
                <p class="mt-4 text-lg text-foreground/70">
                    Tell us about your vessel, scope, and timeline—we will respond with next steps and pricing guidance.
                </p>
            </div>

            <div class="mx-auto mt-14 max-w-3xl">
                <div class="rounded-2xl border border-foreground/10 bg-background p-8 shadow-sm shadow-foreground/5 sm:p-10">
                    @if (session('status'))
                        <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('quote.store') }}" class="space-y-6">
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

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="company" class="mb-2 block text-sm font-semibold text-foreground">Company (optional)</label>
                                <input id="company" name="company" type="text" value="{{ old('company') }}"
                                    class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                    placeholder="Company or operator" autocomplete="organization">
                                @error('company')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="vessel_or_reference" class="mb-2 block text-sm font-semibold text-foreground">Vessel / reference (optional)</label>
                                <input id="vessel_or_reference" name="vessel_or_reference" type="text" value="{{ old('vessel_or_reference') }}"
                                    class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                    placeholder="Vessel name, IMO, or job ref.">
                                @error('vessel_or_reference')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="timeline" class="mb-2 block text-sm font-semibold text-foreground">Needed by / timeline (optional)</label>
                            <input id="timeline" name="timeline" type="text" value="{{ old('timeline') }}"
                                class="w-full rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                placeholder="e.g. Port call next week, ETA …">
                            @error('timeline')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="request_details" class="mb-2 block text-sm font-semibold text-foreground">What do you need quoted?</label>
                            <textarea id="request_details" name="request_details" rows="6" required
                                class="w-full resize-y rounded-xl border border-foreground/20 bg-background px-4 py-3 text-foreground shadow-inner outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
                                placeholder="Services, quantities, ports, special requirements…">{{ old('request_details') }}</textarea>
                            @error('request_details')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <button type="submit"
                                class="inline-flex min-w-[200px] items-center justify-center rounded-xl bg-primary px-6 py-3.5 text-sm font-bold text-slate-900 shadow-md transition hover:bg-primary-hover">
                                Submit quote request
                            </button>
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-foreground/15 bg-background px-6 py-3.5 text-sm font-semibold text-foreground transition hover:bg-foreground/5">
                                Back to home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
