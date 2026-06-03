<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[WithoutTimestamps]
class Worker extends Model
{
    protected $table = 'workers';

    protected $primaryKey = 'id';

        protected $fillable =[
        'Account_Id',
        'Is_Admin'
    ];
    public function account():BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
    public function schedules():HasMany
    {
        return $this->hasMany(Schedule::class, 'account_id', 'id');
    }
    
}
