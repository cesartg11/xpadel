<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'age',
        'telephone',
        'profile_photo_path',
    ];

    /**
     * Define la relación inversa con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alquileres()
    {
        return $this->hasMany(CourtRental::class);
    }

    // Relación con reservas de clases
    public function clases()
    {
        return $this->hasMany(ClubClassRegistration::class);
    }

    // Relación con torneos
    public function torneos()
    {
        return TournamentRegistration::where(function ($query) {
            $query->where('player1_id', $this->id)
                ->orWhere('player2_id', $this->id);
        });
    }
}
