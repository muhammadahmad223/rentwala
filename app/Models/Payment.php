<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    protected $casts = ['for_month' => 'date', 'paid_on' => 'date'];

    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }
}
