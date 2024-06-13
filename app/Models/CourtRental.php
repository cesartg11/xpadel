<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtRental extends Model
{

    use HasFactory;

    protected $table = 'court_rentals';

    protected $fillable = [
        'user_profile_id', 'court_id', 'start_time', 'end_time'
    ];

    /**
     * Relación con la tabla Courts
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

      /**
     * Relación con la tabla Users
     */
    public function userProfile()
    {
        return $this->hasMany(UserProfile::class);
    }

}
