<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model{

    protected $table = 'bonus';

    protected $fillable = ['sum', 'color', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

}