<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubClassRegistration extends Model
{

    use HasFactory;

    protected $table = 'class_registrations';

    protected $fillable = [
        'user_profile_id', 'class_id',
    ];

    /**
     * Relación con la tabla User_profile
     */
    public function userProfile()
    {
        return $this->hasMany(UserProfile::class);
    }

    /**
     * Relación con la tabla classes
     */
    public function clubClass()
    {
        return $this->belongsTo(ClubClass::class, 'class_id');
    }

}
