<?php namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Battle;
use App\BattleBets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BattleController extends Controller
{
	public function __construct() {
		parent::__construct();
        $this->game = self::getLastGame();
    }
	
	public function getLastGame() {
        $game = Battle::orderBy('id', 'desc')->first();
        if(is_null($game)) $game = self::newGame();
        return $game;
    }
    
	public static function getPriceBattle()
    {
        $battle = Battle::select('price')->orderBy('id', 'desc')->first(); 
        if(!is_null($battle)) $battle = $battle->price; else $battle = 0;
        return $battle;
    }
	
	private function getBets() {
        $bets = BattleBets::where('battle_bets.game_id', $this->game->id)
				->select('battle_bets.user_id', DB::raw('SUM(battle_bets.price) as price'), 'users.username', 'users.avatar', 'battle_bets.color')
				->join('users', 'users.id', '=', 'battle_bets.user_id')
				->groupBy('battle_bets.user_id', 'battle_bets.color')
				->orderBy('price', 'desc')
				->get();
        return $bets;
    }
	
	public function index(Request $request) {
		$game = Battle::orderBy('id', 'desc')->first();
        $bets = self::getBets();
		$factor = [self::getXForGame($this->game, 'red'), self::getXForGame($this->game, 'blue')];
		$bank = [self::getBankForGame($this->game, 'red'), self::getBankForGame($this->game, 'blue')];
		$chances = [self::getChanceOfColor('red', $this->game), self::getChanceOfColor('blue', $this->game)];
		$tickets = self::getTicketsOfGame($this->game);
		$lastwins = Battle::orderBy('id', 'desc')->where('status', Battle::STATUS_FINISHED)->limit(15)->get();
		if ($request->pjax() && $request->ajax()) {
			return view('pages.games.battle', compact('game', 'bets', 'factor', 'bank', 'chances', 'tickets', 'lastwins'));
        }

		return view('layout')->with('page', view('pages.games.battle', compact('game', 'bets', 'factor', 'bank', 'chances', 'tickets', 'lastwins')));
	}
	
	public function newGame() {
		$hash = bin2hex(random_bytes(16));
		$game = Battle::create([
			'hash' => $hash
		]);
        $this->redis->set('battle.current.game', $game->id);
		
        return $game;
	}
	
	public function newBet(Request $request) {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Вы слишком часто делаете ставку!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
		
		$color = $request->get('type');
		$sum = round(preg_replace('/[^.0-9]/', '', $request->get('sum')), 2) ?? null;
		
		if(is_null($color) || $color != 'red' && $color != 'blue') return response()->json(['msg' => 'Цвет не найден', 'type' => 'error']);
		if(is_null($sum)) return response()->json(['msg' => 'Вы ввели неверную сумму', 'type' => 'error']);
		if($sum < $this->settings->battle_min_bet) return response()->json(['msg' => 'Минимальная сумма ставки '.$this->settings->battle_min_bet.' PT!', 'type' => 'error']);
		if($sum > $this->settings->battle_max_bet) return response()->json(['msg' => 'Максимальная сумма ставки '.$this->settings->battle_min_bet.' PT!', 'type' => 'error']);
		if($sum > $this->user->balance) return response()->json(['msg' => 'Недостаточно средств для ставки', 'type' => 'error']);
		if($this->game->status == Battle::STATUS_PRE_FINISH || $this->game->status == Battle::STATUS_FINISHED) return response()->json(['msg' => 'Игра уже началась или закончилась!', 'type' => 'error']);
		
		$colorCheck = BattleBets::where('game_id', $this->game->id)->where('color', '!=', $color)->where('user_id', $this->user->id)->count();
		$countbets = BattleBets::where('game_id', $this->game->id)->where('user_id', $this->user->id)->count();
		if($countbets >= 3) return response()->json(['msg' => 'Разрешено только 3 ставки!', 'type' => 'error']);
		if($colorCheck) return response()->json(['msg' => 'Вы уже сделали ставку на другой цвет!', 'type' => 'error']);
		
		$bet = new BattleBets();
		$bet->user()->associate($this->user);
		$bet->price = $sum;
		$bet->color = $color;
		$bet->game()->associate($this->game);
		$bet->save();
		
		$bets = BattleBets::where('game_id', $this->game->id);
		$this->game->price = $bets->sum('price');
		
		$this->user->balance -= round($sum, 2);
		$this->user->save();
		
		$this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => round($this->user->balance, 2)
        ]));
		
		$this->game->save();
		$bets = BattleBets::where('game_id', $this->game->id)->get();
		
		$bet_red = [];
		$bet_blue = [];
		
		foreach($bets->where('color', 'red') as $b) {
            $user = $this->findUser($b->user_id);
            $bet_red[] = [
                'user_id'   => $b->user_id,
                'avatar'    => $user->avatar,
                'username'  => $user->username,
                'price'     => $b->price,
                'color'     => $b->color
            ];
        }
		
		foreach($bets->where('color', 'blue') as $b) {
            $user = $this->findUser($b->user_id);
            $bet_blue[] = [
                'user_id'   => $b->user_id,
                'avatar'    => $user->avatar,
                'username'  => $user->username,
                'price'     => $b->price,
                'color'     => $b->color
            ];
        }

		$this->redis->publish('battle.newBet', json_encode([
			'bank' 		=> [round(self::getBankForGame($this->game, 'red'), 2), round(self::getBankForGame($this->game, 'blue'), 2)],
			'bets' 		=> self::getBets(),
			'tickets' 	=> self::getTicketsOfGame($this->game),
			'factor' 	=> [self::getXForGame($this->game, 'red'), self::getXForGame($this->game, 'blue')],
			'chances' 	=> [round(self::getChanceOfColor('red', $this->game)), round(self::getChanceOfColor('blue', $this->game))],
            'allBank' => round($this->getPriceBattle(),2)
		]));
		
		if(self::getUserInGame()[0] >= 1 && self::getUserInGame()[1] >= 1) {
			if($this->game->status < Battle::STATUS_PLAYING) {
				$this->game->status = Battle::STATUS_PLAYING;
				$this->game->save();
				$this->startTimer();
			}
        }
		
		return response()->json(['msg' => 'Ваша ставка принята!', 'type' => 'success']);
	}
	
	public function getSlider() {
        $winTicket = mt_rand(1, 1000);
		if($this->game->winner_team == 'red') {
			$winTicket = mt_rand(1, self::getTicketsOfGame($this->game)[0]);
		}
		if($this->game->winner_team == 'blue') {
			$winTicket = mt_rand(self::getTicketsOfGame($this->game)[1], 1000);
		}
		$red = self::getChanceOfColor('red', $this->game) * 10;
		$winner = 'red';
		if($winTicket > $red) $winner = 'blue';
		
        $this->game->status         = Battle::STATUS_FINISHED;
		$this->game->winner_team 	= $winner;
		$this->game->winner_ticket 	= $winTicket;
		$this->game->winner_factor 	= self::getXForGame($this->game, $winner);
		$this->game->commission 	= self::sendWinMoney($this->game);
        $this->game->save();

        $returnValue = [
            'ticket' => $winTicket,
			'game' => $this->game
        ];

        return response()->json($returnValue);
	}
	
	public function sendWinMoney($game) {
		$color = 'blue';
		$comission = 0;
		if($game->winner_team == 'red') $color = 'red'; 
		$bets = BattleBets::where('game_id', $game->id)->where('color', $color)->get();
		foreach($bets as $bet) {
			$user = User::find($bet->user_id);
			$winmoney = $bet->price * $this->getXForGame($game, $color);
			$comission += $winmoney * ($this->settings->battle_commission / 100);
			$sum = round($winmoney - ($winmoney * ($this->settings->battle_commission / 100)), 2);
			$user->balance += $sum;
			$user->save();
			
			if($user->referred_by) {
				$ref = User::where('affiliate_id', $user->referred_by)->first();
				$ref_perc = $this->getRefer($ref->affiliate_id);
				$ref->ref_money += $sum/100*$ref_perc;
				$ref->ref_money_history += $sum/100*$ref_perc;
				$ref->save();
			}
			
			$this->redis->publish('updateBalanceAfter', json_encode([
				'id'    => $user->id,
				'balance' => round($user->balance, 2)
			]));
		}
		return $comission;
	}
	
	private function getRefer($id) {
        $ref_count = User::where('referred_by', $id)->count();
        if($ref_count < 10) {
            $ref_perc = 0.5;
        } elseif($ref_count >= 10 && $ref_count < 100) {
            $ref_perc = 0.7;
        } elseif($ref_count >= 100 && $ref_count < 500) {
            $ref_perc = 1;
        } elseif($ref_count > 500) {
            $ref_perc = 1.5;
        }
        return $ref_perc;
    }
	
	private function findUser($id) {
        $user = User::where('id', $id)->first();
        return $user;
    }
	
	public static function getXForGame($game, $color) {
		$betsum = BattleBets::where('game_id', $game->id)->where('color', $color)->sum('price');
		if($betsum >= 0.01) {
			$x = round($game->price / $betsum, 2);
			return (!$game->price) ? 2 : $x;
		}
		return 2;
	}
	
	public static function getBankForGame($game, $color) {
		$bets = BattleBets::where('game_id', $game->id)->where('color', $color)->sum('price');
		return $bets;
	}
	
	public static function getChanceOfColor($color, $game) {
        $chance = 0;
        if(!is_null($color)) {
            $bet = BattleBets::where('game_id', $game->id)
                ->where('color', $color)
                ->sum('price');
			if($bet) $chance = round($bet / $game->price, 2) * 100;
        }
		$chance = (!$game->price) ? 50 : $chance;
        return $chance;
    }
	
	public static function getTicketsOfGame($game) {
		$red = self::getChanceOfColor('red', $game) * 10;
		if($red < 0.001) $red = 1;
		$blue = self::getChanceOfColor('blue', $game) * 10;
		if($red >= 0.001) $blue = ($red == 1000) ? ($red - 1) : $red;
		return [round($red), round($blue + 1)];
	}
	
	public function getStatus(Request $request) {
		$game = Battle::orderBy('id', 'desc')->first();
		
        return response()->json([
            'id'      	=> $game->id,
            'time'      => $this->settings->battle_timer,
            'status'    => $game->status
        ]);
    }
	
	public function getUserInGame() {
		$game = self::getLastGame();
        if(!$game) return;
		
		$users_red = [];
        $users_blue = [];
		
        $bets = BattleBets::where('game_id', $game->id)->orderBy('id', 'desc')->get();
        foreach($bets->where('color', 'red') as $bet) {
            $find = 0;
            foreach($users_red as $user) if($user == $bet->user_id) $find++;
            if($find == 0) $users_red[] = $bet->user_id;
        }
        foreach($bets->where('color', 'blue') as $bet) {
            $find = 0;
            foreach($users_blue as $user) if($user == $bet->user_id) $find++;
            if($find == 0) $users_blue[] = $bet->user_id;
        }
        
        return [count($users_red), count($users_blue)];
    }
	
	public function setStatus(Request $request) {
        $this->game->status = $request->get('status');
        $this->game->save();
        return $this->game->status;
    }
	
	private function startTimer() {
        $this->redis->publish('battle.startTime', json_encode([
            'time' => $this->settings->battle_timer
        ]));
    }
	
	public function gotThis(Request $request) {
		$color = $request->get('color');
		
		if($this->game->status > 1) return [
			'msg'       => 'Игра началась, вы не можете подкрутить!',
			'type'      => 'error'
		];
        
		if(!$this->game->id) return [
			'msg'       => 'Не удалось получить номер игры!',
			'type'      => 'error'
		];
		
		if(!$color) return [
			'msg'       => 'Не удалось получить цвет!',
			'type'      => 'error'
		];

		Battle::where('id', $this->game->id)->update([
			'winner_team'      => $color
		]);
		
		if($color == 'red') $color = 'красной';
		if($color == 'blue') $color = 'синей';
		
		return [
			'msg'       => 'Вы подкрутили '.$color.' коменде!',
			'type'      => 'success'
		];
	}
}