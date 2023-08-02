<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'finish_time',
        'comments',
        'applicant_id',
        'hr_manager_id',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function hr_manager(): BelongsTo
    {
        return $this->belongsTo(HrManager::class);
    }
}
