<?php

namespace App;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class JackpotBets extends Model
{
    protected $table = 'jackpot_bets';

    protected $fillable = ['game_id', 'room', 'user_id', 'sum', 'from', 'to', 'color', 'is_admin'];

    protected $hidden = ['created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function game()
    {
        return $this->belongsTo('App\Jackpot');
    }
}
