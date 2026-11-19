<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    use BelongsToOwner;

    protected $guarded = [];

    protected $casts = ['joined_on' => 'date', 'left_on' => 'date'];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
