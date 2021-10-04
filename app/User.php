<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username', 'avatar', 'user_id', 'balance', 'ip', 'is_admin', 'is_moder', 'is_youtuber', 'banchat', 'fake', 'ban', 'affiliate_id', 'referred_by', 'ref_money', 'ref_money_history',
    ];
}
