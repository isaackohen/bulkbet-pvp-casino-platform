<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BonusLog extends Model{

    protected $table = 'bonus_log';

    protected $fillable = ['user_id', 'sum', 'remaining', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

}