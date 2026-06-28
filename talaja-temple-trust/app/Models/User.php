<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'pan', 'name_as_per_pan',
        'address', 'type', 'is_active', 'mobile_verified_at', 'last_login_at',
        'last_login_ip', 'mfa_settings', 'guide_mode',
    ];

    protected $hidden = [
        'password', 'remember_token', 'mfa_settings',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'mfa_settings' => 'array',
            'is_active' => 'boolean',
            'guide_mode' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->type, ['admin', 'staff', 'trustee']) && $this->is_active;
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function roomBookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function hallBookings()
    {
        return $this->hasMany(HallBooking::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['name', 'email', 'type', 'is_active'])
            ->logOnlyDirty()->useLogName('user');
    }
}
