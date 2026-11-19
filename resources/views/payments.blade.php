@extends('layout')
@section('content')
<h3 class="mb-3">Payments</h3>
<div class="row g-3"><div class="col-lg-5">
<form method="POST" action="/payments" class="card card-body">@csrf
<label class="form-label">Who paid?</label>
<select name="occupant_id" class="form-select mb-3" required onchange="var o=this.selectedOptions[0];document.getElementById('amt').value=o.dataset.owes||''">
<option value="">Choose a name</option>
@foreach($people as $p)<option value="{{ $p->id }}" data-owes="{{ $p->owes }}" @selected($pick && $pick->id==$p->id)>{{ $p->name }} - {{ $p->space->name }} (owes Rs {{ number_format($p->owes) }})</option>@endforeach</select>
<label class="form-label">Amount received (Rs)</label><input id="amt" class="form-control mb-3" type="number" name="amount" min="1" value="{{ $pick?->owes }}" required>
<label class="form-label">Rent for month</label><input class="form-control mb-3" type="month" name="for_month" value="{{ now()->format('Y-m') }}" required>
<label class="form-label">Date received</label><input class="form-control mb-3" type="date" name="paid_on" value="{{ now()->toDateString() }}" required>
<label class="form-label">How did they pay?</label>
<select name="method" class="form-select mb-3"><option value="cash">Cash</option><option value="jazzcash">JazzCash</option><option value="easypaisa">Easypaisa</option><option value="bank">Bank</option></select>
<button class="btn btn-brand btn-lg">Save payment</button></form></div>
<div class="col-lg-7"><div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr><th>Name</th><th>Month</th><th>Amount</th><th>Paid by</th></tr></thead><tbody>
@forelse($history as $h)<tr><td>{{ $h->occupant->name }}<div class="small text-muted">{{ $h->occupant->space->name }}</div></td><td>{{ $h->for_month->format('M Y') }}</td><td class="fw-bold">Rs {{ number_format($h->amount) }}</td><td>{{ ucfirst($h->method) }}</td></tr>
@empty<tr><td colspan="4" class="text-muted p-4">No payments saved yet.</td></tr>@endforelse</tbody></table></div></div></div></div>
@endsection
