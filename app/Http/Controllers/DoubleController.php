<?php

namespace App\Http\Controllers;

use App\Double;
use App\DoubleBets;
use App\Settings;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DoubleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->game = Double::orderBy('id', 'desc')->first();
        if (is_null($this->game)) {
            $this->game = Double::create([
            'hash' => bin2hex(random_bytes(16)),
        ]);
        }
        view()->share('game', $this->game);
    }

    public function index(Request $request)
    {
        /*if(!Auth::user() || !$this->user->is_admin) return redirect()->route('dice')->with('success', 'Тех. работы!');*/
        $rotate = $this->settings->double_rotate2;
        $time = $this->settings->double_rotate_start - time() + $this->settings->double_timer; // заменить 7 на время таймера в секунда INTEGER
        if ($this->game->status == 2 && $time > 0) {
            $rotate += ($this->settings->double_rotate - $this->settings->double_rotate2) * (1 - ($time / 7));
        }
        $rotate2 = $this->settings->double_rotate;
        $bets = $this->getBets();
        $prices = $this->getPrices();
        $history = $this->getHistory();

        if ($request->pjax() && $request->ajax()) {
            return view('pages.games.double', compact('bets', 'rotate', 'rotate2', 'time', 'prices', 'history'));
        }

        return view('layout')->with('page', view('pages.games.double', compact('bets', 'rotate', 'rotate2', 'time', 'prices', 'history')));
    }

    public static function getPriceDouble()
    {
        $double = Double::select('price')->orderBy('id', 'desc')->first();
        if (! is_null($double)) {
            $double = $double->price;
        } else {
            $double = 0;
        }

        return $double;
    }

    private function getPrices()
    {
        $query = DoubleBets::where('round_id', $this->game->id)
                    ->select(DB::raw('SUM(price) as value'), 'type')
                    ->groupBy('type')
                    ->get();

        $list = [];
        foreach ($query as $l) {
            $list[$l->type] = $l->value;
        }

        return $list;
    }

    private function getRealPrices()
    {
        $query = DoubleBets::where(['round_id' => $this->game->id, 'is_fake' => 0])
                    ->select(DB::raw('SUM(price) as value'), 'type')
                    ->groupBy('type')
                    ->get();

        $list = [];
        foreach ($query as $l) {
            $list[$l->type] = $l->value;
        }

        return $list;
    }

    public function getHistory()
    {
        $query = Double::where('status', 3)->select('winner_num', 'winner_color', 'id', 'hash')->orderBy('id', 'desc')->limit(60)->get();

        return $query;
    }

    public function addBet(Request $r)
    {
        /*if($this->user->id != 1) return [
            'success' => false,
            'msg' => 'На сайте ведутся технические работы!'
        ];*/
        if (\Cache::has('bet.user.'.$this->user->id)) {
            $this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы слишком часто делаете ставку!',
                'icon'  => 'error',
            ]));

            return;
        }
        \Cache::put('bet.user.'.$this->user->id, '', 0.10);
        $value = $r->get('bet');

        if ($value < $this->settings->double_min_bet) {
            return [
            'success' => false,
            'msg' => 'Минимальная сумма ставки - '.$this->settings->double_min_bet,
        ];
        }

        if ($this->settings->double_max_bet > 0 && $value > $this->settings->double_max_bet) {
            return [
            'success' => false,
            'msg' => 'Максимальная сумма ставки - '.$this->settings->double_max_bet,
        ];
        }

        if ($this->game->status > 1) {
            return [
            'success' => false,
            'msg' => 'Ставки в эту игру закрыты!',
        ];
        }

        // проверка баланса
        if ($this->user->balance < $value) {
            return [
            'success' => false,
            'msg' => 'Недостаточно баланса!',
        ];
        }

        // получение ставок пользователя
        $bets = DoubleBets::where([
            'user_id' => $this->user->id,
            'round_id' => $this->game->id,
        ])->select('type as color')->groupBy('color')->get();

        $ban = 'none';
        foreach ($bets as $b) {
            if ($b->color != 'green') {
                $ban = $b->color;
            }
        }
        if ($ban != 'none') {
            $ban = ($ban == 'red') ? 'black' : 'red';
        }

        if ($r->get('type') == $ban) {
            return [
            'success' => false,
            'msg' => 'Вы не можете сделать ставку на этот цвет!',
            'bets' => $bets,
        ];
        }

        // Минусуем баланс
        $this->user->balance -= $value;
        $this->user->save();

        $this->game->price += $value;
        $this->game->save();

        $bet = DoubleBets::create([
            'user_id' => $this->user->id,
            'round_id' => $this->game->id,
            'price' => round($value, 2),
            'type' => $r->get('type'),
        ]);

        $this->redis->publish('updateBalance', json_encode([
            'id' => $this->user->id,
            'balance' => round($this->user->balance, 2),
        ]));

        $this->emit([
            'type' => 'admin',
            'prices' => $this->getRealPrices(),
        ]);

        $this->emit([
            'type' => 'bets',
            'bets' => $this->getBets(),
            'prices' => $this->getPrices(),
            'allBank' => $this->getPriceDouble(),
        ]);

        $this->startTimer();

        return [
            'success' => true,
            'msg' => 'Ваша ставка вошла в игру!',
        ];
    }

    public function addBetFake()
    {
        if ($this->game->status > 1) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Ставки в эту игру закрыты!',
        ];
        }

        $user = $this->getUser();

        if (! $user) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Не удалось получить пользователя!',
        ];
        }
        $countBet = DoubleBets::where([
            'user_id' => $user->id,
            'round_id' => $this->game->id,
        ])->count();

        if ($countBet >= 2) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Этот пользователь уде задействован!',
        ];
        }

        $col = ['red', 'black', 'green'];
        $col_rand = array_rand($col, 1);
        $o = [5, 10, 15];
        $ar_o = array_rand($o, 2);
        $sum = $this->roundToTheNearestAnything(mt_rand($this->settings->dice_fake_min, $this->settings->dice_fake_max), $o[$ar_o[0]]);
        if ($col[$col_rand] == 'green') {
            $value = $this->roundToTheNearestAnything(floor(mt_rand($this->settings->double_fake_min, $this->settings->double_fake_max) / mt_rand(2, 4)), $o[$ar_o[0]]);
        } else {
            $value = $this->roundToTheNearestAnything(mt_rand($this->settings->double_fake_min, $this->settings->double_fake_max), $o[$ar_o[0]]);
        }
        // получение ставок пользователя
        $bets = DoubleBets::where([
            'user_id' => $user->id,
            'round_id' => $this->game->id,
        ])->select('type as color')->groupBy('color')->get();

        $ban = 'none';
        foreach ($bets as $b) {
            if ($b->color != 'green') {
                $ban = $b->color;
            }
        }
        if ($ban != 'none') {
            $ban = ($ban == 'red') ? 'black' : 'red';
        }

        if ($col[$col_rand] == $ban) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Вы не можете сделать ставку на этот цвет!',
            'bets' => $bets,
        ];
        }

        if ($value < $this->settings->double_min_bet) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Минимальная сумма ставки - '.$this->settings->double_min_bet,
        ];
        }

        if ($this->settings->double_max_bet > 0 && $value > $this->settings->double_max_bet) {
            return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Максимальная сумма ставки - '.$this->settings->double_max_bet,
        ];
        }

        $this->game->price += $value;
        $this->game->save();

        $bet = DoubleBets::create([
            'user_id' => $user->id,
            'round_id' => $this->game->id,
            'price' => $value,
            'type' => $col[$col_rand],
            'is_fake' => 1,
        ]);

        $this->emit([
            'type' => 'bets',
            'bets' => $this->getBets(),
            'prices' => $this->getPrices(),
            'allBank' => $this->getPriceDouble(),
        ]);

        $this->startTimer();

        return [
            'success' => true,
            'fake' => $this->settings->fake,
            'msg' => '[Double] Фейк ставка сделана!',
        ];
    }

    public function roundToTheNearestAnything($value, $roundTo)
    {
        $mod = $value % $roundTo;

        return $value + ($mod < ($roundTo / 2) ? -$mod : $roundTo - $mod);
    }

    public function adminBet(Request $r)
    {
        if ($this->game->status > 1) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Ставки в эту игру закрыты!',
        ];
        }

        $user = User::where('user_id', $r->get('user'))->first();
        $value = preg_replace('/[^0-9.]/', '', floor($r->get('sum')));
        $color = $r->get('color');

        $countBet = DoubleBets::where([
            'user_id' => $user->id,
            'round_id' => $this->game->id,
        ])->count();

        if ($countBet >= 2) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Этот пользователь уде задействован!',
        ];
        }

        // получение ставок пользователя
        $bets = DoubleBets::where([
            'user_id' => $user->id,
            'round_id' => $this->game->id,
        ])->select('type as color')->groupBy('color')->get();

        $ban = 'none';
        foreach ($bets as $b) {
            if ($b->color != 'green') {
                $ban = $b->color;
            }
        }
        if ($ban != 'none') {
            $ban = ($ban == 'red') ? 'black' : 'red';
        }

        if ($color == $ban) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Вы не можете сделать ставку на этот цвет!',
            'bets' => $bets,
        ];
        }

        if ($value < $this->settings->double_min_bet) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Минимальная сумма ставки - '.$this->settings->double_min_bet,
        ];
        }

        if ($this->settings->double_max_bet > 0 && $value > $this->settings->double_max_bet) {
            return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Максимальная сумма ставки - '.$this->settings->double_max_bet,
        ];
        }

        $this->game->price += $value;
        $this->game->save();

        $bet = DoubleBets::create([
            'user_id' => $user->id,
            'round_id' => $this->game->id,
            'price' => $value,
            'type' => $color,
            'is_fake' => 1,
        ]);

        $this->emit([
            'type' => 'bets',
            'bets' => $this->getBets(),
            'prices' => $this->getPrices(),
        ]);

        $this->startTimer();

        return [
            'success' => true,
            'type' => 'success',
            'msg' => 'Ваша ставка вошла в игру!',
        ];
    }

    private function getUser()
    {
        $user = User::where('fake', 1)->inRandomOrder()->first();
        if ($user->time != 0) {
            $now = Carbon::now()->format('H');
            if ($now < 06) {
                $time = 4;
            }
            if ($now >= 06 && $now < 12) {
                $time = 1;
            }
            if ($now >= 12 && $now < 18) {
                $time = 2;
            }
            if ($now >= 18) {
                $time = 3;
            }
            $user = User::where(['fake' => 1, 'time' => $time])->inRandomOrder()->first();
        }

        return $user;
    }

    private function startTimer()
    {
        if ($this->game->status > 0) {
            return;
        }

        $this->game->status = 1;
        $this->game->save();

        return $this->emit([
            'type' => 'back_timer',
            'timer' => $this->settings->double_timer, // заменить на время таймера
        ]);
    }

    public function rotate($number)
    {
        $list = [
            [0,     'green',    0,     14],
            [337,   'red',    	1,     2],
            [288,   'red',      2,     2],
            [240,   'red',    	3,     2],
            [193,   'red',      4,     2],
            [145,   'red',    	5,     2],
            [97,    'red',      6,     2],
            [48,    'red',    	7,     2],
            [312,   'black',    8,     2],
            [264,   'black',    9,     2],
            [216,   'black',    10,    2],
            [169,   'black',    11,    2],
            [121,   'black',    12,    2],
            [72,    'black',    13,    2],
            [24,    'black',    14,    2],
        ];

        if ($this->game->winner_num !== null) {
            foreach ($list as $l) {
                if ($l[2] == $this->game->winner_num) {
                    return $l;
                }
            }
        }

        return $list[$number];
    }

    public function getSlider()
    {
        $ranked = 0;
        $winNumber = mt_rand(0, 14);

        $lastGreen = Double::where('status', 3)->where('winner_color', 'green')->first();
        //		if($winNumber == 0) {
        //			if(is_null($lastGreen) || !is_null($lastGreen) && ($this->game->id-$lastGreen->id) > mt_rand(8, 19)) {
        //				$winNumber = $winNumber;
        //			} else {
        //				$winNumber = mt_rand(0, 14);
        //			}
        //		}
        $checkUser = DoubleBets::where(['is_fake' => 0, 'round_id' => $this->game->id])->orderBy('id', 'desc')->count();
        if ($checkUser >= 1 && $this->game->ranked != 1) {
            $last = DoubleBets::where(['is_fake' => 0])->orderBy('id', 'desc')->limit(10)->get();

            $winGame = 0;
            $loseGame = 0;
            foreach ($last as $l) {
                if ($l->win == 1) {
                    $winGame += $l->win_sum;
                } else {
                    $loseGame += $l->price;
                }
            }

            if (! is_null($last) && $winGame * 2 > $loseGame) {
                $betsRed = DoubleBets::where(['round_id' => $this->game->id, 'type' => 'red', 'is_fake' => 0])->sum('price');
                $betsGreen = DoubleBets::where(['round_id' => $this->game->id, 'type' => 'green', 'is_fake' => 0])->sum('price');
                $betsBlack = DoubleBets::where(['round_id' => $this->game->id, 'type' => 'black', 'is_fake' => 0])->sum('price');

                $betsRed = $betsRed * 2;
                $betsGreen = $betsGreen * 14;
                $betsBlack = $betsBlack * 2;

                $min = min($betsRed, $betsGreen, $betsBlack);
                if ($min == $betsRed) {
                    $winNumber = mt_rand(1, 7);
                } elseif ($min == $betsBlack) {
                    $winNumber = mt_rand(8, 14);
                } elseif ($min == $betsBlack && $min == $betsRed) {
                    $winNumber = mt_rand(1, 14);
                } elseif ($min == $betsGreen) {
                    if (is_null($lastGreen) || ! is_null($lastGreen) && ($this->game->id - $lastGreen->id) > mt_rand(5, 12)) {
                        $winNumber = $winNumber;
                    } else {
                        $winNumber = mt_rand(0, 14);
                    }
                } else {
                    $winNumber = mt_rand(0, 14);
                }

                $ranked = 1;
            }
        }

        $box = $this->rotate($winNumber);
        $rotate = ((floor($this->settings->double_rotate / 360) * 360) + 360) + (360 * 5) + $box[0];

        $this->game->winner_num = $box[2];
        $this->game->winner_color = $box[1];
        $this->game->ranked = $ranked;
        $this->game->save();

        $this->settings->double_rotate = $rotate;
        $this->settings->double_rotate_start = time();
        $this->settings->save();

        $this->emit([
            'type' => 'slider',
            'slider' => [
                'rotate' => $this->settings->double_rotate,
                'color' => $this->game->winner_color,
                'num' => $this->game->winner_num,
                'time' => 7000,
                'timeToNewGame' => 3000,
            ],
        ]);

        return [
            'number' => $this->game->winner_num,
            'color' => $this->game->winner_color,
            'time' => 10000,
        ];
    }

    public function getBet(Request $r)
    {
        if ($r->get('type') == 'all') {
            return $this->user->balance;
        }
        $bet = DoubleBets::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
    }

    public function newGame()
    {
        $this->settings->double_rotate = $this->settings->double_rotate - (floor($this->settings->double_rotate / 360) * 360);
        $this->settings->double_rotate2 = $this->settings->double_rotate;
        $this->settings->save();

        $bets = DoubleBets::select(DB::raw('SUM(price) as price'), 'user_id')->where('round_id', $this->game->id)->where('type', $this->game->winner_color)->groupBy('user_id')->get();
        $multiplier = ($this->game->winner_color == 'green') ? 14 : 2;
        foreach ($bets as $u) {
            $user = User::where(['id' => $u->user_id, 'fake' => 0])->first();
            if (! is_null($user)) {
                $user->balance += $u->price * $multiplier;
                $user->save();

                if ($user->referred_by) {
                    $ref = User::where('affiliate_id', $user->referred_by)->first();
                    $ref_perc = $this->getRefer($ref->affiliate_id);
                    $ref->ref_money += $u->price * $multiplier / 100 * $ref_perc;
                    $ref->ref_money_history += $u->price * $multiplier / 100 * $ref_perc;
                    $ref->save();
                }

                $this->redis->publish('updateBalance', json_encode([
                    'id' => $user->id,
                    'balance' => $user->balance,
                ]));
            }
        }

        $betUsers = DoubleBets::where('round_id', $this->game->id)->where('type', $this->game->winner_color)->get();
        foreach ($betUsers as $b) {
            $b->win = 1;
            $b->win_sum += $b->price * $multiplier;
            $b->save();
        }

        $hash = bin2hex(random_bytes(16));

        $this->emit([
            'type' => 'newGame',
            'id' => $this->game->id,
            'hash' => $hash,
            'slider' => [
                'rotate' => $this->settings->double_rotate,
                'time' => $this->settings->double_timer,
            ],
            'history' => [
                'num' => $this->game->winner_num,
                'color' => $this->game->winner_color,
                'hash' => $this->game->hash,
            ],
            'allBank' => $this->getPriceDouble(),
        ]);

        $this->game = Double::create([
            'hash' => $hash,
        ]);

        Double::where('updated_at', '>=', Carbon::today()->addDays(2))->delete();
        DoubleBets::where('updated_at', '>=', Carbon::today()->addDays(2))->delete();

        return [
            'id' => $this->game->id,
        ];
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

    public function updateStatus(Request $r)
    {
        $this->game->status = $r->get('status');
        $this->game->save();

        return [
            'success' => true,
        ];
    }

    public function getGame()
    {
        return [
            'id' => $this->game->id,
            'status' => $this->game->status,
            'time' => $this->settings->double_timer, // fix
        ];
    }

    private function getBets()
    {
        $bets = DB::table('double_bets')
                    ->where('double_bets.round_id', $this->game->id)
                    ->select('double_bets.user_id', DB::raw('SUM(double_bets.price) as value'), 'users.username', 'users.avatar', 'double_bets.type')
                    ->join('users', 'users.id', '=', 'double_bets.user_id')
                    ->groupBy('double_bets.user_id', 'double_bets.type')
                    ->orderBy('value', 'desc')
                    ->get();

        return $bets;
    }

    private function emit($array)
    {
        return $this->redis->publish('roulette', json_encode($array));
    }

    public function gotThis(Request $r)
    {
        $color = $r->get('color');
        $number = '';

        if ($this->game->status > 1) {
            return [
            'msg'       => 'Игра началась, вы не можете подкрутить!',
            'type'      => 'error',
        ];
        }

        if (! $this->game->id) {
            return [
            'msg'       => 'Не удалось получить номер игры!',
            'type'      => 'error',
        ];
        }

        if (! $color) {
            return [
            'msg'       => 'Не удалось получить цвет!',
            'type'      => 'error',
        ];
        }

        $list = [
            [0,     'green',    0,     14],
            [337,   'red',    	1,     2],
            [288,   'red',      2,     2],
            [240,   'red',    	3,     2],
            [193,   'red',      4,     2],
            [145,   'red',    	5,     2],
            [97,    'red',      6,     2],
            [48,    'red',    	7,     2],
            [312,   'black',    8,     2],
            [264,   'black',    9,     2],
            [216,   'black',    10,    2],
            [169,   'black',    11,    2],
            [121,   'black',    12,    2],
            [72,    'black',    13,    2],
            [24,    'black',    14,    2],
        ];

        shuffle($list);

        if ($color == 'green') {
            $number = 0;
        }
        if ($color == 'red') {
            $number = mt_rand(1, 7);
        }
        if ($color == 'black') {
            $number = mt_rand(8, 14);
        }

        foreach ($list as $l) {
            if ($l[2] == $number) {
                $data = $l;
            }
        }

        Double::where('id', $this->game->id)->update([
            'winner_color'      => $data[1],
            'winner_num'     => $data[2],
            'ranked'     => 1,
        ]);

        if ($color == 'green') {
            $color = 'зеленый';
        }
        if ($color == 'red') {
            $color = 'красный';
        }
        if ($color == 'black') {
            $color = 'черный';
        }

        return [
            'msg'       => 'Вы подкрутили на '.$color.' цвет!',
            'type'      => 'success',
        ];
    }
}
