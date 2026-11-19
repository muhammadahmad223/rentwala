@php($u = auth()->user())
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $u->business_name ?: 'RentWala' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{--ink:#10343a;--brand:#0b6e6e;--late:#b42318;--ok:#1a7f37;--bg:#f2f5f6}
html{font-size:18px}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--bg);color:#16262a;padding-bottom:84px}
.navbar{background:var(--ink)} .navbar .nav-link{color:#d5e8e8;font-size:1.05rem} .navbar .nav-link.active{color:#fff;font-weight:700}
.btn{min-height:50px;font-weight:600;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;gap:.4rem}
.btn-brand{background:var(--brand);color:#fff} .btn-brand:hover{background:#09595a;color:#fff}
.form-control,.form-select{min-height:52px;font-size:1.05rem} .form-label{font-weight:600;margin-bottom:.25rem}
.card{border:1px solid #d3dee0;border-radius:14px;box-shadow:none}
.stat{font-size:2rem;font-weight:800;line-height:1.1}
.late{color:var(--late);font-weight:700} .good{color:var(--ok);font-weight:700}
.room-full{border-left:8px solid var(--late)} .room-part{border-left:8px solid #c27c0e} .room-empty{border-left:8px solid var(--ok)}
.avatar{width:48px;height:48px;border-radius:50%;object-fit:cover;background:#d9e4e6;display:inline-flex;align-items:center;justify-content:center;flex:none}
.bottom{position:fixed;left:0;right:0;bottom:0;background:#fff;border-top:1px solid #cfdadc;display:flex;z-index:20;padding-bottom:env(safe-area-inset-bottom)}
.bottom a{flex:1;text-align:center;padding:.55rem 0;color:#4a5f63;text-decoration:none;font-size:.8rem;font-weight:600}
.bottom a i{display:block;font-size:1.5rem} .bottom a.active{color:var(--brand)}
@media(min-width:992px){body{padding-bottom:0}.bottom{display:none}}
</style></head><body>
@php($nav = ['dashboard'=>['Home','house-door'],'rooms'=>[$u->word('spaces'),'door-open'],'payments'=>['Payments','cash-coin'],'settings'=>['Settings','gear']])
<nav class="navbar navbar-dark mb-4"><div class="container">
<a class="navbar-brand fw-bold" href="/dashboard">{{ $u->business_name ?: 'RentWala' }}</a>
<div class="d-none d-lg-flex gap-3 align-items-center">
@foreach($nav as $url=>[$label,$icon])<a class="nav-link {{ request()->is($url.'*') ? 'active' : '' }}" href="/{{ $url }}">{{ $label }}</a>@endforeach
<form method="POST" action="/logout">@csrf<button class="btn btn-sm btn-outline-light" style="min-height:40px">Log out</button></form></div>
<form class="d-lg-none" method="POST" action="/logout">@csrf<button class="btn btn-sm btn-outline-light" style="min-height:40px">Log out</button></form>
</div></nav>
<main class="container pb-4">
@if(session('ok'))<div class="alert alert-success fs-6">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="alert alert-danger fs-6">{{ $errors->first() }}</div>@endif
@yield('content')</main>
<div class="bottom">@foreach($nav as $url=>[$label,$icon])<a class="{{ request()->is($url.'*') ? 'active' : '' }}" href="/{{ $url }}"><i class="bi bi-{{ $icon }}"></i>{{ $label }}</a>@endforeach</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
