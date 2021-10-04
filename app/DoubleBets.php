<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DoubleBets extends Model
{
    protected $table = 'double_bets';

    protected $fillable = ['user_id', 'round_id', 'price', 'type', 'win', 'win_sum', 'is_fake'];

    protected $hidden = ['created_at', 'updated_at'];
}
