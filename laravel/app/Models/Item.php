<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo'];

    public function backpacks()
    {
        return $this->hasManyThrough(Backpack::class, User::class);
    }
}
