@extends('layout')
@section('content')
<h3 class="mb-3">Settings</h3>
<form method="POST" action="/settings" class="card card-body mb-4" style="max-width:640px">@csrf
<label class="form-label">Name of your {{ $u->business_type=='hostel' ? 'hostel' : 'property' }}</label>
<div class="d-flex gap-2"><input class="form-control" name="business_name" value="{{ $u->business_name }}" required><button class="btn btn-brand">Save</button></div></form>

<h4 class="mb-3">Your {{ strtolower($u->word('spaces')) }}</h4>
<div class="row g-3 mb-4">
@foreach($spaces as $s)
<div class="col-12 col-xl-6"><div class="card card-body">
<form method="POST" action="/spaces/{{ $s->id }}">@csrf @method('PUT')
<div class="row g-2"><div class="col-12"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $s->name }}" required></div>
@if($u->business_type=='hostel')<div class="col-6"><label class="form-label">Beds</label><input type="number" min="1" class="form-control" name="beds" value="{{ $s->beds }}"></div>@endif
<div class="col-6"><label class="form-label">{{ $u->business_type=='hostel' ? 'Rent per bed' : 'Rent per month' }} (Rs)</label><input type="number" min="0" class="form-control" name="rent" value="{{ $s->rent }}" required></div></div>
<div class="small text-muted mt-2">{{ $s->used }} {{ $s->used==1 ? 'person lives' : 'people live' }} here now.</div>
<button class="btn btn-brand mt-2">Save changes</button></form>
<form method="POST" action="/spaces/{{ $s->id }}" class="mt-2" onsubmit="return confirm('Delete {{ e($s->name) }}?')">@csrf @method('DELETE')<button class="btn btn-outline-danger w-100">Delete {{ $s->name }}</button></form>
</div></div>
@endforeach</div>

<h4 class="mb-3">Add another {{ strtolower($u->word('space')) }}</h4>
<form method="POST" action="/spaces" class="card card-body" style="max-width:640px">@csrf
<label class="form-label">Name</label><input class="form-control mb-3" name="name" placeholder="{{ $u->word('space') }} name" required>
@if($u->business_type=='hostel')<label class="form-label">Beds</label><input type="number" min="1" class="form-control mb-3" name="beds" value="3">@endif
<label class="form-label">{{ $u->business_type=='hostel' ? 'Rent per bed' : 'Rent per month' }} (Rs)</label><input type="number" min="0" class="form-control mb-3" name="rent" value="0" required>
<button class="btn btn-brand">Add</button></form>
@endsection
