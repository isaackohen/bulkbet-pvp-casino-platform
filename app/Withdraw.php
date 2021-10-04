<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraw';
	
	protected $fillable = ['user_id', 'value', 'wallet', 'system', 'status'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
}
