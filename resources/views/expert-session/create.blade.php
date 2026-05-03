<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Expert Session</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #1f2937; }
        .container { max-width: 640px; margin: 32px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06); }
        h1 { margin: 0 0 8px; font-size: 26px; }
        .lead { color: #64748b; margin: 0 0 22px; line-height: 1.55; font-size: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #334155; }
        input { width: 100%; padding: 11px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; }
        input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
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
            <h1>Get Free Consultation</h1>
            <p class="lead">Share your details. Our team will contact you with a suitable plan for your business.</p>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('expert-session.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter your name" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="company_name">Company Name</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" placeholder="Enter company name" required>
                    @error('company_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="designation">Designation</label>
                    <input id="designation" name="designation" type="text" value="{{ old('designation') }}" placeholder="Enter your designation" required>
                    @error('designation') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="mobile">Mobile</label>
                    <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" placeholder="+880..." required>
                    @error('mobile') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@company.com" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn">Submit Request</button>
            </form>
        </div>
    </div>
</body>
</html>
