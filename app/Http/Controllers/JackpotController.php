<?php

namespace App\Http\Controllers;

use App\Jackpot;
use App\JackpotBets;
use App\Rooms;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JackpotController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct();
        $rooms = Rooms::where('status', 0)->orderBy('id', 'desc')->get();
        foreach ($rooms as $s) {
            $room = Rooms::where('name', $request->room)->first();
            if (! $room) {
                $room = $s->name;
            } else {
                $room = $room->name;
            }
        }
        $this->game = Jackpot::where('room', $room)->orderBy('game_id', 'desc')->first();
        view()->share('bets', $this->getGameBets($room));
        view()->share('game', $this->getGame($room));
        view()->share('time', $this->getTime($room));
        view()->share('chances', $this->getChancesOfGame($room, $this->game->game_id));
    }

    public function getRooms()
    {
        $room = Rooms::where('status', 0)->get();

        return $room;
    }

    public static function getPriceJackpot()
    {
        $jackpot_r1 = Jackpot::select('price')->where('room', 'small')->orderBy('id', 'desc')->first();
        $jackpot_r2 = Jackpot::select('price')->where('room', 'classic')->orderBy('id', 'desc')->first();
        $jackpot_r3 = Jackpot::select('price')->where('room', 'major')->orderBy('id', 'desc')->first();
        if (! is_null($jackpot_r1) && ! is_null($jackpot_r2) && ! is_null($jackpot_r3)) {
            $jackpot = $jackpot_r1->price + $jackpot_r2->price + $jackpot_r3->price;
        } else {
            $jackpot = 0;
        }

        return $jackpot;
    }

    public function index(Request $request)
    {
        $rooms = Rooms::where('status', 0)->orderBy('id', 'desc')->get();
        foreach ($rooms as $s) {
            $room = Rooms::where('name', $request->room)->first();
            if (! $room) {
                $room = $s->name;
            } else {
                $room = $room->name;
            }
        }

        if ($request->pjax() && $request->ajax()) {
            return view('pages.games.jackpot', compact('room', 'rooms'));
        }

        return view('layout')->with('page', view('pages.games.jackpot', compact('room', 'rooms')));
    }

    public function newGame(Request $request)
    {
        $room = $request->room;
        if (\Cache::get('act.newgame.'.$room) > time()) {
            return false;
        }
        \Cache::forever('act.newgame.'.$room, time() + 6);

        $hash = bin2hex(random_bytes(16));

        $game = Jackpot::create([
            'room' => $room,
            'game_id' => $this->game->game_id + 1,
            'hash' => $hash,
        ]);

        $countGame = Jackpot::where(['room' => $room, 'status' => 3])->count();
        if ($countGame > 20) {
            Jackpot::where(['room' => $room, 'status' => 3])->orderBy('id', 'asc')->limit(1)->delete();
        }

        Jackpot::where('updated_at', '>=', Carbon::today()->addDays(2))->delete();
        JackpotBets::where('created_at', '>=', Carbon::today()->addDays(2))->delete();

        return response()->json([
            'room'   	 => $room,
            'game'     	 => [
                'id'   	 => $game->game_id,
                'price'	 => $game->price,
                'hash'	 => $hash,
            ],
            'allBank' => round($this->getPriceJackpot(), 2),
            'time'  	 => $this->getTime($room),
        ]);
    }

    public function newBet(Request $request)
    {
        if ($this->user->ban) {
            return;
        }
        $rooms = Rooms::where('status', 0)->orderBy('id', 'desc')->get();
        foreach ($rooms as $s) {
            $room = Rooms::where('name', $request->room)->first();
            if (! $room) {
                $room = $s->name;
            } else {
                $room = $room->name;
            }
        }

        if (\Cache::has('bet.user.'.$this->user->id)) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы слишком часто делаете ставку!',
                'type'  => 'error',
            ]));

            return;
        }
        \Cache::put('bet.user.'.$this->user->id, '', 0.03);

        $option = Rooms::where('name', $room)->first();

        $sum = preg_replace('/[^0-9.]/', '', $request->sum);
        $moneytick = preg_replace('/[^0-9.]/', '', $sum);

        $userbets = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->where('user_id', $this->user->id)->count();
        $usersum = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->where('user_id', $this->user->id)->sum('sum');
        $bets = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->get();

        if ($userbets >= $option->bets) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы не можете сделать больше '.$option->bets.' ставок за одну игру!',
                'type'  => 'error',
            ]));

            return;
        }
        if ($usersum >= $option->max) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы не можете сделать ставку больше '.$option->max.' монет за одну игру!',
                'type'  => 'error',
            ]));

            return;
        }
        if ($this->game->status == Jackpot::STATUS_PRE_FINISH || $this->game->status == Jackpot::STATUS_FINISHED) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Ставки в эту игру закрыты!',
                'type'  => 'error',
            ]));

            return;
        }
        if (! $moneytick) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы не ввели сумму ставки!',
                'type'  => 'error',
            ]));

            return;
        }
        if ($moneytick > $this->user->balance) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Не хватает монет для ставки!',
                'type'  => 'error',
            ]));

            return;
        }
        if ($moneytick < $option->min) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Минимальная ставка - '.$option->min.' монет.',
                'type'  => 'error',
            ]));

            return;
        }
        if ($moneytick > $option->max) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Максимальная ставка - '.$option->max.' монет.',
                'type'  => 'error',
            ]));

            return;
        }

        $getcolor = $this->getColor();
        foreach ($bets as $check) {
            if ($check->color == $getcolor) {
                $getcolor = $this->getColor();
            }
            if ($check->user_id == $this->user->id) {
                $getcolor = $check->color;
            }
        }

        $ticketFrom = 1;
        $lastBet = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->orderBy('id', 'desc')->first();
        if ($lastBet) {
            $ticketFrom = $lastBet->to;
        }
        $ticketTo = $ticketFrom + floor($moneytick * 10);

        $bet = new JackpotBets();
        $bet->game_id = $this->game->game_id;
        $bet->room = $room;
        $bet->user()->associate($this->user);
        $bet->sum = $moneytick;
        $bet->from = $ticketFrom;
        $bet->to = $ticketTo;
        $bet->color = $getcolor;
        $bet->save();

        $this->user->balance -= $moneytick;
        $this->user->save();

        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => round($this->user->balance, 2),
        ]));

        $infos = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->orderBy('id', 'desc')->get();

        $this->game->price = $infos->sum('sum');
        $this->game->save();

        $info = [];

        foreach ($infos as $bet) {
            $user = $this->findUser($bet->user_id);
            $info[] = [
                'user_id'   => $bet->user_id,
                'avatar'    => $user->avatar,
                'username'  => $user->username,
                'sum'       => $bet->sum,
                'color'     => $bet->color,
                'from'      => $bet->from,
                'to'        => $bet->to,
                'chance'    => $this->getChanceByUser($room, $user->id, $this->game->game_id),
            ];
        }

        $this->redis->publish('jackpot.newBet', json_encode([
            'room' 	 	 => $room,
            'bets'       => $info,
            'game'     	 => [
                'price'	 => round($this->game->price, 2),
            ],
            'allBank' => round($this->getPriceJackpot(), 2),
            'chances'  	 => $this->getChancesOfGame($room, $this->game->game_id),
        ]));

        $this->redis->publish('message', json_encode([
            'user'  => $this->user->id,
            'msg'   => 'Ваша ставка одобрена!',
            'type'  => 'success',
        ]));

        if (count($this->getChancesOfGame($room, $this->game->game_id)) >= 2) {
            if ($this->game->status < Jackpot::STATUS_PLAYING) {
                $this->game->status = Jackpot::STATUS_PLAYING;
                $this->game->save();
                $this->StartTimer($room);
            }
        }

        return ['success' => true];
    }

    public function newBetFake()
    {
        $room_rand = Rooms::where('status', 0)->inRandomOrder()->first();
        $room = $room_rand->name;
        $game = Jackpot::where('room', $room)->orderBy('id', 'desc')->first();
        $option = Rooms::where('name', $room)->first();
        $user = $this->getUser();
        $lastBet = JackpotBets::where(['is_fake' => 0, 'room' => $room, 'game_id' => $game->game_id])->orderBy('sum', 'desc')->first();

        $countBet = JackpotBets::where(['room' => $room, 'user_id' => $user->id, 'game_id' => $game->game_id])->count();
        $usersum = JackpotBets::where(['room' => $room, 'game_id' => $game->game_id, 'user_id' => $user->id])->sum('sum');

        if ($game->status > Jackpot::STATUS_PLAYING) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[ROOM #'.$room.'] Ставки в эту игру закрыты!',
        ];
        }
        if ($countBet == $option->bets) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[ROOM #'.$room.'] Этот пользователь уже задействован!',
        ];
        }
        if ($usersum == $option->max) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg'   => '[ROOM #'.$room.'] Этот пользователь уже сделал максимальную ставку!',
        ];
        }
        if (is_null($lastBet)) {
            $min = $option->min + mt_rand(1, 10);
            $max = $option->min + mt_rand(11, 99);
            $moneytick = mt_rand($min, $max);
        } else {
            if ($lastBet->sum == $option->max) {
                $min = $lastBet->sum - mt_rand(5, 10);
                $max = $lastBet->sum - mt_rand(1, 4);
            } else {
                $min = $option->min + mt_rand(1, 10);
                $max = $option->min + mt_rand(11, 99);
            }

            $o = [5, 10];
            $ar_o = array_rand($o, 2);
            $moneytick = $this->roundToTheNearestAnything(mt_rand($min, $max), $o[$ar_o[0]]);
        }
        $bets = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->get();

        $getcolor = $this->getColor();
        foreach ($bets as $check) {
            if ($check->color == $getcolor) {
                $getcolor = $this->getColor();
            }
            if ($check->user_id == $user->id) {
                $getcolor = $check->color;
            }
        }

        $ticketFrom = 1;
        $lastBet = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->orderBy('id', 'desc')->first();
        if ($lastBet) {
            $ticketFrom = $lastBet->to + 1;
        }
        $ticketTo = $ticketFrom + floor($moneytick * 10);

        $bet = new JackpotBets();
        $bet->room = $room;
        $bet->game_id = $game->game_id;
        $bet->user()->associate($user);
        $bet->sum = $moneytick;
        $bet->from = $ticketFrom;
        $bet->to = $ticketTo;
        $bet->color = $getcolor;
        $bet->is_fake = 1;
        $bet->save();

        $infos = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->orderBy('id', 'desc')->get();

        $game->price = $infos->sum('sum');
        $game->save();

        $info = [];

        foreach ($infos as $bet) {
            $user = $this->findUser($bet->user_id);
            $info[] = [
                'user_id'   => $bet->user_id,
                'avatar'    => $user->avatar,
                'username'  => $user->username,
                'sum'       => $bet->sum,
                'color'     => $bet->color,
                'from'      => $bet->from,
                'to'        => $bet->to,
                'chance'    => $this->getChanceByUser($room, $user->id, $game->game_id),
            ];
        }

        $this->redis->publish('jackpot.newBet', json_encode([
            'room' 	 	 => $room,
            'bets'       => $info,
            'game'     	 => [
                'price'	 => $game->price,
            ],
            'chances'  	 => $this->getChancesOfGame($room, $game->game_id),
            'allBank' => round($this->getPriceJackpot(), 2),
        ]));

        if (count($this->getChancesOfGame($room, $game->game_id)) >= 2) {
            if ($game->status < Jackpot::STATUS_PLAYING) {
                $game->status = Jackpot::STATUS_PLAYING;
                $game->save();
                $this->StartTimer($room);
            }
        }

        return ['success' => true, 'fake' => $this->settings->fake, 'msg' => '[ROOM #'.$room.'] Ставка сделана!'];
    }

    public function roundToTheNearestAnything($value, $roundTo)
    {
        $mod = $value % $roundTo;

        return $value + ($mod < ($roundTo / 2) ? -$mod : $roundTo - $mod);
    }

    public function adminBet(Request $request)
    {
        $user = User::where('user_id', $request->user)->first();
        $room = $request->room;
        $game = Jackpot::where('room', $room)->orderBy('id', 'desc')->first();
        $moneytick = preg_replace('/[^0-9.]/', '', $request->sum);

        $option = Rooms::where('name', $room)->first();

        $countBet = JackpotBets::where(['user_id' => $user->id, 'game_id' => $game->game_id])->count();
        $usersum = JackpotBets::where(['game_id' => $game->game_id, 'user_id' => $user->id])->sum('sum');

        if (! $moneytick) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Вы не ввели сумму ставки',
        ];
        }
        if ($moneytick > $option->max) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Ставка больше максимальной',
        ];
        }
        if ($moneytick < $option->min) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Ставка меньше минимальной',
        ];
        }
        if ($game->status > Jackpot::STATUS_PLAYING) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Ставки в эту игру закрыты!',
        ];
        }
        if ($countBet == $option->bets) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Этот пользователь уже задействован!',
        ];
        }
        if ($usersum == $option->max) {
            return [
            'success' => false,
            'type' => 'error',
            'msg'   => 'Этот пользователь уже сделал максимальную ставку!',
        ];
        }

        $bets = JackpotBets::where('game_id', $game->game_id)->get();

        $getcolor = $this->getColor();
        foreach ($bets as $check) {
            if ($check->color == $getcolor) {
                $getcolor = $this->getColor();
            }
            if ($check->user_id == $user->id) {
                $getcolor = $check->color;
            }
        }

        $ticketFrom = 1;
        $lastBet = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->orderBy('id', 'desc')->first();
        if ($lastBet) {
            $ticketFrom = $lastBet->to + 1;
        }
        $ticketTo = $ticketFrom + floor($moneytick * 10);

        $bet = new JackpotBets();
        $bet->room = $room;
        $bet->game_id = $game->game_id;
        $bet->user()->associate($user);
        $bet->sum = $moneytick;
        $bet->from = $ticketFrom;
        $bet->to = $ticketTo;
        $bet->color = $getcolor;
        $bet->is_fake = 1;
        $bet->save();

        $infos = JackpotBets::where('game_id', $game->game_id)->orderBy('id', 'desc')->get();

        $game->price = $infos->sum('sum');
        $game->save();

        $info = [];

        foreach ($infos as $bet) {
            $user = $this->findUser($bet->user_id);
            $info[] = [
                'user_id'   => $bet->user_id,
                'avatar'    => $user->avatar,
                'username'  => $user->username,
                'sum'       => round($bet->sum, 2),
                'color'     => $bet->color,
                'from'      => $bet->from,
                'to'        => $bet->to,
                'chance'    => $this->getChanceByUser($room, $user->id, $game->game_id),
            ];
        }

        $this->redis->publish('jackpot.newBet', json_encode([
            'room' 	 	 => $room,
            'bets'       => $info,
            'game'     	 => [
                'price'	 => round($game->price, 2),
            ],
            'chances'  	 => $this->getChancesOfGame($room, $game->game_id),
            'allBank' => round($this->getPriceJackpot(), 2),
        ]));

        if (count($this->getChancesOfGame($room, $game->game_id)) >= 2) {
            if ($game->status < Jackpot::STATUS_PLAYING) {
                $game->status = Jackpot::STATUS_PLAYING;
                $game->save();
                $this->StartTimer($room);
            }
        }

        return ['success' => true, 'type' => 'success', 'msg' => 'Ставка сделана!'];
    }

    private function getUser()
    {
        $user = User::where('fake', 1)->inRandomOrder()->first();

        return $user;
    }

    public function getSlider(Request $request)
    {
        $room = $request->room;

        // Поиск победителя
        $tickets = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->orderBy('id', 'desc')->first();
        $tickets = $tickets->to;
        $winTicket = mt_rand(1, $tickets);

        $bets = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->orderBy('id', 'desc')->get();
        foreach ($bets as $bet) {
            if (($bet->from <= $winTicket) && ($bet->to >= $winTicket)) {
                $winBet = $bet;
            }
        }
        if (is_null($winBet)) {
            return ['success' => false];
        }

        $winner = User::where('id', $winBet->user_id)->first();
        if (is_null($winner)) {
            return ['success' => false];
        }

        $users = $this->getChancesOfGame($room, $this->game->game_id);

        if ($this->game->winner_id) {
            // Подкрутка.
            $winner2 = User::where('id', $this->game->winner_id)->first();
            // Поиск билетов юзера
            $bets = JackpotBets::where('game_id', $this->game->game_id)->where('user_id', $winner2->id)->get();
            $bet = $bets[mt_rand(0, count($bets) - 1)];
            $winTicket2 = mt_rand($bet->from, $bet->to);

            foreach ($bets as $bet) {
                if (($bet->from <= $winTicket2) && ($bet->to >= $winTicket2)) {
                    $winBet = $bet;
                }
            }
            if (is_null($winBet)) {
                return ['success' => false];
            }

            $winTicket = $winTicket2;
            $winner = $winner2;
        }

        $members = [];
        foreach ($users as $user) {
            for ($i = 0; $i < ceil($user['chance']); $i++) {
                $members[] = [
                    'avatar' => $user['avatar'],
                    'color' => $user['color'],
                ];
            }
        }

        shuffle($members);

        $win = [
            'avatar' => $winner->avatar,
            'color'  => $winBet->color,
        ];

        $members[80] = $win;

        $this->game->winner_id = $winner->id;
        $this->game->winner_chance = $this->getChanceByUser($room, $winner->id, $this->game->game_id);
        $this->game->winner_ticket = $winTicket;
        $this->game->save();

        $this->game->winner_sum = $this->sendMoney($room, $this->game->game_id);
        $this->game->save();

        return response()->json([
            'room'       	=> $room,
            'members'      	=> $members,
            'hash'      	=> $this->game->hash,
            'winner'        => [
                'username'  => $winner->username,
                'avatar'    => $winner->avatar,
                'sum'    	=> round($this->game->winner_sum, 2),
                'chance'    => $this->getChanceByUser($room, $winner->id, $this->game->game_id),
                'ticket'    => $this->game->winner_ticket,
            ],
            'ml'            => mt_rand(4187, 4240),
            'game'          => [
                'price'     => round($this->game->price, 2),
            ],
        ]);
    }

    public function sendMoney($room, $game_id)
    {
        $game = Jackpot::where('room', $room)->where('status', 2)->where('game_id', $game_id)->first();
        $all_bets = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->sum('sum');
        $w_bet = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->where('user_id', $game->winner_id)->sum('sum');
        $sum = round($w_bet + (($all_bets - $w_bet) - ($all_bets - $w_bet) / 100 * 20), 2);
        $user = User::where(['id' => $game->winner_id, 'fake' => 0])->first();
        if (! is_null($user)) {
            $user->balance += $sum;
            $user->save();

            if ($user->referred_by) {
                $ref = User::where('affiliate_id', $user->referred_by)->first();
                $ref_perc = $this->getRefer($ref->affiliate_id);
                $ref->ref_money += $sum / 100 * $ref_perc;
                $ref->ref_money_history += $sum / 100 * $ref_perc;
                $ref->save();
            }

            $this->redis->publish('updateBalanceAfter', json_encode([
                'id'    => $user->id,
                'balance' => $user->balance,
            ]));
        } else {
            $sum = $game->price;
        }

        $game->status = Jackpot::STATUS_FINISHED;
        $game->save();

        return $sum;
    }

    private function getRefer($id)
    {
        $ref_count = User::where('referred_by', $id)->count();
        if ($ref_count < 10) {
            $ref_perc = 0.5;
        } elseif ($ref_count >= 10 && $ref_count < 100) {
            $ref_perc = 0.7;
        } elseif ($ref_count >= 100 && $ref_count < 500) {
            $ref_perc = 1;
        } elseif ($ref_count > 500) {
            $ref_perc = 1.5;
        }

        return $ref_perc;
    }

    public function getStatus(Request $request)
    {
        $room = $request->room;
        $option = Rooms::where('name', $room)->first();
        $game = Jackpot::where('room', $room)->orderBy('game_id', 'desc')->first();
        $min = floor($option->time / 60);
        $sec = floor($option->time - ($min * 60));

        if (count($this->getChancesOfGame($room, $game->game_id)) >= 2) {
            if ($game->status < Jackpot::STATUS_PLAYING) {
                $game->status = Jackpot::STATUS_PLAYING;
                $game->save();
                $this->StartTimer($room);
            }
        }

        return response()->json([
            'room'      => $room,
            'id'        => $game->game_id,
            'min'       => $min,
            'sec'       => $sec,
            'time'      => $option->time,
            'status'    => $game->status,
        ]);
    }

    public function setStatus(Request $request)
    {
        $room = $request->room;
        $status = $request->status;

        Jackpot::where('room', $room)->where('game_id', $this->game->game_id)->update([
            'status' => $status,
        ]);

        return [
            'msg'       => 'Статус изменен на '.$status.' в комнате #'.$room.'!',
            'type'      => 'success',
        ];
    }

    private function getColor()
    {
        $color = str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

        return $color;
    }

    private function getGameBets($room)
    {
        if (is_null($this->game)) {
            $this->game = Jackpot::create([
            'room' => $room,
            'game_id' => 1,
            'hash' => bin2hex(random_bytes(16)),
        ]);
        }
        $bets = JackpotBets::where('room', $room)->where('game_id', $this->game->game_id)->orderBy('id', 'desc')->get();

        foreach ($bets as $key => $bet) {
            $user = User::where('id', $bet->user_id)->first();
            $bets[$key]->username = $user->username;
            $bets[$key]->avatar = $user->avatar;
            $bets[$key]->chance = $this->getChanceByUser($room, $user->id, $this->game->game_id);
        }

        return $bets;
    }

    private function getGame($room)
    {
        $game = Jackpot::where('room', $room)->orderBy('game_id', 'desc')->first();

        return $game;
    }

    public function getTime($room)
    {
        $option = Rooms::where('name', $room)->first();
        $min = floor($option->time / 60);
        $sec = floor($option->time - ($min * 60));

        if ($min == 0) {
            $min = '00';
        }
        if ($sec == 0) {
            $sec = '00';
        }
        if (($min > 0) && ($min < 10)) {
            $min = '0'.$min;
        }
        if (($sec > 0) && ($sec < 10)) {
            $sec = '0'.$sec;
        }

        return [$min, $sec, $option->time];
    }

    private function StartTimer($room)
    {
        $option = Rooms::where('name', $room)->first();
        $min = floor($option->time / 60);
        $sec = floor($option->time - ($min * 60));
        $this->redis->publish('jackpot.timer', json_encode([
            'room' => $room,
            'min'  => $min,
            'sec'  => $sec,
            'time' => $option->time,
        ]));
    }

    private function findUser($id)
    {
        $user = User::where('id', $id)->first();

        return $user;
    }

    private function getChanceByUser($room, $user, $game)
    {
        $chance = 0;
        if (! is_null($user)) {
            $value = JackpotBets::where('room', $room)->where('game_id', $game)->where('user_id', $user)->sum('sum');
            $game = Jackpot::where(['room' => $room, 'game_id' => $game])->first();
            $chance = round(($value / $game->price) * 100);
        }

        return $chance;
    }

    public static function getChancesOfGame($room, $gameid)
    {
        $game = Jackpot::where('room', $room)->where('game_id', $gameid)->first();
        $users = [];
        if (! $game) {
            return;
        }
        $bets = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->orderBy('game_id', 'desc')->get();
        foreach ($bets as $bet) {
            $find = 0;
            foreach ($users as $user) {
                if ($user == $bet->user_id) {
                    $find++;
                }
            }
            if ($find == 0) {
                $users[] = $bet->user_id;
            }
        }

        // get chances
        $chances = [];
        foreach ($users as $user) {
            $user = User::where('id', $user)->first();
            $value = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->where('user_id', $user->id)->sum('sum');
            $colors = JackpotBets::where('room', $room)->where('game_id', $game->game_id)->where('user_id', $user->id)->get();
            $chance = round(($value / $game->price) * 100);
            foreach ($colors as $cl) {
                $color = $cl->color;
                $betid = $cl->id;
            }
            $chances[] = [
                'room'      => $room,
                'id'        => $user->id,
                'username'  => $user->username,
                'avatar'    => $user->avatar,
                'sum'    	=> $value,
                'color'     => $color,
                'chance'    => round($chance, 2),
            ];
        }

        usort($chances, function ($a, $b) {
            return $b['chance'] - $a['chance'];
        });

        return $chances;
    }

    public function gameHistory($room, $id)
    {
        $history = Jackpot::where('room', $room)->where('game_id', $id)->first();
        if (is_null($history)) {
            return redirect()->route('jackpotHistory');
        }
        $historyBets = JackpotBets::where('room', $room)->where('game_id', $id)->get();
        $historyChance = $this->getChancesOfGame($room, $id);

        foreach ($historyBets as $key => $bet) {
            $user = User::where('id', $bet->user_id)->first();
            $value = JackpotBets::where('room', $room)->where('game_id', $id)->where('user_id', $user->id)->sum('sum');
            $chance = round(($value / $history->price) * 100);
            $historyBets[$key]->username = $user->username;
            $historyBets[$key]->avatar = $user->avatar;
            $historyBets[$key]->chance = $chance;
        }

        $users = $this->getChancesOfGame($room, $id);
        $winner = User::where('id', $history->winner_id)->first();
        if (is_null($winner)) {
            return ['success' => false];
        }

        $winBet = JackpotBets::where('room', $room)->where('game_id', $id)->where('user_id', $winner->id)->first();

        $members = [];
        foreach ($users as $user) {
            for ($i = 0; $i < ceil($user['chance']); $i++) {
                $members[] = [
                    'avatar' => $user['avatar'],
                    'color' => $user['color'],
                ];
            }
        }

        shuffle($members);

        $win = [
            'avatar' => $winner->avatar,
            'color'  => $winBet->color,
        ];

        $members[80] = $win;

        return view('pages.gameHistory', compact('history', 'historyBets', 'historyChance', 'members', 'winner'));
    }

    public function gotThis(Request $request)
    {
        $room = $request->room;
        $userid = $request->user_id;
        $user = User::where('id', $userid)->first();
        $bets = JackpotBets::where(['room' => $room, 'game_id' => $this->game->game_id, 'user_id' => $user->id])->first();

        if (! $this->game->id) {
            return [
            'msg'       => 'Не удалось получить номер игры!',
            'type'      => 'error',
        ];
        }

        if (! $userid) {
            return [
            'msg'       => 'Не удалось получить ид игрока!',
            'type'      => 'error',
        ];
        }

        if (! $room) {
            return [
            'msg'       => 'Не удалось получить комнату!',
            'type'      => 'error',
        ];
        }

        if (is_null($bets)) {
            return [
            'msg'       => 'Данный игрок не делал ставку!',
            'type'      => 'error',
        ];
        }

        Jackpot::where('room', $room)->where('game_id', $this->game->game_id)->update([
            'winner_id' => $userid,
        ]);

        return [
            'msg'       => 'Вы подкрутили игроку '.$user->username.' в режиме Jackpot!',
            'type'      => 'success',
        ];
    }
}
