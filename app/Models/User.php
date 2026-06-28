<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    protected $table = 'usuarios';
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'usuarios_papeis', 'fk_users_id', 'fk_roles_id');

    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasValidImage(): bool
    {
        return !empty($this->image) && File::exists(public_path('storage/' . ltrim($this->image, '/')));
    }

    public function getProfileImageUrlAttribute(): string
    {
        if ($this->hasValidImage()) {
            return asset('storage/' . ltrim($this->image, '/'));
        }

        return asset('dashboard/assets/img/undraw_profile.svg');
    }

}
