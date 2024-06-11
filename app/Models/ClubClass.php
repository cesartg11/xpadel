<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubClass extends Model
{

    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'club_profile_id', 'level', 'court_id', 'start_time', 'end_time',
    ];

    /**
     * Relación con la tabla club_profile
     */
    public function clubProfile()
    {
        return $this->belongsTo(ClubProfile::class);
    }

    /**
     * Relación con la tabla courts
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Relación con la tabla class_registrations
     */
    public function registrations()
    {
        return $this->hasMany(ClubClassRegistration::class);
    }
}
