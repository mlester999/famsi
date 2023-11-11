<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'array',
    ];

    protected $fillable = [
        'title',
        'user_id',
        'author_id',
        'status'
    ];
}
