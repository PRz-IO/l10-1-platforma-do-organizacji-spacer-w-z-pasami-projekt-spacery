<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[WithoutTimestamps]
class Account extends Model
{
    protected $table = 'accounts';
    protected $primaryKey = 'Id'; 

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
        return $this->hasOne(Worker::class, 'Account_Id', 'Id');
    }

    public function volunteer(): HasOne
    {
        return $this->hasOne(Volunteer::class, 'Account_Id', 'Id');
    }
}