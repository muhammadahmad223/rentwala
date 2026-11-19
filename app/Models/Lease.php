<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    protected $casts = ['start_date' => 'date'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function renter()
    {
        return $this->belongsTo(Renter::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getLabelAttribute()
    {
        return $this->unit?->name.' - '.$this->renter?->name;
    }
}
