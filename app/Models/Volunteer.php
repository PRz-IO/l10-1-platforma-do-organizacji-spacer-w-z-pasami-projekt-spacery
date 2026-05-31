<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[WithoutTimestamps]
class Volunteer extends Model
{
    protected $table = 'Volunteers';
    protected $primaryKey = 'id';    

    protected $fillable =[
        'Account_Id',
        'Is_Experienced'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'Account_Id', 'Id');
    }

    public function fav_dogs(): HasMany
    {
        return $this->hasMany(Fav_Dog::class, 'Volunteer_Id', 'Id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'Volunteer_Id', 'Id');
    }
}