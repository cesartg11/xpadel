<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $table = 'courts';

    protected $fillable = [
        'club_profile_id', 'number', 'type',
    ];

    public function clubProfile()
    {
        return $this->belongsTo(ClubProfile::class);
    }

    // Suponiendo que las pistas pueden tener múltiples reservas
    public function rental()
    {
        return $this->hasMany(CourtRental::class);
    }
}
