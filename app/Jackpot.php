<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Jackpot extends Model
{
	const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_PRE_FINISH = 2;
    const STATUS_FINISHED = 3;
	
    protected $table = 'jackpot';
    
    protected $fillable = ['game_id', 'room', 'winner_id', 'winner_chance', 'winner_ticket', 'winner_sum', 'hash', 'price', 'status'];
    
    protected $hidden = ['created_at', 'updated_at'];
	
    public static function getBank($room) {
		$game = self::where('room', $room)->orderBy('id', 'desc')->first();
		if(is_null($game)) return 0;
        return $game->price;
    }
	
	public function users() {
        return self::join('jackpot_bets', 'jackpot.game_id', '=', 'jackpot_bets.game_id', 'jackpot.room', '=', 'jackpot_bets.room')
            ->join('users', 'jackpot_bets.user_id', '=', 'users.id')
            ->where('jackpot.game_id', $this->game_id)
            ->groupBy('users.id')
            ->select('users.*')
            ->get();
    }
	
	public function winner() {
        return $this->belongsTo('App\User');
    }
	
	public function bets() {
        return $this->hasMany('App\JackpotBets');
    }
}
