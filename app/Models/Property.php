<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function getLabelAttribute()
    {
        return $this->name;
    }
}
