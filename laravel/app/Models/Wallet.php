<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'coins'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Aumenta el número de monedas en la wallet.
     *
     * @param int $coins Cantidad de monedas a añadir.
     */
    public function addCoins($coins)
    {
        $this->coins += $coins;  // Asume que 'coins' es una columna en tu tabla 'wallets'
        $this->save();
    }

    /**
     * Disminuye el número de monedas en la wallet, si hay suficientes monedas para restar.
     *
     * @param int $coins Cantidad de monedas a restar.
     * @return bool Retorna true si la operación fue exitosa, de lo contrario false.
     */
    public function subtractCoins($coins)
    {
        if ($this->coins >= $coins) {
            $this->coins -= $coins;
            $this->save();
            return true;
        }
        return false;
    }
}
