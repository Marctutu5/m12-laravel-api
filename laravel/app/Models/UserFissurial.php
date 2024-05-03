<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFissurial extends Model
{
    use HasFactory;

    protected $table = 'users_fissurials';

    protected $fillable = ['user_id', 'fissurials_id', 'current_life']; // Asegúrate de incluir todos los campos que se pueden asignar masivamente

    public function user()
    {
        return $this->belongsTo(User::class); // Relación con el modelo User
    }

    public function fissurial()
    {
        return $this->belongsTo(Fissurial::class, 'fissurials_id'); // Relación con el modelo Fissurial
    }
}
