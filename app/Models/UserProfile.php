<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id','name','surname','age','telephone','profile_photo_path',
    ];

    /**
     * Define la relación inversa con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
