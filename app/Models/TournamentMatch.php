<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{

    use HasFactory;

    protected $table = 'tournament_matches';

    protected $fillable = [
        'tournament_id', 'round_number', 'court_id', 'pair1_id', 'pair2_id', 'set1', 'set2', 'set3', 'result', 'scheduled_start_time',
    ];

    /**
     * Relación con la tabla tournaments
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Relación con la tabla Courts
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Relación con la tabla user_profile
     */
    public function pair1()
    {
        return $this->belongsTo(TournamentRegistration::class, 'pair1_id');
    }

    /**
     * Relación con la tabla user_profile
     */
    public function pair2()
    {
        return $this->belongsTo(TournamentRegistration::class, 'pair2_id');
    }
}
