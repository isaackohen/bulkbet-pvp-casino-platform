<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Double extends Model
{
    protected $table = 'double';

    protected $fillable = ['winner_num', 'winner_color', 'price', 'status', 'ranked', 'hash'];

    protected $hidden = ['created_at', 'updated_at'];
}
