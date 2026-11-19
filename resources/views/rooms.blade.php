@extends('layout')
@section('content')
<h3 class="mb-3">{{ $u->word('spaces') }}</h3>
<div class="row g-3">
@forelse($spaces as $s)
@php($n = $s->occupants->count())@php($left = $s->beds - $n)
<div class="col-12 col-md-6 col-xl-4"><div class="card card-body h-100 {{ $left<=0 ? 'room-full' : ($n ? 'room-part' : 'room-empty') }}">
 <div class="d-flex justify-content-between align-items-start"><div class="fs-4 fw-bold">{{ $s->name }}</div>
 <span class="fw-bold {{ $left<=0 ? 'late' : 'good' }}">{{ $left<=0 ? 'Full' : ($n ? $left.' free' : 'Empty') }}</span></div>
 @if($u->business_type=='hostel')<div class="text-muted mb-2">{{ $n }} of {{ $s->beds }} beds used &middot; Rs {{ number_format($s->rent) }} per bed</div>
 @else<div class="text-muted mb-2">Rs {{ number_format($s->rent) }} per month</div>@endif
 @foreach($s->occupants as $o)
 <div class="d-flex align-items-center gap-2 py-2 border-top">
  @if($o->photo)<img class="avatar" src="{{ asset('storage/'.$o->photo) }}" alt="">@else<span class="avatar"><i class="bi bi-person fs-4"></i></span>@endif
  <div class="flex-grow-1"><div class="fw-semibold">{{ $o->name }}</div><div class="small text-muted">{{ $o->phone }}</div></div>
  <form method="POST" action="/people/{{ $o->id }}/leave" onsubmit="return confirm('Mark {{ e($o->name) }} as left? The bed will become free.')">@csrf<button class="btn btn-sm btn-outline-danger" style="min-height:40px">Left</button></form></div>
 @endforeach
 @if($left>0)<a class="btn btn-brand mt-3" href="/rooms/{{ $s->id }}/assign"><i class="bi bi-person-plus"></i> Add {{ strtolower($u->word('person')) }}</a>@endif
</div></div>
@empty<div class="card card-body text-muted">No {{ strtolower($u->word('spaces')) }} yet. Add them in Settings.</div>@endforelse</div>
@endsection
