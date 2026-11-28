@extends('layout')
@section('content')
<style>
    .settings-ui{--settings-ink:#10343a;--settings-brand:#0b6e6e;--settings-muted:#6b7c80;--settings-line:#e4ecec;color:var(--settings-ink);padding-bottom:.5rem}
    .settings-ui .settings-head{margin-bottom:1rem}
    .settings-ui .settings-eyebrow{color:var(--settings-brand);font-size:.72rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase;margin-bottom:.2rem}
    .settings-ui .settings-title{font-size:clamp(1.3rem,4vw,1.75rem);font-weight:800;letter-spacing:-.045em;line-height:1.12;margin:0;overflow-wrap:anywhere}
    .settings-ui .settings-subtitle{color:var(--settings-muted);font-size:.82rem;margin:.3rem 0 0}
    .settings-ui .category-list{display:grid;gap:.55rem;margin-bottom:1.1rem}
    .settings-ui .category-link{display:flex;align-items:center;gap:.7rem;min-height:58px;padding:.7rem .8rem;border:1px solid var(--settings-line);border-radius:14px;background:#fff;color:var(--settings-ink);text-decoration:none;box-shadow:0 3px 12px #10343a08}
    .settings-ui .category-link:hover{border-color:#a5cbc3;background:#f9fcfb}
    .settings-ui .category-icon{width:37px;height:37px;display:grid;place-items:center;flex:none;border-radius:12px;background:#e7f3f1;color:var(--settings-brand);font-size:1.05rem}
    .settings-ui .category-copy{min-width:0;flex:1}
    .settings-ui .category-name{display:block;font-size:.82rem;font-weight:800;overflow-wrap:anywhere}
    .settings-ui .category-help{display:block;color:var(--settings-muted);font-size:.68rem;margin-top:.12rem;overflow-wrap:anywhere}
    .settings-ui .category-arrow{color:#8a9b9e;flex:none}
    .settings-ui .settings-section{scroll-margin-top:1rem;margin-top:1.2rem}
    .settings-ui .section-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;margin-bottom:.65rem}
    .settings-ui .section-title{font-size:1rem;font-weight:800;letter-spacing:-.025em;margin:0;overflow-wrap:anywhere}
    .settings-ui .section-help{color:var(--settings-muted);font-size:.72rem;margin:.2rem 0 0;overflow-wrap:anywhere}
    .settings-ui .section-count{flex:none;color:var(--settings-brand);background:#e7f3f1;border-radius:999px;padding:.3rem .55rem;font-size:.68rem;font-weight:750}
    .settings-ui .settings-card{padding:.9rem;border:1px solid var(--settings-line);border-radius:16px;background:#fff;box-shadow:0 4px 15px #10343a09}
    .settings-ui .personal-preview{display:flex;align-items:center;gap:.75rem;margin-bottom:1rem}
    .settings-ui .logo-preview{width:58px;height:58px;display:grid;place-items:center;flex:none;border:1px solid #d8e7e4;border-radius:17px;background:#e7f3f1;color:var(--settings-brand);font-size:1.5rem;overflow:hidden}
    .settings-ui .logo-preview img{width:100%;height:100%;object-fit:cover}
    .settings-ui .preview-copy{min-width:0}
    .settings-ui .preview-name{font-size:.9rem;font-weight:800;overflow-wrap:anywhere}
    .settings-ui .preview-type{color:var(--settings-muted);font-size:.72rem;margin-top:.15rem}
    .settings-ui .form-label{font-size:.76rem;font-weight:700;color:#385258;margin-bottom:.3rem;overflow-wrap:anywhere}
    .settings-ui .form-control{width:100%;max-width:100%;min-width:0;min-height:48px;border-color:#d6e1e2;border-radius:11px;font-size:.86rem}
    .settings-ui .form-control:focus{border-color:#73aaa1;box-shadow:0 0 0 .2rem #0b6e6e18}
    .settings-ui .form-control[type=file]{height:auto;min-height:48px;padding:.55rem;font-size:.76rem}
    .settings-ui .form-help{color:var(--settings-muted);font-size:.68rem;line-height:1.45;margin:.35rem 0 .75rem;overflow-wrap:anywhere}
    .settings-ui .primary-action{min-height:44px;padding:.55rem .9rem;border-radius:10px;background:var(--settings-brand);border-color:var(--settings-brand);font-size:.78rem;font-weight:750;white-space:normal}
    .settings-ui .space-card{height:100%;padding:.85rem;border:1px solid var(--settings-line);border-radius:15px;background:#fff;box-shadow:0 4px 14px #10343a09}
    .settings-ui .space-top{display:flex;align-items:flex-start;justify-content:space-between;gap:.6rem;margin-bottom:.75rem}
    .settings-ui .space-name{min-width:0;font-size:.86rem;font-weight:800;overflow-wrap:anywhere}
    .settings-ui .space-occupancy{flex:none;color:#167548;background:#e7f7ec;border-radius:999px;padding:.25rem .45rem;font-size:.64rem;font-weight:750;text-align:center;overflow-wrap:anywhere}
    .settings-ui .space-form-grid{--bs-gutter-x:.55rem;--bs-gutter-y:.55rem}
    .settings-ui .space-actions{display:flex;flex-wrap:wrap;gap:.45rem;margin-top:.7rem}
    .settings-ui .space-actions .btn{min-height:40px;flex:1 1 120px;padding:.4rem .55rem;border-radius:9px;font-size:.72rem;font-weight:700;white-space:normal;overflow-wrap:anywhere}
    .settings-ui .add-space-card{padding:.9rem;border:1px dashed #b9d6d0;border-radius:15px;background:#f8fcfb}
    .settings-ui .payment-link-card{display:flex;align-items:center;gap:.7rem;padding:.85rem;border:1px solid var(--settings-line);border-radius:14px;background:#fff;color:var(--settings-ink);text-decoration:none;box-shadow:0 4px 14px #10343a08}
    .settings-ui .payment-link-card:hover{border-color:#a5cbc3;background:#f9fcfb}
    .settings-ui .empty-settings{padding:.9rem;border:1px dashed #cbdcda;border-radius:14px;background:#f8fcfb;color:var(--settings-muted);font-size:.78rem}
    @media(min-width:768px){
        .settings-ui .category-list{grid-template-columns:repeat(3,minmax(0,1fr))}
        .settings-ui .settings-card,.settings-ui .space-card,.settings-ui .add-space-card{padding:1rem}
    }
</style>

<div class="settings-ui">
    <header class="settings-head">
        <div class="settings-eyebrow">RentWala</div>
        <h1 class="settings-title">Settings</h1>
        <p class="settings-subtitle">Manage your profile, {{ strtolower($u->word('spaces')) }}, and payment records.</p>
    </header>

    <nav class="category-list" aria-label="Settings categories">
        <a class="category-link" href="#personal-settings"><span class="category-icon"><i class="bi bi-person-gear" aria-hidden="true"></i></span><span class="category-copy"><span class="category-name">Personal information</span><span class="category-help">Business name and logo</span></span><i class="bi bi-chevron-right category-arrow" aria-hidden="true"></i></a>
        <a class="category-link" href="#space-settings"><span class="category-icon"><i class="bi bi-buildings" aria-hidden="true"></i></span><span class="category-copy"><span class="category-name">{{ ucfirst($u->word('spaces')) }}</span><span class="category-help">Add or update a {{ strtolower($u->word('space')) }}</span></span><i class="bi bi-chevron-right category-arrow" aria-hidden="true"></i></a>
        <a class="category-link" href="/payments"><span class="category-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span><span class="category-copy"><span class="category-name">Payment records</span><span class="category-help">Search, filter, and export payments</span></span><i class="bi bi-chevron-right category-arrow" aria-hidden="true"></i></a>
    </nav>

    <section class="settings-section" id="personal-settings" aria-labelledby="personal-settings-title">
        <div class="section-heading"><div><h2 class="section-title" id="personal-settings-title">Personal information</h2><p class="section-help">Update the name and logo shown in your app.</p></div></div>
        <form method="POST" action="/settings" enctype="multipart/form-data" class="settings-card">@csrf
            <div class="personal-preview">
                <span class="logo-preview">@if($u->logo_path)<img src="{{ asset('storage/'.$u->logo_path) }}" alt="Current business logo">@else<i class="bi bi-buildings" aria-hidden="true"></i>@endif</span>
                <span class="preview-copy"><span class="preview-name">{{ $u->business_name ?: 'Your business' }}</span><span class="preview-type d-block">{{ ucfirst($u->business_type ?? 'property') }}</span></span>
            </div>
            <div class="mb-3"><label class="form-label" for="business_name">{{ $u->business_type == 'hostel' ? 'Hostel name' : 'Property name' }}</label><input id="business_name" class="form-control" name="business_name" value="{{ old('business_name', $u->business_name) }}" maxlength="80" required></div>
            <div class="mb-3"><label class="form-label" for="logo">Business logo</label><input id="logo" class="form-control" type="file" name="logo" accept="image/png,image/jpeg,image/webp"><p class="form-help">Optional. Choose a JPG, PNG, or WebP image up to 2 MB. Without a logo, RentWala shows a building icon.</p></div>
            <button class="btn btn-brand primary-action" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i>Save personal information</button>
        </form>
    </section>

    <section class="settings-section" id="space-settings" aria-labelledby="space-settings-title">
        <div class="section-heading"><div><h2 class="section-title" id="space-settings-title">Your {{ strtolower($u->word('spaces')) }}</h2><p class="section-help">Update a {{ strtolower($u->word('space')) }} or add a new one.</p></div><span class="section-count">{{ $spaces->count() }}</span></div>
        @if($spaces->isEmpty())<div class="empty-settings mb-3">No {{ strtolower($u->word('spaces')) }} yet. Add your first one below.</div>@endif
        <div class="row g-2 mb-3">
            @foreach($spaces as $s)
                <div class="col-12 col-xl-6">
                    <article class="space-card">
                        <div class="space-top"><h3 class="space-name mb-0">{{ $s->name }}</h3><span class="space-occupancy">{{ $s->used }} {{ $s->used == 1 ? strtolower($u->word('person')) : strtolower($u->word('people')) }}</span></div>
                        <form method="POST" action="/spaces/{{ $s->id }}">@csrf @method('PUT')
                            <div class="row space-form-grid">
                                <div class="col-12"><label class="form-label" for="space-name-{{ $s->id }}">{{ ucfirst($u->word('space')) }} name</label><input id="space-name-{{ $s->id }}" class="form-control" name="name" value="{{ $s->name }}" maxlength="60" required></div>
                                @if($u->business_type == 'hostel')<div class="col-6"><label class="form-label" for="space-beds-{{ $s->id }}">Total beds</label><input id="space-beds-{{ $s->id }}" type="number" min="1" max="50" class="form-control" name="beds" value="{{ $s->beds }}" required></div>@endif
                                <div class="col-6"><label class="form-label" for="space-rent-{{ $s->id }}">{{ $u->business_type == 'hostel' ? 'Rent per bed' : 'Rent per month' }} (Rs)</label><input id="space-rent-{{ $s->id }}" type="number" min="0" class="form-control" name="rent" value="{{ $s->rent }}" required></div>
                            </div>
                            <div class="space-actions"><button class="btn btn-brand" type="submit"><i class="bi bi-check2" aria-hidden="true"></i>Save changes</button></div>
                        </form>
                        <form method="POST" action="/spaces/{{ $s->id }}" class="space-actions" onsubmit="return confirm('Delete {{ e($s->name) }}?')">@csrf @method('DELETE')<button class="btn btn-outline-danger" type="submit"><i class="bi bi-trash3" aria-hidden="true"></i>Delete {{ $u->word('space') }}</button></form>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="section-heading mt-3"><div><h3 class="section-title">Add a {{ strtolower($u->word('space')) }}</h3><p class="section-help">Add another {{ strtolower($u->word('space')) }} to your list.</p></div></div>
        <form method="POST" action="/spaces" class="add-space-card">@csrf
            <div class="row g-2">
                <div class="col-12"><label class="form-label" for="new-space-name">{{ ucfirst($u->word('space')) }} name</label><input id="new-space-name" class="form-control" name="name" value="{{ old('name') }}" placeholder="e.g. {{ $u->word('space') }} 1" maxlength="60" required></div>
                @if($u->business_type == 'hostel')<div class="col-6"><label class="form-label" for="new-space-beds">Total beds</label><input id="new-space-beds" type="number" min="1" max="50" class="form-control" name="beds" value="{{ old('beds', 3) }}" required></div>@endif
                <div class="col-6"><label class="form-label" for="new-space-rent">{{ $u->business_type == 'hostel' ? 'Rent per bed' : 'Rent per month' }} (Rs)</label><input id="new-space-rent" type="number" min="0" class="form-control" name="rent" value="{{ old('rent', 0) }}" required></div>
                <div class="col-12"><button class="btn btn-brand primary-action w-100" type="submit"><i class="bi bi-plus-circle" aria-hidden="true"></i>Add {{ ucfirst($u->word('space')) }}</button></div>
            </div>
        </form>
    </section>

    <section class="settings-section" aria-labelledby="payment-settings-title">
        <div class="section-heading"><div><h2 class="section-title" id="payment-settings-title">Payment records</h2><p class="section-help">Find, filter, and export your payment history.</p></div></div>
        <a class="payment-link-card" href="/payments"><span class="category-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span><span class="category-copy"><span class="category-name">Open payment records</span><span class="category-help">Search by resident or date, then export</span></span><i class="bi bi-arrow-right category-arrow" aria-hidden="true"></i></a>
    </section>
</div>
@endsection
