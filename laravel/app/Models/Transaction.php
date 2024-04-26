<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'seller_id', 'item_id', 'quantity', 'price'];

    /**
     * Get the buyer associated with the transaction.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller associated with the transaction.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the item involved in the transaction.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
