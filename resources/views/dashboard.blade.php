@extends('layout')
@section('content')
<style>
    .dashboard-ui{--dash-ink:#10343a;--dash-brand:#0b6e6e;--dash-muted:#6b7c80;--dash-line:#e4ecec;--dash-red:#b42318;--dash-green:#16804b;color:var(--dash-ink);padding-bottom:.25rem}
    .dashboard-ui .dash-head{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;margin-bottom:1.1rem}
    .dashboard-ui .dash-eyebrow{color:var(--dash-brand);font-size:.72rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase;margin-bottom:.2rem}
    .dashboard-ui .dash-title{font-size:clamp(1.28rem,4vw,1.75rem);font-weight:800;letter-spacing:-.045em;line-height:1.12;margin:0;overflow-wrap:anywhere}
    .dashboard-ui .dash-subtitle{color:var(--dash-muted);font-size:.82rem;margin:.3rem 0 0}
    .dashboard-ui .dash-month{flex:none;color:var(--dash-brand);background:#e7f3f1;border:1px solid #d3e9e5;border-radius:999px;padding:.45rem .7rem;font-size:.75rem;font-weight:700;white-space:normal;text-align:center}
    .dashboard-ui .stats-grid{--bs-gutter-x:.65rem;--bs-gutter-y:.65rem;margin-bottom:1.3rem}
    .dashboard-ui .stat-card{position:relative;display:flex;align-items:center;gap:.7rem;min-height:104px;height:100%;padding:.8rem;border:1px solid var(--dash-line);border-radius:16px;background:linear-gradient(145deg,#fff 30%,var(--stat-tint));box-shadow:0 5px 16px #10343a0c;overflow:hidden}
    .dashboard-ui .stat-icon{flex:none;width:42px;height:42px;display:grid;place-items:center;border-radius:14px;background:var(--stat-icon-bg);color:var(--stat-color);font-size:1.15rem}
    .dashboard-ui .stat-copy{min-width:0}
    .dashboard-ui .stat-label{color:#596e72;font-size:.72rem;font-weight:650;line-height:1.25;margin-bottom:.28rem}
    .dashboard-ui .stat-value{color:var(--stat-color);font-size:clamp(1.15rem,4.3vw,1.6rem);font-weight:800;letter-spacing:-.055em;line-height:1.02;overflow-wrap:anywhere}
    .dashboard-ui .stat-note{color:var(--dash-muted);font-size:.68rem;line-height:1.25;margin-top:.28rem}
    .dashboard-ui .tone-teal{--stat-tint:#f1fbf8;--stat-icon-bg:#dff5ed;--stat-color:#08755d}
    .dashboard-ui .tone-blue{--stat-tint:#f2f8ff;--stat-icon-bg:#e2f0ff;--stat-color:#1769aa}
    .dashboard-ui .tone-red{--stat-tint:#fff6f6;--stat-icon-bg:#ffe8e8;--stat-color:#b42318}
    .dashboard-ui .tone-green{--stat-tint:#f1fbf4;--stat-icon-bg:#e0f5e5;--stat-color:#16804b}
    .dashboard-ui .dash-section{margin-top:1.15rem}
    .dashboard-ui .section-head{display:flex;align-items:center;justify-content:space-between;gap:.65rem;margin-bottom:.65rem}
    .dashboard-ui .section-title{font-size:1rem;font-weight:800;letter-spacing:-.025em;margin:0}
    .dashboard-ui .section-count{background:#fff0ef;color:var(--dash-red);border-radius:999px;padding:.28rem .55rem;font-size:.7rem;font-weight:750;white-space:nowrap}
    .dashboard-ui .pending-card{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:.7rem;align-items:center;padding:.85rem;border:1px solid #f0d9d8;border-radius:15px;background:linear-gradient(145deg,#fff 25%,#fff8f7);box-shadow:0 4px 12px #b4231808}
    .dashboard-ui .pending-card+.pending-card{margin-top:.55rem}
    .dashboard-ui .person-name{font-size:.92rem;font-weight:800;line-height:1.2;overflow-wrap:anywhere}
    .dashboard-ui .person-meta{color:var(--dash-muted);font-size:.74rem;margin-top:.22rem;overflow-wrap:anywhere}
    .dashboard-ui .person-due{color:var(--dash-red);font-size:.72rem;font-weight:700;margin-top:.35rem}
    .dashboard-ui .pending-side{text-align:right}
    .dashboard-ui .pending-amount{color:var(--dash-red);font-size:1.1rem;font-weight:850;letter-spacing:-.04em;overflow-wrap:anywhere}
    .dashboard-ui .pending-actions{display:flex;gap:.4rem;margin-top:.45rem}
    .dashboard-ui .pending-actions .btn{min-height:38px;padding:.35rem .55rem;font-size:.72rem;white-space:normal;border-radius:10px}
    .dashboard-ui .pending-actions .btn-brand{color:#fff;background:var(--dash-brand);border-color:var(--dash-brand)}
    .dashboard-ui .empty-state{padding:.9rem 1rem;border:1px dashed #cbdcda;border-radius:14px;background:#f8fcfb;color:var(--dash-muted);font-size:.82rem}
    .dashboard-ui .space-list{display:flex;flex-wrap:wrap;gap:.5rem}
    .dashboard-ui .space-chip{display:inline-flex;align-items:center;gap:.45rem;min-height:42px;padding:.5rem .72rem;border:1px solid #cfe8e2;border-radius:12px;background:#f3fbf8;color:#08755d;text-decoration:none;font-size:.78rem;font-weight:700;box-shadow:0 2px 8px #10343a08}
    .dashboard-ui .space-chip:hover{border-color:#93cbbd;background:#eaf8f3;color:#075b4a}
    .dashboard-ui .space-empty{color:var(--dash-muted);font-size:.8rem}
    @media(max-width:380px){
        .dashboard-ui .dash-head{align-items:flex-start;flex-direction:column;gap:.55rem}
        .dashboard-ui .stat-card{gap:.55rem;min-height:96px;padding:.65rem}
        .dashboard-ui .stat-icon{width:36px;height:36px;border-radius:12px;font-size:1rem}
        .dashboard-ui .pending-card{grid-template-columns:minmax(0,1fr);gap:.55rem}
        .dashboard-ui .pending-side{display:flex;align-items:center;justify-content:space-between;gap:.5rem;text-align:left}
        .dashboard-ui .pending-actions{margin-top:0}
    }
    @media(min-width:768px){
        .dashboard-ui .stats-grid{--bs-gutter-x:1rem;--bs-gutter-y:1rem}
        .dashboard-ui .stat-card{min-height:118px;padding:1rem}
        .dashboard-ui .stat-icon{width:48px;height:48px;font-size:1.25rem}
        .dashboard-ui .stat-label{font-size:.8rem}
        .dashboard-ui .stat-note{font-size:.74rem}
        .dashboard-ui .pending-card{padding:1rem 1.1rem}
    }
</style>
<div class="dashboard-ui">
@php($free = $beds - $used)
<header class="dash-head">
    <div>
        <div class="dash-eyebrow">RentWala dashboard</div>
        <h1 class="dash-title">Welcome back{{ $u->business_name ? ', '.$u->business_name : '' }}</h1>
        <p class="dash-subtitle">Here is your rental overview.</p>
    </div>
    <span class="dash-month"><i class="bi bi-calendar3 me-1"></i>{{ $month->format('F') }}</span>
</header>

<div class="row stats-grid">
    <div class="col-6 col-lg-3"><article class="stat-card tone-teal"><span class="stat-icon"><i class="bi bi-door-open"></i></span><div class="stat-copy"><div class="stat-label">{{ $u->business_type=='hostel' ? 'Empty beds' : 'Empty '.strtolower($u->word('spaces')) }}</div><div class="stat-value">{{ $free }}</div><div class="stat-note">{{ $used }} of {{ $beds }} in use</div></div></article></div>
    <div class="col-6 col-lg-3"><article class="stat-card tone-blue"><span class="stat-icon"><i class="bi bi-people"></i></span><div class="stat-copy"><div class="stat-label">{{ $u->word('people') }} living now</div><div class="stat-value">{{ $used }}</div><div class="stat-note">Currently active</div></div></article></div>
    <div class="col-6 col-lg-3"><article class="stat-card tone-red"><span class="stat-icon"><i class="bi bi-wallet2"></i></span><div class="stat-copy"><div class="stat-label">Pending rent</div><div class="stat-value">Rs {{ number_format($pending->sum('owes')) }}</div><div class="stat-note">{{ $pending->count() }} people</div></div></article></div>
    <div class="col-6 col-lg-3"><article class="stat-card tone-green"><span class="stat-icon"><i class="bi bi-check-lg"></i></span><div class="stat-copy"><div class="stat-label">Received this month</div><div class="stat-value">Rs {{ number_format($collected) }}</div><div class="stat-note">{{ $month->format('F') }}</div></div></article></div>
</div>

<section class="dash-section" aria-labelledby="pending-title">
<div class="section-head"><h2 class="section-title" id="pending-title">Rent to collect</h2><span class="section-count">{{ $pending->count() }} {{ $pending->count()===1 ? 'person' : 'people' }}</span></div>
@forelse($pending as $o)
<article class="pending-card">
    <div><div class="person-name">{{ $o->name }}</div><div class="person-meta">{{ $o->space->name }} &middot; {{ $o->phone }}</div><div class="person-due">@if($o->late){{ $o->late }} days late @else Due on day {{ $o->due_day }}@endif</div></div>
    <div class="pending-side"><div class="pending-amount">Rs {{ number_format($o->owes) }}</div><div class="pending-actions"><a class="btn btn-brand" href="/payments?occupant={{ $o->id }}"><i class="bi bi-cash-coin"></i> Pay</a><a class="btn btn-outline-success" target="_blank" href="{{ $o->wa }}"><i class="bi bi-whatsapp"></i> Remind</a></div></div>
</article>
@empty
<div class="empty-state">{{ $used ? 'Everyone has paid. Well done.' : 'No one is living here yet. Open '.$u->word('spaces').' and add the first person.' }}</div>
@endforelse
</section>

<section class="dash-section" aria-labelledby="spaces-title">
    <div class="section-head"><h2 class="section-title" id="spaces-title">{{ $u->word('spaces') }} with free space</h2></div>
    <div class="space-list">
    @forelse($openSpaces as $s)<a class="space-chip" href="/rooms/{{ $s->id }}/assign"><i class="bi bi-door-open"></i>{{ $s->name }} <span>{{ $s->beds - $s->used }} free</span></a>
    @empty<span class="space-empty">Everything is full.</span>@endforelse
    </div>
</section>
</div>
@endsection
