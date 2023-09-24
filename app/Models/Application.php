<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'job_position_id',
        'file_name',
        'file_path',
        'status'
    ];
}
