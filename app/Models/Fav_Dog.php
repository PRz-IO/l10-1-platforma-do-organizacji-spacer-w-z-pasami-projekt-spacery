<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[WithoutTimestamps]
class Fav_Dog extends Model
{
    protected $table = 'fav_dogs';

    protected $fillable = [];

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class);
    }
}
