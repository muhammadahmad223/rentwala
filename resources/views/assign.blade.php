@extends('layout')
@section('content')
<a href="/rooms" class="text-decoration-none">&larr; Back</a>
<h3 class="mt-2">Add {{ strtolower($u->word('person')) }} to {{ $space->name }}</h3>
<p class="text-muted">{{ $free }} {{ $free==1 ? 'place is' : 'places are' }} free in this {{ strtolower($u->word('space')) }}.</p>
<form method="POST" action="/rooms/{{ $space->id }}/assign" enctype="multipart/form-data" class="card card-body" style="max-width:640px">@csrf
<label class="form-label">Full name</label><input class="form-control mb-3" name="name" value="{{ old('name') }}" required>
<label class="form-label">Mobile number</label><input class="form-control mb-3" name="phone" inputmode="tel" placeholder="03xx xxxxxxx" value="{{ old('phone') }}" required>
<label class="form-label">CNIC number</label><input class="form-control mb-3" name="cnic" inputmode="numeric" placeholder="xxxxx-xxxxxxx-x" value="{{ old('cnic') }}">
<label class="form-label">Photo (take a picture or choose one)</label><input class="form-control mb-3" type="file" name="photo" accept="image/*" capture="environment">
<label class="form-label">Rent per month (Rs)</label><input class="form-control mb-3" type="number" name="rent" min="0" value="{{ old('rent', $space->rent) }}" required>
<div class="row g-3 mb-3"><div class="col-6"><label class="form-label">Joining date</label><input class="form-control" type="date" name="joined_on" value="{{ old('joined_on', now()->toDateString()) }}" required></div>
<div class="col-6"><label class="form-label">Rent due on day</label><input class="form-control" type="number" name="due_day" min="1" max="28" value="{{ old('due_day', 1) }}" required></div></div>
<button class="btn btn-brand btn-lg">Save</button></form>
@endsection
