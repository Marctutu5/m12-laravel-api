<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCollectedItem extends Model
{
    use HasFactory;

    protected $fillable = ['map_item_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapItem()
    {
        return $this->belongsTo(MapItem::class);
    }
}
