<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversations';

    protected $fillable = [
        'participant_1', 'participant_2',
    ];

    /**
     * Relación con la tabla messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Relación con la tabla users
     */
    public function participant1()
    {
        return $this->belongsTo(User::class, 'participant_1');
    }

    /**
     * Relación con la tabla users
     */
    public function participant2()
    {
        return $this->belongsTo(User::class, 'participant_2');
    }
}
