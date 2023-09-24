<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function jobPosition(): HasMany
    {
        return $this->hasMany(JobPosition::class);
    }
}
