<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubHour extends Model
{
    use HasFactory;

    protected $table = 'club_hours';

    protected $fillable = [
        'club_profile_id', 'day_of_week', 'opening_time', 'closing_time'
    ];

    /**
     * Relación con la tabla club_profile
     */
    public function clubProfile()
    {
        return $this->belongsTo(ClubProfile::class);
    }
}
