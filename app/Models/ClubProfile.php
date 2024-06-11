<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubProfile extends Model
{

    use HasFactory;

    protected $table = 'club_profile';

    protected $fillable = [
        'user_id', 'name', 'telephone', 'address', 'province', 'description',
    ];

    /**
     * Relación con la tabla Users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la tabla Club_photos
     */
    public function photos()
    {
        return $this->hasMany(ClubPhoto::class);
    }

    /**
     * Relación con la tabla Club_hours
     */
    public function hours()
    {
        return $this->hasMany(ClubHour::class);
    }

    /**
     * Relación con la tabla Courts
     */
    public function courts()
    {
        return $this->hasMany(Court::class);
    }

    /**
     * Relación con la tabla Classes
     */
    public function classes()
    {
        return $this->hasMany(ClubClass::class);
    }

    /**
     * Relación con la tabla Tournaments
     */
    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }
}
