<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#10343a">
    <title>{{ $mode === 'login' ? 'Log in' : 'Create your account' }} · RentWala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--ink:#10343a;--brand:#0b6e6e;--muted:#5c7074}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;min-height:100svh;background:radial-gradient(ellipse at top left,#1a5558 0,#10343a 52%,#0b292e 100%);color:var(--ink);font-family:system-ui,-apple-system,"Segoe UI",sans-serif;display:flex;align-items:center;padding:20px 14px;padding-top:max(20px,env(safe-area-inset-top));padding-bottom:max(20px,env(safe-area-inset-bottom))}
        .auth-wrap{width:100%;max-width:440px;margin:auto}
        .brand{color:#fff;text-align:center;margin-bottom:22px}
        .brand-mark{width:54px;height:54px;border-radius:18px;display:grid;place-items:center;margin:0 auto 12px;background:#d5f0e6;color:#0b6e6e;font-size:1.55rem;font-weight:800}
        .brand h1{font-size:1.55rem;font-weight:800;letter-spacing:-.04em;margin:0}
        .brand p{color:#d4e6e5;margin:.35rem 0 0;font-size:.95rem}
        .auth-card{background:#fff;border-radius:24px;padding:26px 22px;box-shadow:0 24px 70px #061e2266}
        .auth-card h2{font-size:1.45rem;font-weight:800;letter-spacing:-.03em;margin:0}
        .subhead{color:var(--muted);margin:.35rem 0 20px;font-size:.94rem}
        .form-label{font-size:.9rem;font-weight:650;margin-bottom:6px}
        .form-control{min-height:54px;border-radius:12px;border-color:#cbd8da;padding:12px 14px;font-size:1rem}
        .form-control:focus{border-color:var(--brand);box-shadow:0 0 0 .2rem #0b6e6e20}
        .field{margin-bottom:15px}
        .btn-brand{min-height:54px;border:0;border-radius:12px;background:var(--brand);color:#fff;font-weight:700;font-size:1rem}
        .btn-brand:hover,.btn-brand:focus{background:#09595a;color:#fff}
        .switch{display:block;text-align:center;color:var(--brand);font-weight:650;text-decoration:none;padding:16px 8px 0;min-height:48px}
        .switch:hover{text-decoration:underline}
        .alert{border-radius:12px;font-size:.92rem}
        @media(min-width:576px){body{padding:32px 20px}.auth-card{padding:34px}}
    </style>
</head>
<body>
<main class="auth-wrap">
    <header class="brand">
        <div class="brand-mark" aria-hidden="true">R</div>
        <h1>RentWala</h1>
        <p>Kiraya ka hisaab, ek jagah.</p>
    </header>
    <section class="auth-card" aria-labelledby="form-title">
        <h2 id="form-title">{{ $mode === 'login' ? 'Welcome back' : 'Get started' }}</h2>
        <p class="subhead">{{ $mode === 'login' ? 'Log in to manage your rentals.' : 'Create an account to manage your rentals.' }}</p>
        @if($errors->any())
            <div class="alert alert-danger py-3" role="alert">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/{{ $mode }}">
            @csrf
            @if($mode === 'register')
                <div class="field"><label class="form-label" for="name">Your name</label><input id="name" class="form-control" name="name" autocomplete="name" placeholder="e.g. Ayesha Khan" value="{{ old('name') }}" required></div>
                <div class="field"><label class="form-label" for="business_name">Property or hostel name <span class="text-secondary fw-normal">(optional)</span></label><input id="business_name" class="form-control" name="business_name" autocomplete="organization" placeholder="e.g. Al-Madina Hostel" value="{{ old('business_name') }}"></div>
                <div class="field"><label class="form-label" for="phone">Mobile number</label><input id="phone" class="form-control" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="03xx xxxxxxx" value="{{ old('phone') }}" required></div>
            @endif
            <div class="field"><label class="form-label" for="email">Email address</label><input id="email" class="form-control" type="email" name="email" inputmode="email" autocomplete="email" placeholder="you@example.com" value="{{ old('email') }}" required></div>
            <div class="field"><label class="form-label" for="password">Password</label><input id="password" class="form-control" type="password" name="password" autocomplete="{{ $mode === 'login' ? 'current-password' : 'new-password' }}" placeholder="{{ $mode === 'register' ? 'At least 8 characters' : 'Your password' }}" required></div>
            @if($mode === 'register')
                <div class="field"><label class="form-label" for="password_confirmation">Confirm password</label><input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Enter your password again" required></div>
            @endif
            <button class="btn btn-brand w-100 mt-1">{{ $mode === 'login' ? 'Log in' : 'Create account' }}</button>
        </form>
        <a class="switch" href="/{{ $mode === 'login' ? 'register' : 'login' }}">{{ $mode === 'login' ? 'New here? Create an account' : 'Already have an account? Log in' }}</a>
    </section>
</main>
</body>
</html>
