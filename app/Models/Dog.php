<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;

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
            return asset('images/default_dog.png');
        }

        if (str_starts_with($value, '/')) {
        $path = public_path($value);

        if (!File::exists($path)) {
            return asset('images/default_dog.png');
        }
    }

        return $value;
    }

}
