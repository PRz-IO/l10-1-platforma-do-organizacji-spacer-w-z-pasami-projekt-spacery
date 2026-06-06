<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[WithoutTimestamps]
class Dog extends Model
{
    protected $fillable = [
        'Name',
        'Age',
        'Behaviour',
        'State',
        'Photo'
    ];

    public function fav_dogs():HasMany
    {
        return $this->hasMany(Fav_Dog::class);
    }

    public function schedules():HasMany
    {
        return $this->hasMany(Schedule::class);
    }



    public function getPhotoAttribute($value)
    {
        if (empty($value)) {
            return '/images/default_dog.png';
        }

        return $value;
    }

}
