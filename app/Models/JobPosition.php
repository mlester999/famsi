<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'job_type',
        'employment_type',
        'responsibilities',
        'qualifications',
        'schedule'
    ];
}
