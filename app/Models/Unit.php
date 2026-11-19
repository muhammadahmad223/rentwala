<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getLabelAttribute()
    {
        return $this->property?->name.' / '.$this->name;
    }
}
