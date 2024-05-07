<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'x', 'y', 'scene', 'route'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function userCollectedItems()
    {
        return $this->hasMany(UserCollectedItem::class);
    }
}
