<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[WithoutTimestamps]
class Schedule extends Model
{
    protected $fillable = [
        'Date',
        'Time',
        'Note',
        'Grade',
    ];

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'supervisor_id');
    }
}
