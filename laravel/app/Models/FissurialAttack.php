<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FissurialAttack extends Model
{
    use HasFactory;

    protected $table = 'fissurials_attacks'; // Especifica el nombre de la tabla si no sigue la convención de nombres de Laravel

    protected $fillable = ['fissurial_id', 'attack_id']; // Los campos que se pueden asignar masivamente

    // Puedes definir relaciones aquí si es necesario
    public function fissurial()
    {
        return $this->belongsTo(Fissurial::class);
    }

    public function attack()
    {
        return $this->belongsTo(Attack::class);
    }
}
