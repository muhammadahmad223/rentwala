@extends('layout')
@section('content')
<style>
    .rooms-ui{--room-ink:#10343a;--room-brand:#0b6e6e;--room-muted:#6b7c80;--room-line:#e4ecec;color:var(--room-ink);padding-bottom:.25rem}
    .rooms-ui .rooms-head{margin-bottom:1rem}
    .rooms-ui .rooms-eyebrow{color:var(--room-brand);font-size:.72rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase;margin-bottom:.2rem}
    .rooms-ui .rooms-title{font-size:clamp(1.3rem,4vw,1.75rem);font-weight:800;letter-spacing:-.045em;line-height:1.12;margin:0}
    .rooms-ui .rooms-subtitle{color:var(--room-muted);font-size:.82rem;margin:.3rem 0 0}
    .rooms-ui .rooms-summary{--bs-gutter-x:.6rem;--bs-gutter-y:.6rem;margin-bottom:.8rem}
    .rooms-ui .summary-card{height:100%;padding:.7rem .75rem;border:1px solid var(--room-line);border-radius:14px;background:#fff;box-shadow:0 4px 13px #10343a09}
    .rooms-ui .summary-label{color:var(--room-muted);font-size:.68rem;font-weight:650;line-height:1.2}
    .rooms-ui .summary-value{font-size:1.22rem;font-weight:850;letter-spacing:-.04em;line-height:1.15;margin-top:.2rem}
    .rooms-ui .summary-value.available{color:#16804b}
    .rooms-ui .search-box{position:relative;margin:0 0 1rem}
    .rooms-ui .search-icon{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:var(--room-muted);pointer-events:none}
    .rooms-ui .room-search{min-height:48px;padding-left:2.5rem;border-radius:13px;border-color:#d7e2e3;background:#fff;font-size:.9rem}
    .rooms-ui .room-search:focus{border-color:#73aaa1;box-shadow:0 0 0 .2rem #0b6e6e18}
    .rooms-ui .results-line{color:var(--room-muted);font-size:.72rem;margin:0 0 .55rem}
    .rooms-ui .rooms-grid{--bs-gutter-x:.7rem;--bs-gutter-y:.7rem}
    .rooms-ui .room-card{height:100%;padding:.85rem;border:1px solid var(--room-line);border-radius:16px;background:#fff;box-shadow:0 5px 16px #10343a0b;overflow:hidden}
    .rooms-ui .room-card.status-full{border-top:3px solid #d94b46}
    .rooms-ui .room-card.status-partial{border-top:3px solid #0b6e6e}
    .rooms-ui .room-card.status-available{border-top:3px solid #27a36a}
    .rooms-ui .room-top{display:flex;align-items:flex-start;justify-content:space-between;gap:.6rem}
    .rooms-ui .room-name{font-size:1.02rem;font-weight:800;letter-spacing:-.025em;line-height:1.2;overflow-wrap:anywhere}
    .rooms-ui .room-status{flex:none;display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .5rem;border-radius:999px;font-size:.66rem;font-weight:750;line-height:1.1;white-space:nowrap}
    .rooms-ui .status-full .room-status{color:#a9312d;background:#fff0ef}
    .rooms-ui .status-partial .room-status{color:#08685b;background:#eaf6f2}
    .rooms-ui .status-available .room-status{color:#167548;background:#e7f7ec}
    .rooms-ui .status-dot{width:6px;height:6px;border-radius:50%;background:currentColor}
    .rooms-ui .room-stats{display:flex;flex-wrap:wrap;gap:.4rem;margin:.7rem 0 .55rem}
    .rooms-ui .room-stat{display:inline-flex;align-items:center;gap:.28rem;padding:.32rem .48rem;border-radius:9px;background:#f3f7f7;color:#425b5f;font-size:.7rem;font-weight:700;line-height:1.15}
    .rooms-ui .room-stat.available{background:#eaf7ee;color:#167548}
    .rooms-ui .room-rent{color:#425b5f;font-size:.73rem;font-weight:650;padding:.48rem 0;border-top:1px solid #edf1f1}
    .rooms-ui .residents-heading{display:flex;align-items:center;gap:.35rem;color:#50686c;font-size:.7rem;font-weight:750;padding:.45rem 0 .25rem}
    .rooms-ui .resident-row{display:flex;align-items:center;gap:.55rem;padding:.48rem 0;border-top:1px solid #edf1f1}
    .rooms-ui .resident-avatar{width:36px;height:36px;border-radius:12px;object-fit:cover;background:#e5f1ee;color:var(--room-brand);display:grid;place-items:center;flex:none}
    .rooms-ui .resident-copy{min-width:0;flex:1}
    .rooms-ui .resident-name{font-size:.78rem;font-weight:750;line-height:1.2;overflow-wrap:anywhere}
    .rooms-ui .resident-phone{color:var(--room-muted);font-size:.7rem;margin-top:.16rem;overflow-wrap:anywhere}
    .rooms-ui .resident-row .btn{min-height:36px;padding:.3rem .55rem;border-radius:9px;font-size:.68rem;font-weight:700;flex:none}
    .rooms-ui .add-resident{width:100%;min-height:42px;margin-top:.55rem;border-radius:11px;font-size:.78rem;font-weight:700}
    .rooms-ui .empty-state{padding:1rem;border:1px dashed #cbdcda;border-radius:15px;background:#f8fcfb;color:var(--room-muted);font-size:.82rem}
    .rooms-ui .no-results{display:none;padding:.8rem 1rem;border-radius:13px;background:#f5f9f9;color:var(--room-muted);font-size:.8rem}
    @media(min-width:768px){
        .rooms-ui .rooms-summary{--bs-gutter-x:.85rem;--bs-gutter-y:.85rem;margin-bottom:1rem}
        .rooms-ui .summary-card{padding:.85rem 1rem}
        .rooms-ui .summary-value{font-size:1.4rem}
        .rooms-ui .rooms-grid{--bs-gutter-x:.9rem;--bs-gutter-y:.9rem}
        .rooms-ui .room-card{padding:1rem}
    }
</style>

<div class="rooms-ui">
    @php
        $totalBeds = $spaces->sum('beds');
        $occupiedBeds = $spaces->sum(fn ($space) => $space->occupants->count());
        $availableBeds = $spaces->sum(fn ($space) => max(0, $space->beds - $space->occupants->count()));
    @endphp
    <header class="rooms-head">
        <div class="rooms-eyebrow">RentWala</div>
        <h1 class="rooms-title">{{ $u->word('spaces') }}</h1>
        <p class="rooms-subtitle">Find a room, check who is staying, and manage beds.</p>
    </header>

    <div class="row rooms-summary" aria-label="Room occupancy summary">
        <div class="col-4"><div class="summary-card"><div class="summary-label">{{ ucfirst($u->word('spaces')) }}</div><div class="summary-value">{{ $spaces->count() }}</div></div></div>
        <div class="col-4"><div class="summary-card"><div class="summary-label">{{ $u->business_type == 'hostel' ? 'Occupied' : 'In use' }}</div><div class="summary-value">{{ $occupiedBeds }}</div></div></div>
        <div class="col-4"><div class="summary-card"><div class="summary-label">{{ $u->business_type == 'hostel' ? 'Free beds' : 'Available' }}</div><div class="summary-value available">{{ $availableBeds }}</div></div></div>
    </div>

    <label class="search-box" for="room-search">
        <i class="bi bi-search search-icon" aria-hidden="true"></i>
        <input id="room-search" class="form-control room-search" type="search" placeholder="Search {{ strtolower($u->word('spaces')) }} or residents" autocomplete="off">
    </label>
    <p class="results-line" id="room-results" aria-live="polite">{{ $spaces->count() }} {{ strtolower($u->word('spaces')) }}</p>

    <div class="row rooms-grid" id="rooms-grid">
    @forelse($spaces as $s)
        @php($n = $s->occupants->count())
        @php($left = $s->beds - $n)
        @php($statusClass = $left <= 0 ? 'status-full' : ($n ? 'status-partial' : 'status-available'))
        <div class="col-12 col-md-6 col-xl-4 room-item">
            <article class="room-card {{ $statusClass }}">
                <div class="room-top">
                    <h2 class="room-name mb-0">{{ $s->name }}</h2>
                    <span class="room-status"><span class="status-dot" aria-hidden="true"></span>{{ $left <= 0 ? 'Full' : ($n ? 'Beds available' : 'Available') }}</span>
                </div>

                <div class="room-stats">
                    <span class="room-stat"><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i>@if($u->business_type == 'hostel'){{ $s->beds }} {{ $s->beds === 1 ? 'bed' : 'beds' }} total @else {{ ucfirst($u->word('space')) }} @endif</span>
                    <span class="room-stat"><i class="bi bi-person-check" aria-hidden="true"></i>{{ $n }} occupied</span>
                    <span class="room-stat available"><i class="bi bi-check2" aria-hidden="true"></i>@if($u->business_type == 'hostel'){{ max(0, $left) }} free @else {{ $left > 0 ? 'Available' : 'Occupied' }} @endif</span>
                </div>

                <div class="room-rent"><i class="bi bi-cash-coin me-1" aria-hidden="true"></i>Rs {{ number_format($s->rent) }} {{ $u->business_type == 'hostel' ? 'per bed / month' : 'per month' }}</div>

                @if($s->occupants->isNotEmpty())
                    <div class="residents-heading"><i class="bi bi-people" aria-hidden="true"></i>{{ $u->word('people') }} staying</div>
                    @foreach($s->occupants as $o)
                        <div class="resident-row">
                            @if($o->photo)
                                <img class="resident-avatar" src="{{ asset('storage/'.$o->photo) }}" alt="">
                            @else
                                <span class="resident-avatar"><i class="bi bi-person fs-5" aria-hidden="true"></i></span>
                            @endif
                            <div class="resident-copy"><div class="resident-name">{{ $o->name }}</div><div class="resident-phone">{{ $o->phone }}</div></div>
                            <form method="POST" action="/people/{{ $o->id }}/leave" onsubmit="return confirm('Mark {{ e($o->name) }} as left? The bed will become free.')">
                                @csrf
                                <button class="btn btn-outline-danger" type="submit">Left</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="residents-heading text-muted"><i class="bi bi-person" aria-hidden="true"></i>No residents yet</div>
                @endif

                @if($left > 0)
                    <a class="btn btn-brand add-resident" href="/rooms/{{ $s->id }}/assign"><i class="bi bi-person-plus" aria-hidden="true"></i>Add {{ ucfirst($u->word('person')) }}</a>
                @endif
            </article>
        </div>
    @empty
        <div class="col-12"><div class="empty-state">No {{ strtolower($u->word('spaces')) }} yet. Add them in Settings.</div></div>
    @endforelse
    </div>
    <div class="no-results" id="no-room-results">No rooms or residents match that search.</div>
</div>

<script>
    (() => {
        const searchInput = document.getElementById('room-search');
        const roomCards = [...document.querySelectorAll('.room-item')];
        const resultsLabel = document.getElementById('room-results');
        const emptySearchState = document.getElementById('no-room-results');

        if (!searchInput) {
            return;
        }

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim().toLocaleLowerCase();
            let visibleRooms = 0;

            roomCards.forEach((roomCard) => {
                const matches = roomCard.textContent.toLocaleLowerCase().includes(query);
                roomCard.classList.toggle('d-none', !matches);
                visibleRooms += matches ? 1 : 0;
            });

            resultsLabel.textContent = `${visibleRooms} ${visibleRooms === 1 ? 'result' : 'results'}`;
            emptySearchState.style.display = visibleRooms === 0 && roomCards.length > 0 ? 'block' : 'none';
        });
    })();
</script>
@endsection
