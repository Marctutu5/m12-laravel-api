<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fissurial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo', 'original_life'];

    // Aquí puedes añadir relaciones o métodos adicionales si es necesario
}
