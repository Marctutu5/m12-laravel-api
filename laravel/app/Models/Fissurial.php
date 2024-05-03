<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fissurial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo', 'original_life'];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class, 'fissurials_attacks');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_fissurials')
                    ->withPivot('current_life')
                    ->withTimestamps();
    }

}
