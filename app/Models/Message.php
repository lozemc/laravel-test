<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message'];

    protected $casts = [
        'timestamp' => 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
