<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #1f2937; }
        .container { max-width: 640px; margin: 32px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06); }
        h1 { margin: 0 0 8px; font-size: 26px; }
        .lead { color: #64748b; margin: 0 0 22px; line-height: 1.55; font-size: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #334155; }
        input, textarea { width: 100%; padding: 11px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; }
        input:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .field { margin-bottom: 16px; }
        .btn { width: 100%; padding: 12px; border: 0; border-radius: 8px; background: #2563eb; color: #fff; font-weight: 700; font-size: 15px; cursor: pointer; margin-top: 6px; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
        .flash { background: #dcfce7; color: #166534; border: 1px solid #86efac; border-radius: 8px; padding: 12px; margin-bottom: 18px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Get in touch</h1>
            <p class="lead">Send us a message and we will get back to you as soon as possible.</p>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}">
                @csrf

                <div class="field">
                    <label for="full_name">Full Name</label>
                    <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" placeholder="Enter your name" required>
                    @error('full_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" placeholder="Enter your phone number" required>
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="subject">Subject</label>
                    <input id="subject" name="subject" type="text" value="{{ old('subject') }}" placeholder="Message subject" required>
                    @error('subject') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" placeholder="How can we help you?" required>{{ old('message') }}</textarea>
                    @error('message') <div class="error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
