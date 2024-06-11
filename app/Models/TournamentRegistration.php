<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentRegistration extends Model
{
    use HasFactory;

    protected $table = 'tournament_registrations';

    protected $fillable = [
        'tournament_id', 'player1_id', 'player2_id',
    ];

    /**
     * Relación con la tabla tournaments
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Relación con la tabla user_profile
     */
    public function player1()
    {
        return $this->belongsTo(UserProfile::class, 'player1_id');
    }

    /**
     * Relación con la tabla user_profile
     */
    public function player2()
    {
        return $this->belongsTo(UserProfile::class, 'player2_id');
    }
}
