@extends('layout')
@section('content')
<h4 class="mb-3">{{ $d['title'] }}</h4>
<div class="row g-3">
<div class="col-lg-4"><form method="POST" action="/m/{{ $type }}" class="card card-body">@csrf
@foreach($d['f'] as [$n,$l,$t,$o,$req])
<label class="form-label mt-2 mb-1">{{ $l }}</label>
@if($t=='select')<select name="{{ $n }}" class="form-select" @required($req)><option value="">Choose</option>
@foreach($o as $k=>$v)<option value="{{ $k }}" @selected(old($n)==$k)>{{ $v }}</option>@endforeach</select>
@else<input type="{{ $t }}" name="{{ $n }}" value="{{ old($n) }}" class="form-control" @required($req)>@endif
@endforeach
<button class="btn btn-brand mt-3">Save</button></form></div>
<div class="col-lg-8"><div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr>@foreach($d['cols'] as $c)<th>{{ ucfirst(str_replace(['_','.'],' ',$c)) }}</th>@endforeach<th></th></tr></thead><tbody>
@forelse($rows as $r)<tr>@foreach($d['cols'] as $c)<td>{{ data_get($r,$c) instanceof \Carbon\Carbon ? data_get($r,$c)->format('M Y') : data_get($r,$c) }}</td>@endforeach
<td class="text-end"><form method="POST" action="/m/{{ $type }}/{{ $r->id }}" onsubmit="return confirm('Delete this record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="{{ count($d['cols'])+1 }}" class="text-muted p-4">Nothing here yet. Fill the form to add the first record.</td></tr>@endforelse
</tbody></table></div></div></div></div>
@endsection
