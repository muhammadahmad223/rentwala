<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'owner_id', 'phone', 'business_name', 'logo_path', 'business_type', 'onboarded', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'onboarded' => 'boolean'];
    }

    public function ownerId(): int
    {
        return $this->role === 'staff' ? $this->owner_id : $this->id;
    }

    // words change with the business type, so a hostel owner sees "Rooms" and a landlord sees "Houses"
    public function word(string $k): string
    {
        $m = [
            'hostel' => ['space' => 'Room', 'spaces' => 'Rooms', 'person' => 'Resident', 'people' => 'Residents'],
            'house' => ['space' => 'House', 'spaces' => 'Houses', 'person' => 'Tenant', 'people' => 'Tenants'],
            'building' => ['space' => 'Flat or shop', 'spaces' => 'Flats and shops', 'person' => 'Tenant', 'people' => 'Tenants'],
        ];

        return $m[$this->business_type ?? 'hostel'][$k] ?? $k;
    }
}
