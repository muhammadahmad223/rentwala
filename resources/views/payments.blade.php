@extends('layout')
@section('content')
<style>
    .payments-ui{--pay-ink:#10343a;--pay-brand:#0b6e6e;--pay-muted:#6b7c80;--pay-line:#e4ecec;color:var(--pay-ink);padding-bottom:.25rem}
    .payments-ui .payments-head{margin-bottom:1rem}
    .payments-ui .payments-eyebrow{color:var(--pay-brand);font-size:.72rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase;margin-bottom:.2rem}
    .payments-ui .payments-title{font-size:clamp(1.3rem,4vw,1.75rem);font-weight:800;letter-spacing:-.045em;line-height:1.12;margin:0;overflow-wrap:anywhere}
    .payments-ui .payments-subtitle{color:var(--pay-muted);font-size:.82rem;margin:.3rem 0 0}
    .payments-ui .payments-grid{--bs-gutter-x:.75rem;--bs-gutter-y:.75rem}
    .payments-ui .payment-form{height:100%;padding:1rem;border:1px solid var(--pay-line);border-radius:17px;background:#fff;box-shadow:0 5px 16px #10343a0b}
    .payments-ui .form-title{display:flex;align-items:center;gap:.55rem;font-size:.96rem;font-weight:800;margin-bottom:.85rem}
    .payments-ui .form-title-icon{width:34px;height:34px;display:grid;place-items:center;border-radius:11px;background:#e7f3f1;color:var(--pay-brand)}
    .payments-ui .form-label{font-size:.78rem;font-weight:700;color:#385258;margin-bottom:.3rem}
    .payments-ui .form-control,.payments-ui .form-select{min-height:47px;border-color:#d6e1e2;border-radius:11px;font-size:.86rem;max-width:100%}
    .payments-ui .form-control:focus,.payments-ui .form-select:focus{border-color:#73aaa1;box-shadow:0 0 0 .2rem #0b6e6e18}
    .payments-ui .payment-save{width:100%;min-height:47px;margin-top:.15rem;border-radius:11px;background:var(--pay-brand);border-color:var(--pay-brand);font-size:.86rem;font-weight:750;white-space:normal}
    .payments-ui .history-panel{height:100%;overflow:hidden;border:1px solid var(--pay-line);border-radius:17px;background:#fff;box-shadow:0 5px 16px #10343a0b}
    .payments-ui .history-heading{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.9rem 1rem;border-bottom:1px solid var(--pay-line)}
    .payments-ui .history-title{font-size:.96rem;font-weight:800;margin:0}
    .payments-ui .history-count{flex:none;color:var(--pay-brand);background:#e7f3f1;border-radius:999px;padding:.28rem .55rem;font-size:.68rem;font-weight:750}
    .payments-ui .payment-history{padding:0 .9rem}
    .payments-ui .history-row{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(70px,.7fr) minmax(90px,.8fr);align-items:center;gap:.65rem;padding:.75rem .1rem;border-bottom:1px solid #edf1f1}
    .payments-ui .history-row:last-child{border-bottom:0}
    .payments-ui .history-person{min-width:0;font-size:.78rem;font-weight:750;overflow-wrap:anywhere}
    .payments-ui .history-space{display:block;color:var(--pay-muted);font-size:.69rem;font-weight:500;margin-top:.15rem;overflow-wrap:anywhere}
    .payments-ui .history-month{color:#50686c;font-size:.72rem;overflow-wrap:anywhere}
    .payments-ui .history-amount{color:#08755d;font-size:.79rem;font-weight:800;overflow-wrap:anywhere}
    .payments-ui .history-method{display:block;color:var(--pay-muted);font-size:.67rem;font-weight:600;margin-top:.12rem}
    .payments-ui .history-empty{padding:1.1rem .2rem;color:var(--pay-muted);font-size:.8rem}
    @media(max-width:430px){
        .payments-ui .history-row{grid-template-columns:minmax(0,1fr) auto;gap:.3rem .65rem}
        .payments-ui .history-month{grid-column:1;grid-row:2}
        .payments-ui .history-amount{grid-column:2;grid-row:1 / span 2;text-align:right}
    }
    @media(min-width:768px){
        .payments-ui .payments-grid{--bs-gutter-x:1rem;--bs-gutter-y:1rem}
        .payments-ui .payment-form{padding:1.15rem}
        .payments-ui .payment-history{padding:0 1rem}
    }
</style>

<div class="payments-ui">
    <header class="payments-head">
        <div class="payments-eyebrow">RentWala</div>
        <h1 class="payments-title">Payments</h1>
        <p class="payments-subtitle">Record rent received and review recent payments.</p>
    </header>

    <div class="row payments-grid">
        <div class="col-12 col-lg-5">
            <form method="POST" action="/payments" class="payment-form">@csrf
                <h2 class="form-title"><span class="form-title-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span>Record a payment</h2>
                <label class="form-label" for="occupant_id">Who paid?</label>
                <select id="occupant_id" name="occupant_id" class="form-select mb-3" required onchange="var o=this.selectedOptions[0];document.getElementById('amt').value=o.dataset.owes||''">
                    <option value="">Choose a name</option>
                    @foreach($people as $p)<option value="{{ $p->id }}" data-owes="{{ $p->owes }}" @selected($pick && $pick->id==$p->id)>{{ $p->name }} - {{ $p->space->name }} (owes Rs {{ number_format($p->owes) }})</option>@endforeach
                </select>
                <label class="form-label" for="amt">Amount received (Rs)</label><input id="amt" class="form-control mb-3" type="number" name="amount" min="1" value="{{ $pick?->owes }}" required>
                <label class="form-label" for="for_month">Rent for month</label><input id="for_month" class="form-control mb-3" type="month" name="for_month" value="{{ now()->format('Y-m') }}" required>
                <label class="form-label" for="paid_on">Date received</label><input id="paid_on" class="form-control mb-3" type="date" name="paid_on" value="{{ now()->toDateString() }}" required>
                <label class="form-label" for="method">How did they pay?</label>
                <select id="method" name="method" class="form-select mb-3"><option value="cash">Cash</option><option value="jazzcash">JazzCash</option><option value="easypaisa">Easypaisa</option><option value="bank">Bank</option></select>
                <button class="btn btn-brand payment-save" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i>Save payment</button>
            </form>
        </div>

        <div class="col-12 col-lg-7">
            <section class="history-panel" aria-labelledby="history-title">
                <div class="history-heading"><h2 class="history-title" id="history-title">Recent payments</h2><span class="history-count">{{ $history->count() }} shown</span></div>
                <div class="payment-history">
                    @forelse($history as $h)
                        <article class="history-row">
                            <div class="history-person">{{ $h->occupant->name }}<span class="history-space">{{ $h->occupant->space->name }}</span></div>
                            <div class="history-month">{{ $h->for_month->format('M Y') }}</div>
                            <div class="history-amount">Rs {{ number_format($h->amount) }}<span class="history-method">{{ ucfirst($h->method) }}</span></div>
                        </article>
                    @empty
                        <div class="history-empty">No payments saved yet.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
