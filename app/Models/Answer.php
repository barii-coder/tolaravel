<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = [];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
