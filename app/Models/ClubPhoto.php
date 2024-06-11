<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubPhoto extends Model
{

    use HasFactory;

    protected $table = 'club_photos';

    protected $fillable = [
        'club_profile_id', 'photo_url', 'photo_type'
    ];

    /**
     * Relacion con la tabla Club_profile
     */
    public function clubProfile()
    {
        return $this->belongsTo(ClubProfile::class);
    }
}
