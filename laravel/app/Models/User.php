<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Agregar relación con Wallet
    public function wallet()
    {
        return $this->hasOne(Wallet::class);  // Asume que la tabla 'wallets' tiene 'user_id' como clave foránea
    }

    public function position()
    {
        return $this->hasOne(UserPosition::class);
    }
    public function userCollectedItems()
    {
        return $this->hasMany(UserCollectedItem::class);
    }

    public function backpacks()
    {
        return $this->hasMany(Backpack::class);
    }
    
    public function canAccessFilament() : bool
    {
        $roles = [Role::ADMIN, Role::EDITOR];
        Log::debug("User with role '{$this->role->name}' accessing Filament");
        return in_array($this->role->id, $roles);
    }

    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN;
    }

    public function isEditor()
    {
        return $this->role_id == Role::EDITOR;
    }

    public function isPublisher()
    {
        return $this->role_id == Role::AUTHOR;
    }
}
