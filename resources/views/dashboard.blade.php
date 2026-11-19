@extends('layout')
@section('content')
@php($free = $beds - $used)
<div class="row g-3 mb-4">
<div class="col-6 col-lg-3"><div class="card card-body h-100"><div class="text-muted">{{ $u->business_type=='hostel' ? 'Empty beds' : 'Empty '.strtolower($u->word('spaces')) }}</div><div class="stat good">{{ $free }}</div><div class="small text-muted">{{ $used }} of {{ $beds }} in use</div></div></div>
<div class="col-6 col-lg-3"><div class="card card-body h-100"><div class="text-muted">{{ $u->word('people') }} living now</div><div class="stat">{{ $used }}</div></div></div>
<div class="col-6 col-lg-3"><div class="card card-body h-100"><div class="text-muted">Pending rent</div><div class="stat late">Rs {{ number_format($pending->sum('owes')) }}</div><div class="small text-muted">{{ $pending->count() }} people</div></div></div>
<div class="col-6 col-lg-3"><div class="card card-body h-100"><div class="text-muted">Received in {{ $month->format('F') }}</div><div class="stat good">Rs {{ number_format($collected) }}</div></div></div></div>

<h4 class="mb-3">Who has not paid for {{ $month->format('F') }}</h4>
@forelse($pending as $o)
<div class="card card-body mb-2"><div class="d-flex justify-content-between flex-wrap gap-2">
 <div><div class="fw-bold fs-5">{{ $o->name }}</div><div class="text-muted">{{ $o->space->name }} &middot; {{ $o->phone }}</div>
 <div>@if($o->late)<span class="late">{{ $o->late }} days late</span>@else Due on day {{ $o->due_day }}@endif</div></div>
 <div class="text-end"><div class="fs-4 fw-bold late">Rs {{ number_format($o->owes) }}</div></div></div>
 <div class="d-flex gap-2 mt-3"><a class="btn btn-brand flex-fill" href="/payments?occupant={{ $o->id }}"><i class="bi bi-cash-coin"></i> Receive payment</a>
 <a class="btn btn-outline-success flex-fill" target="_blank" href="{{ $o->wa }}"><i class="bi bi-whatsapp"></i> WhatsApp</a></div></div>
@empty
<div class="card card-body text-muted mb-3">{{ $used ? 'Everyone has paid. Well done.' : 'No one is living here yet. Open '.$u->word('spaces').' and add the first person.' }}</div>
@endforelse

<h4 class="mt-4 mb-3">{{ $u->word('spaces') }} with free space</h4>
<div class="d-flex flex-wrap gap-2">
@forelse($openSpaces as $s)<a class="btn btn-outline-secondary" href="/rooms/{{ $s->id }}/assign">{{ $s->name }}: {{ $s->beds - $s->used }} free</a>
@empty<span class="text-muted">Everything is full.</span>@endforelse</div>
@endsection
