<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $table = 'promocode';

    protected $fillable = ['code', 'limit', 'amount', 'count_use'];

    protected $hidden = ['created_at', 'updated_at'];
}
