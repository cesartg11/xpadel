<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'conversation_id', 'sender', 'archive_url', 'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

}
