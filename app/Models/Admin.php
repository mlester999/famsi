<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Admin extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'contact_number',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = LogOptions::defaults()
        ->logFillable(['first_name'])
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");

        return $logOptions;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
