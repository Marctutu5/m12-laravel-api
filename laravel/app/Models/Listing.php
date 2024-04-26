<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'item_id', 'quantity', 'price'];

    /**
     * Get the seller that owns the listing.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the item associated with the listing.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
