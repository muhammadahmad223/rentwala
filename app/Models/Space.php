<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    public function occupants()
    {
        return $this->hasMany(Occupant::class);
    }
}
