<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $table = 'tournaments';

    protected $fillable = [
        'club_profile_id', 'name', 'status', 'description', 'photo_url', 'start_date', 'end_date'
    ];

    /**
     * Relación con la tabla Tournament_registrations
     */
    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    /**
     * Relación con la tabla Tournament_matches
     */
    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }

    /**
     * Relación con la tabla club_profile
     */
    public function clubProfile()
    {
        return $this->belongsTo(clubProfile::class);
    }
}
