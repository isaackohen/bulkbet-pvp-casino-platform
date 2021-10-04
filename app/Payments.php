<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';

    protected $fillable = ['secret', 'merchant_id', 'order_id', 'sum', 'user_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
