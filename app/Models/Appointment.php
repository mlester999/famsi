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
        'interviewer_id',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function hrManager(): BelongsTo
    {
        return $this->belongsTo(HrManager::class, 'interviewer_id');
    }

    public function hrStaff(): BelongsTo
    {
        return $this->belongsTo(HrStaff::class, 'interviewer_id');
    }
}
