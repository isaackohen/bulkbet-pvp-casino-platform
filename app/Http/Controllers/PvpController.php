<?php namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Pvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PvpController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct();
    }
	
	public function index(Request $request) {
		$games = Pvp::where('status', 0)->get();
		$ended = Pvp::where('status', 1)->orderBy('id', 'desc')->limit(6)->get();
		
		$rooms = [];
		foreach($games as $game) {
			$user = User::where('id', $game->user1)->first();
			$rooms[] = [
				'id' => $game->id,
				'username' => $user->username,
				'avatar' => $user->avatar,
				'price' => $game->price
			];
		}
		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.games.pvp', compact('rooms', 'ended'));
        }

		return view('layout')->with('page', view('pages.games.pvp', compact('rooms', 'ended')));
	}
    
	public static function getPriceCoin()
    {
        $coin = Pvp::select('price')->where('status', 0)->sum('price');
        if(!is_null($coin)) $coin; else $coin = 0;
        return $coin;
    }
	
	public function createRoom(Request $request)
    {
		if (\Cache::has('bet.user.' . $this->user->id)) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы слишком часто делаете ставку!',
                'icon'  => 'error'
            ]));
            return;
		}
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
		$count = Pvp::where('user1', $this->user->id)->where('status', 0)->count();
		if($request->get('value') > $this->user->balance) return response()->json(['type' => 'error', 'msg' => 'На вашем балансе не хватает монет для вырполнения дейсвия!']);
		if($count >= 3) return response()->json(['type' => 'error', 'msg' => 'Вы создали максимальное количество комнат!']);
		if(!$request->get('value')) return response()->json(['type' => 'error', 'msg' => 'Вы забыли указать сумму ставки!']);
		if($request->get('value') <= 0) return response()->json(['type' => 'error', 'msg' => 'Минимальная ставка 1 монета!']);
		if($this->user->balance <= 0) return response()->json(['type' => 'error', 'msg' => 'Вам не хватает монет для совершения ставки!']);

		$hash = bin2hex(random_bytes(16));
		
		$ticketFrom = 1;
		$room = new Pvp();
        $coins = preg_replace('/[^0-9.]/', '', $request->get('value'));
        $room->from1 = $ticketFrom;
        $room->to1 = $ticketFrom + floor($coins*10);
		$room->user1 = $this->user->id;
        $room->price = $coins;
        $room->hash = $hash;
        $room->save();
		
		$this->user->balance -= $coins;
		$this->user->save();
		
		$this->redis->publish('new.flip', json_encode([
			'id' 	 	 => $room->id,
			'username'   => $this->user->username,
			'avatar' 	 => $this->user->avatar,
			'price' 	 => round($room->price,2),
			'hash' 	 	 => $hash,
            'allBank' => $this->getPriceCoin()
		]));
		
		$this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => round($this->user->balance,2)
        ]));
		
		Pvp::where('updated_at', '>=', Carbon::today()->addDays(2))->delete();
		
		return response()->json(['type' => 'success', 'msg' => 'Игра создана!']);
    }
	
	public function joinRoom(Request $request)
	{
		if (\Cache::has('bet.user.' . $this->user->id)) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы слишком часто делаете ставку!',
                'icon'  => 'error'
            ]));
            return;
		}
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
		$room = Pvp::where('id', $request->get('id'))->first();
        $coins = $room->price;
		if($coins > $this->user->balance) return response()->json(['type' => 'error', 'msg' => 'Не достаточно средств!']);
		if($room->status == 1) return response()->json(['type' => 'error', 'msg' => 'Игра #'.$room->id.' уже началась!']);
		if(!$coins) return response()->json(['type' => 'error', 'msg' => 'Вы забыли указать сумму ставки!']);
		if($coins <= 0) return response()->json(['type' => 'error', 'msg' => 'Минимальная ставка 1 PT!']);
		if($this->user->balance <= 0) return response()->json(['type' => 'error', 'msg' => 'Вам не хватает PT для совершения ставки!']);
		if($room->user1 == $this->user->id) return response()->json(['type' => 'error', 'msg' => 'Вы не можете учавствовать в своей игре!']);
		
		$this->user->balance -= $coins;
		$this->user->save();
        
        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));
		$lastTicket = $room->to1;
        $room->from2 = $lastTicket + 1;
        $room->to2 = $lastTicket + floor($coins)*10;
		$room->user2 = $this->user->id;
		$room->price += $coins;
		
		$win_sum = round($room->price-($room->price/100*15), 2);

        $winnerTicket = mt_rand(1, $room->to2);
		if(($room->from1 <= $winnerTicket) && ($room->to1 >= $winnerTicket)) $winner = User::where('id', $room->user1)->first();
		if(($room->from2 <= $winnerTicket) && ($room->to2 >= $winnerTicket)) $winner = User::where('id', $room->user2)->first();
        $room->winner_id = $winner->id;
        $room->winner_ticket = $winnerTicket;
        $room->win_sum = $win_sum;
        $room->status = 1;
        $room->save();
		
		$user1 = User::where('id', $room->user1)->first();
		$user2 = User::where('id', $room->user2)->first();
		if($winner->id == $user1->id) {
			$loser = User::where('id', $user2->id)->first();
		} else {
			$loser = User::where('id', $user1->id)->first();
		}
		$user_win = User::where('id', $winner->id)->first();
		
		$returnValues = [
			'status' 	=> 'success',
            'user1'     => [
				'username' 	=> $user1->username,
				'avatar' 	=> $user1->avatar,
				'from' 		=> $room->from1,
				'to' 		=> $room->to1
			],
            'user2'     => [
				'username' 	=> $user2->username,
				'avatar' 	=> $user2->avatar,
				'from' 		=> $room->from2,
				'to' 		=> $room->to2
			],
            'winner'    => [
				'username' 	=> $user_win->username,
				'avatar' 	=> $user_win->avatar,
                'ticket' 	=> $room->winner_ticket
			],
            'loser'    => [
				'username' 	=> $loser->username,
				'avatar' 	=> $loser->avatar
			],
            'game'      => [
                'id'        => $room->id,
                'price'     => $room->price,
                'hash'      => $room->hash
            ],
            'allBank' => $this->getPriceCoin()
        ];
		
        $winner->balance += $win_sum;
        $winner->save();
		
		if($winner->referred_by) {
			$ref = User::where('affiliate_id', $winner->referred_by)->first();
			$ref_perc = $this->getRefer($ref->affiliate_id);
			$ref->ref_money += $win_sum/100*$ref_perc;
			$ref->ref_money_history += $win_sum/100*$ref_perc;
			$ref->save();
		}
		
		$this->redis->publish('end.flip', json_encode($returnValues));
        
        $this->redis->publish('updateBalanceAfter', json_encode([
            'id'    => $winner->id,
            'balance' => round($winner->balance,2)
        ]));
		
		Pvp::where('updated_at', '>=', Carbon::today()->addDays(2))->delete();
		
		return response()->json(['type' => 'success', 'msg' => 'Вы вступили в игру #'.$room->id.'!']);
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
}