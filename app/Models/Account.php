<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[WithoutTimestamps]
class Account extends Model
{
    protected $fillable =[
        'Name',
        'Last_Name',
        'Login',
        'Password',
        'Acc_State',
        'Email',
        'Phone_Num'
    ];
    public function worker():HasOne
    {
        return $this->hasOne(Worker::class);
    }
    public function volunteer():HasOne
    {
        return $this->hasOne(Volunteer::class);
    }
}
