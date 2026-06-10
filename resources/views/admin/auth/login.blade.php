<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite(['resources/css/app.css'])
    @include('partials.site-theme-css')
    <style>
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
                radial-gradient(circle at 12% 16%, color-mix(in srgb, var(--primary) 22%, transparent), transparent 45%),
                radial-gradient(circle at 88% 85%, color-mix(in srgb, var(--secondary) 22%, transparent), transparent 42%),
                var(--admin-page-bg);
            font-family: var(--font-geist-sans), Arial, sans-serif;
            color: #0f172a;
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
            color: #0f172a;
            font-size: 24px;
        }

        .subtext {
            margin: 0 0 18px;
            color: #475569;
            font-size: 14px;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
            color: #1f2937;
        }

        input[type="email"],
        .login-password__input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid var(--admin-login-border);
            border-radius: 8px;
            margin-bottom: 14px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            color: #0f172a;
        }

        .login-password__input {
            padding-right: 2.75rem;
        }

        .login-password {
            position: relative;
            margin-bottom: 14px;
        }

        .login-password .login-password__input {
            margin-bottom: 0;
        }

        .login-password__toggle {
            position: absolute;
            top: 50%;
            right: 0.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            padding: 0;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transform: translateY(-50%);
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        .login-password__toggle:hover {
            color: #0f172a;
            background: #f1f5f9;
            transform: translateY(-50%);
        }

        .login-password__toggle svg {
            width: 1.15rem;
            height: 1.15rem;
        }

        .login-password__icon--hidden {
            display: none;
        }

        input[type="email"]:focus,
        .login-password__input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 28%, transparent);
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
            background: var(--admin-danger-bg);
            color: var(--admin-danger-text);
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
            color: var(--secondary);
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.15s ease;
        }

        button:hover {
            background: var(--primary);
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="login-shell">
        <div class="brand">
            <img src="{{ $adminHeaderLogoUrl ?? \App\Models\SiteDetail::headerLogoAssetUrl() }}" alt="{{ $siteMetaName ?? \App\Models\SiteDetail::resolvedSiteName() }}">
        </div>
        <form class="card" method="POST" action="{{ route('login.store') }}">
            @csrf
            <h2>Welcome back</h2>
            <p class="subtext">Sign in to continue to your dashboard.</p>

            @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
            @endif

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password">Password</label>
            <div class="login-password">
                <input id="password" class="login-password__input" name="password" type="password" required autocomplete="current-password">
                <button
                    type="button"
                    class="login-password__toggle"
                    data-login-password-toggle
                    aria-label="Show password"
                    aria-pressed="false"
                >
                    <svg class="login-password__icon--show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg class="login-password__icon--hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                        <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                        <line x1="2" y1="2" x2="22" y2="22" />
                    </svg>
                </button>
            </div>

            <label class="remember" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit">Sign in</button>
        </form>
    </div>
    <script>
        document.querySelectorAll("[data-login-password-toggle]").forEach((button) => {
            const field = button.closest(".login-password")?.querySelector(".login-password__input");
            const showIcon = button.querySelector(".login-password__icon--show");
            const hideIcon = button.querySelector(".login-password__icon--hidden");

            if (!field || !showIcon || !hideIcon) {
                return;
            }

            button.addEventListener("click", () => {
                const isHidden = field.type === "password";

                field.type = isHidden ? "text" : "password";
                showIcon.classList.toggle("login-password__icon--hidden", isHidden);
                hideIcon.classList.toggle("login-password__icon--hidden", !isHidden);
                button.setAttribute("aria-pressed", isHidden ? "true" : "false");
                button.setAttribute("aria-label", isHidden ? "Hide password" : "Show password");
            });
        });
    </script>
</body>

</html>