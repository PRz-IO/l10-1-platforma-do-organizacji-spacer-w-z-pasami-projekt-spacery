<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[WithoutTimestamps]
class Volunteer extends Model
{
    protected $table = 'volunteers'; 
    protected $primaryKey = 'id';    

    protected $fillable =[
        'account_id',
        'Is_Experienced'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function fav_dogs(): HasMany
    {
        return $this->hasMany(Fav_Dog::class, 'volunteer_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'volunteer_id', 'id');
    }
}