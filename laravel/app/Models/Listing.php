<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    protected static function booted()
    {
        static::creating(function ($listing) {
            $backpack = Backpack::where('user_id', $listing->seller_id)
                                ->where('item_id', $listing->item_id)
                                ->first();

            if ($backpack && $backpack->quantity >= $listing->quantity) {
                $backpack->decrement('quantity', $listing->quantity);
                if ($backpack->quantity == 0) {
                    $backpack->delete();
                }
            } else {
                // Si no hay suficientes items, impide la creación del listing
                return false;
            }
        });

        static::deleted(function ($listing) {
            $backpack = Backpack::firstOrCreate(
                ['user_id' => $listing->seller_id, 'item_id' => $listing->item_id],
                ['quantity' => 0]
            );
            $backpack->increment('quantity', $listing->quantity);
        });
    }
}
