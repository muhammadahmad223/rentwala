<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToOwner
{
    protected static function bootBelongsToOwner(): void
    {
        static::addGlobalScope('owner', function (Builder $q) {
            $u = auth()->user();
            if ($u && $u->role !== 'super_admin') {
                $q->where($q->getModel()->getTable().'.owner_id', $u->ownerId());
            }
        });
        static::creating(function ($m) {
            if (empty($m->owner_id) && auth()->check()) {
                $m->owner_id = auth()->user()->ownerId();
            }
        });
    }
}
