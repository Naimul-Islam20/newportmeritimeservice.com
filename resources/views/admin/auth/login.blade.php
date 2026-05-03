<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        :root {
            --primary: #e9a70e;
            --primary-hover: #ecc00d;
            --secondary: #1e1e6d;
            --bg-soft: #f4f7fb;
            --text: #0f172a;
            --muted: #475569;
            --border: #dbe4ef;
            --danger-bg: #fee2e2;
            --danger-text: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 22px;
            background:
                radial-gradient(circle at 12% 16%, rgba(233, 167, 14, 0.18), transparent 45%),
                radial-gradient(circle at 88% 85%, rgba(30, 30, 109, 0.18), transparent 42%),
                var(--bg-soft);
            font-family: Arial, sans-serif;
            color: var(--text);
        }

        .login-shell {
            width: 100%;
            max-width: 460px;
        }

        .brand {
            text-align: center;
            margin-bottom: 6px;
        }

        .brand img {
            width: min(220px, 100%);
            height: auto;
            display: inline-block;
        }

        .card {
            width: 100%;
            background: #fff;
            border-radius: 16px;
            padding: 26px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 16px 44px rgba(15, 23, 42, 0.12);
        }

        h2 {
            margin: 0 0 6px;
            color: var(--text);
            font-size: 24px;
        }

        .subtext {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 14px;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
            color: #1f2937;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 14px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            color: #0f172a;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(233, 167, 14, 0.18);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            color: #334155;
            font-size: 14px;
        }

        .remember input {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        .error {
            background: var(--danger-bg);
            color: var(--danger-text);
            font-size: 14px;
            margin-bottom: 14px;
            border-radius: 8px;
            border: 1px solid #fecaca;
            padding: 10px 12px;
        }

        button {
            width: 100%;
            padding: 11px;
            border: 0;
            border-radius: 8px;
            background: var(--primary);
            color: #1f2937;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.15s ease;
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="login-shell">
        <div class="brand">
            <img src="{{ asset('ERP17-header.png') }}" alt="ERP17">
        </div>
        <form class="card" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <h2>Welcome back</h2>
            <p class="subtext">Sign in to continue to your dashboard.</p>

            @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
            @endif

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <label class="remember" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit">Sign in</button>
        </form>
    </div>
</body>

</html>