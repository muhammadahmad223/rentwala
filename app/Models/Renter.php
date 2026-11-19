<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Renter extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    public function getLabelAttribute()
    {
        return $this->name.' ('.$this->phone.')';
    }
}
