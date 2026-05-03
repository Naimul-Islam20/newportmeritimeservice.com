@extends('site.layouts.app', [
    'title' => 'Contact — ' . config('app.name'),
    'metaDescription' => 'Send us a message — we respond as soon as possible.',
])

@section('content')
    <section class="bg-background py-14 sm:py-20">
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
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-secondary">Direct line</h2>
                        <p class="mt-4 text-sm leading-relaxed text-foreground/70">
                            Prefer email first? Use the form—every submission is logged for our operations desk.
                        </p>
                        <ul class="mt-8 space-y-4 text-sm text-foreground/80">
                            <li class="flex gap-3">
                                <span class="mt-0.5 text-primary" aria-hidden="true">●</span>
                                <span><strong class="text-foreground">Response time:</strong> typically within one business day.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 text-primary" aria-hidden="true">●</span>
                                <span><strong class="text-foreground">Secure:</strong> your details are used only to handle your request.</span>
                            </li>
                        </ul>
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
    </section>
@endsection
