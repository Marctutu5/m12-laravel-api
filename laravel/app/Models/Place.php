<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'file_id',
        'latitude',
        'longitude',
        'author_id',
        'visibility_id'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favorited()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
    
    public function favoritedByUser(User $user)
    {
        $count = Favorite::where([
            ['user_id',  '=', $user->id],
            ['place_id', '=', $this->id],
        ])->count();

        return $count > 0;
    }

    public function favoritedByAuthUser()
    {
        $user = auth()->user();
        return $this->favoritedByUser($user);
    }

    public function visibility()
    {
        return $this->belongsTo(Visibility::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewed()
    {
        return $this->belongsToMany(User::class, 'reviews');
    }
    
    public function reviewedByUser(User $user)
    {
        $count = Review::where([
            ['author_id',  '=', $user->id],
            ['place_id', '=', $this->id],
        ])->count();

        return $count > 0;
    }

    public function reviewedByAuthUser()
    {
        $user = auth()->user();
        return $this->reviewedByUser($user);
    }    
}
