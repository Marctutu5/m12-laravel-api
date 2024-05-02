<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    use HasFactory;

    // Define los atributos que son asignables masivamente
    protected $fillable = ['name', 'power'];

    // Aquí puedes agregar métodos de relación o cualquier otro método adicional que necesites
}
