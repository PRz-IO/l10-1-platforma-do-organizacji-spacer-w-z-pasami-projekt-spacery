<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[WithoutTimestamps]
class Account extends Model
{
    protected $table = 'accounts';
    protected $primaryKey = 'id';

    protected $fillable =[
        'Name',
        'Last_Name',
        'Login',
        'Password',
        'Date',       
        'Acc_State',   
        'Email',
        'Phone_Num'    
    ];

    public function worker(): HasOne
    {
        return $this->hasOne(Worker::class, 'account_id', 'id');
    }

    public function volunteer(): HasOne
    {
        return $this->hasOne(Volunteer::class, 'account_id', 'id');
    }
}