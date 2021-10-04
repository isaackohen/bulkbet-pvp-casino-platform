<?php namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Bonus;
use App\Battle;
use App\Jackpot;
use App\Pvp;
use App\Double;
use App\BonusLog;
use App\Payments;
use App\Promocode;
use App\SuccessPay;
use App\Withdraw;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct();
    }
	
	public function partnership(Request $request) {
		if ($request->pjax() && $request->ajax()) {
			return view('pages.partnership');
        }
		return view('layout')->with('page', view('pages.partnership'));
	}
	
	public function help(Request $request) {
		if ($request->pjax() && $request->ajax()) {
			return view('pages.help');
        }
		return view('layout')->with('page', view('pages.help'));
	}
	
	public function rules(Request $request) {
		if ($request->pjax() && $request->ajax()) {
			return view('pages.rules');
        }
		return view('layout')->with('page', view('pages.rules'));
	}
	
	public function payHistory(Request $request) {
		$pays = SuccessPay::where('user', $this->user->user_id)->where('status', '>=', 1)->orderBy('id','desc')->get();
        $withdraws = Withdraw::where('user_id', $this->user->id)->where('status', '>', 0)->orderBy('id','desc')->get();
		$active = Withdraw::where('user_id', $this->user->id)->where('status', 0)->orderBy('id','desc')->get();
		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.payHistory', compact('pays', 'withdraws', 'active'));
        }
		return view('layout')->with('page', view('pages.payHistory', compact('pays', 'withdraws', 'active')));
	}
	
	public function referral(Request $request) {
		$ref = User::where('referred_by', $this->user->affiliate_id)->count();
		$lvl = 0;
		$perc = 0;
		$width = 0;
		$min = 0;
		$max = 0;
		if($ref < 10) {
			$lvl = 1;
			$perc = 0.5;
			$max = 10;
			$width = ($ref/$max)*100;
		}
		if($ref >= 10 && $ref < 100) {
			$lvl = 2;
			$perc = 0.7;
			$max = 100;
			$width = ($ref/$max)*100;
		}
		if($ref >= 100 && $ref < 500) {
			$lvl = 3;
			$perc = 1;
			$max = 500;
			$width = ($ref/$max)*100;
		}
		if($ref > 500) {
			$lvl = 4;
			$perc = 1.5;
			$max = 500;
			$width = ($ref/$max)*100;
		}
		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.referral', compact('ref', 'perc', 'perc', 'width', 'lvl'));
        }
		return view('layout')->with('page', view('pages.referral', compact('ref', 'perc', 'perc', 'width', 'lvl')));
	}
	
	public function refActivate(Request $request) {
        $code = $request->get('code');
        
        if(is_null($code)) return [
            'success' => false,
            'msg' => 'Вы не ввели код!',
            'type' => 'error'
        ];
        
        $refcode = User::where('affiliate_id', $code)->first();
        $promocode = Promocode::where('code', $code)->first();
        
        if(!$refcode && !$promocode) return [
            'success' => false,
            'msg' => 'Такого кода не существует!',
            'type' => 'error'
        ];
        
        if($refcode) {
            $money = 5;
            if(strtolower($code) == strtolower($this->user->affiliate_id)) return [
                'success' => false,
                'msg' => 'Вы не можете активировать свой код!',
                'type' => 'error'
            ];

            if($this->user->referred_by) return [
                'success' => false,
                'msg' => 'Вы уже активировали код!',
                'type' => 'error'
            ];

            $this->user->balance += $money;
            $this->user->referred_by = $code;
            $this->user->save();
            
            SuccessPay::insert([
                'user' => $this->user->user_id,
                'price' => $money,
                'code' => $code,
                'status' => 2,
            ]);
        }
        if($promocode) {
            $money = $promocode->amount;
            $check = SuccessPay::where('user', $this->user->user_id)->where('code', $code)->first();
			
            if($check) return [
                'success' => false,
                'msg' => 'Вы уже активировали код!',
                'type' => 'error'
            ];
            
            if($promocode->limit == 1 && $promocode->count_use <= 0) return [
                'success' => false,
                'msg' => 'Код больше не действителен!',
                'type' => 'error'
            ];

            $this->user->balance += $money;
            $this->user->save();
            
            if($promocode->limit == 1 && $promocode->count_use > 0){
                $promocode->count_use -= 1;
                $promocode->save();
            }
            
            SuccessPay::insert([
                'user' => $this->user->user_id,
                'price' => $money,
                'code' => $code,
                'status' => 3,
            ]);
        }
        
        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));
        
        return [
            'success' => true,
            'msg' => 'Код активирован!',
            'type' => 'success'
        ];
    }
	
	public function getMoney() {
        $ref_money = floor($this->user->ref_money);
        if($ref_money < 0.99) return [
            'success' => false,
            'msg' => 'Вам нечего забирать!',
            'type' => 'error'
        ];
        $this->user->balance += $ref_money;
        $this->user->ref_money -= $ref_money;
        $this->user->save();
        
        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));
        
        return [
            'success' => true,
            'msg' => 'Вы забрали монеты!',
            'type' => 'success'
        ];
    }
	
	public function bonus(Request $request) {
		$bonus = Bonus::get();
		if($bonus == '[]') {
			$line = 0;
			$check = 0;
		} else {
			$bonusLog = BonusLog::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
			$line = [];
			foreach($bonus as $b) {
				for($i = 0; $i < 100; $i++) {
					$line[] = [
						'id' => $i,
						'sum' => $b->sum,
						'color' => $b->color
					];
				}
			}
			shuffle($line);
			array_splice($line, 99);
			$winner = Bonus::where('status', 1)->inRandomOrder()->first();

			$win = [
				'id' => 'win',
				'sum' => $winner->sum,
				'color'  => $winner->color
			];

			$line[80] = $win;
			$check = 0;
			if($bonusLog) {
				if($bonusLog->remaining) {
					$nowtime = time();
					$time = $bonusLog->remaining;
					$lasttime = $nowtime - $time;
					if($time >= $nowtime) {
						$check = 1;
					}
				}
				$bonusLog->status = 2;
				$bonusLog->save();
			}
		}
		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.bonus', compact('line', 'check'));
        }
		return view('layout')->with('page', view('pages.bonus', compact('line', 'check')));
	}
	
	public function getBonus(Request $request) {
		$validator = \Validator::make($request->all(), [
            'recapcha' => 'required|captcha',
        ]);
		
		if($validator->fails()) {
            return [
				'success' => false,
				'msg' => 'Вы не прошли проверку на я не робот!',
				'type' => 'error'
			];
        }
		$bonus = Bonus::get();
		$bonusLog = BonusLog::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
		$tg_ckeck = 1;
		
        if($tg_ckeck == null) {
            return [
                'success' => false,
                'msg' => 'Выдача бонусов временно не работает!',
                'type' => 'error'
            ];
        }
		
		if($bonusLog) {
			if($bonusLog->remaining) {
                $nowtime = time();
                $time = $bonusLog->remaining;
                $lasttime = $nowtime - $time;
                if($time >= $nowtime) {
                    return [
                        'success' => false,
                        'msg' => 'Следующий бонус Вы сможете получить: '.date("d.m.Y H:i:s", $time),
                        'type' => 'error'
                    ];
                }
            }
            $bonusLog->status = 2;
            $bonusLog->save();
		}
		
		$line = [];
        foreach($bonus as $b) {
            for($i = 0; $i < 100; $i++) {
				$line[] = [
					'id' => $i,
					'sum' => $b->sum,
					'color' => $b->color
				];
            }
        }
        shuffle($line);
        array_splice($line, 99);
		$winner = Bonus::where('status', 1)->inRandomOrder()->first();
		
		$win = [
			'sum' => $winner->sum,
			'color'  => $winner->color
		];
		
        $line[80] = $win;
		$remaining = Carbon::now()->addDay(1)->getTimestamp();
		
		BonusLog::create([
            'user_id' => $this->user->id,
            'sum' => $win['sum'],
            'remaining' => $remaining,
            'status' => 1
        ]);
		
		$this->user->balance += $win['sum'];
        $this->user->save();
		
		$this->redis->publish('updateBalanceAfter', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));
		
		$this->redis->publish('bonus', json_encode([
			'user_id'    => $this->user->id,
			'line'       => $line,
			'ml'		 => mt_rand(4181, 4238)
		]));
		
		$this->redis->publish('bonus_win', json_encode([
			'user'  => $this->user->id,
			'msg'   => 'Вы получили бонус в размере '.$win['sum'].' '.trans_choice('монету|монеты|монет', $win['sum']).'!',
			'type'  => 'success'
		]));
		
		return [
            'success' => true,
            'msg' => 'Открываем!',
            'type' => 'success'
        ];
	}
	
	public function withdraw_cancel($id) {
		if(\Cache::has('action.user.' . $this->user->id)) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Подождите пожалуйста перед следующим действием!',
                'type'  => 'error'
            ]));
            return;
		}
        \Cache::put('action.user.' . $this->user->id, '', 0.05);
        $withdraw = Withdraw::where('id', $id)->first();
		
		if($withdraw->status > 0) return redirect()->route('payhistory')->with('error', 'Вы не можете отменить данный вывод!');
		if($withdraw->user_id != $this->user->id) return redirect()->route('payhistory')->with('error', 'Вы не можете отменить вывод другого пользователя!');
		
		if($withdraw->system == 'qiwi') {
            $perc = 4;
            $com = 1;
        } elseif($withdraw->system == 'webmoney') {
            $perc = 6;
            $com = 0;
        } elseif($withdraw->system == 'yandex') {
            $perc = 0;
            $com = 0;
        } elseif($withdraw->system == 'visa') {
            $perc = 4;
            $com = 50;
        }
        
        $valwithcom = round($withdraw->value+($withdraw->value/100*$perc+$com));
        if($this->user->is_youtuber) $valwithcom = $val/10;
		
		$this->user->balance += $valwithcom;
        $this->user->save();
        $withdraw->status = 2;
        $withdraw->save();
		
		return redirect()->route('payhistory')->with('success', 'Вы отменили вывод на '.$withdraw->value.'р.');
	}
    
    public function pay(Request $request) {
        $sum = $request->get('num');
		
		if(!$sum) return \Redirect::back();
        
        Settings::where('id', 1)->update([
            'order_id' => $this->settings->order_id+1 
        ]);
		
		return Redirect('https://pay.freekassa.ru/?'.http_build_query([
                'm' => $this->settings->mrh_ID,
                'oa' => $request->num,
				'currency' => 'RUB',
                'o' => $this->settings->order_id,
                's' => md5($this->settings->mrh_ID.':'.$sum.':'.$this->settings->mrh_secret1.':'.$this->settings->order_id),
                'lang' => 'ru',
                'i' => $this->user->user_id
		]));
        
	}
	
	public function result(Request $request) {
        $ip = false;
        if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $this->getIp($_SERVER['HTTP_X_REAL_IP']);
        } else {
            $ip = $this->getIp($_SERVER['REMOTE_ADDR']);
        }
        if(!$ip) return redirect()->route('jackpot')->with('error', 'Ошибка при проверке IP free-kassa!');
        
		$order = $this->chechOrder($request->get('MERCHANT_ORDER_ID'), $request->get('AMOUNT'));
		if($order['type'] == 'error') return ['msg' => $order['msg'], 'type' => 'error'];
		
		/*CREATE PAY*/
        $pay = [
            'secret' => md5($this->settings->mrh_ID . ":" . $request->get('AMOUNT') . ":" . $this->settings->mrh_secret1 . ":" . $this->settings->order_id),
            'merchant_id' => $this->settings->mrh_ID,
            'order_id' => $request->get('MERCHANT_ORDER_ID'),
            'sum' => $request->get('AMOUNT'),
            'user_id' => $request->get('us_uid'),
            'status' => 0
        ];
        Payments::insert($pay);
        
        $user = User::where('user_id', $request->get('us_uid'))->first();
        if(!$user) return ['msg' => 'User not found!', 'type' => 'error'];
        
		/* ADD Balance from user and partner */
        $sum = floor($request->get('AMOUNT'));
        User::where('user_id', $user->user_id)->update([
            'balance' => $user->balance+$sum 
        ]);
        /*REDIRECT*/
		
		SuccessPay::insert([
        	'user' => $user->user_id,
            'price' => $sum,
            'status' => 1,
       	]);
		
        Payments::where('order_id', $request->get('MERCHANT_ORDER_ID'))->update([
            'status' => 1 
        ]);
		
        /* SUCCESS REDIRECT */
        return ['msg' => 'Your order #'.$request->get('MERCHANT_ORDER_ID').' has been paid successfully!', 'type' => 'success'];
	}
	
	private function chechOrder($id, $sum) {
		$merch = Payments::where('order_id', $id)->first();
		if(!$merch) return ['msg' => 'Order checked!', 'type' => 'success'];
		if($sum != $merch->sum) return ['msg' => 'You paid another order!', 'type' => 'error'];
		if($merch->order_id == $id && $merch->status == 1) return ['msg' => 'Order alredy paid!', 'type' => 'error'];
		
		return ['msg' => 'Order checked!', 'type' => 'success'];
	}
    
    /* CHECK FREE KASSA IP */
    function getIp($ip) {
        $list = ['136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98', '37.1.14.226', '136.243.38.108', '80.71.252.10'];
        for($i = 0; $i < count($list); $i++) {
            if($list[$i] == $ip) return true;
        }
        return false;
    }
    
    public function getMerchBalance() {
        $sign = md5($this->settings->mrh_ID.$this->settings->mrh_secret2);
        $xml_string = file_get_contents('http://www.free-kassa.ru/api.php?merchant_id='.$this->settings->mrh_ID.'&s='.$sign.'&action=get_balance');
        
        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $balance = json_decode($json, true);
        
        if($balance['answer'] == 'info') {
            $sum = $balance['balance'];
            if($sum >= 50) {
                sleep(11);
                return $this->sendToWallet($sum);
            } else {
                return [
                    'msg' => 'Not enough money on the balance of the merchant!',
                    'type' => $balance['answer']
                ];
            }
        } else {
            return [
                'msg' => $balance['desc'],
                'type' => $balance['answer']
            ];
        }
    }
	
	public function sendToWallet($sum) {
        $sign = md5($this->settings->mrh_ID.$this->settings->mrh_secret2);
        $xml_string = file_get_contents('http://www.free-kassa.ru/api.php?currency=fkw&merchant_id='.$this->settings->mrh_ID.'&s='.$sign.'&action=payment&amount='.$sum);
        
        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $res = json_decode($json, true);
        
        if($res['answer'] == 'info') {
            return [
                'msg' => $res['desc'].', PaymentId - '.$res['PaymentId'],
                'type' => $res['answer']
            ];
        } else {
            return [
                'msg' => $res['desc'],
                'type' => $res['answer']
            ];
        }
        return $res;
    }
	
	public function success() {
		return redirect()->route('jackpot')->with('success', 'Ваш баланс успешно пополнен!');
	}
	
	public function fail() {
		return redirect()->route('jackpot')->with('error', 'Ошибка при пополнении баланса!');
	}
    
    public function withdraw(Request $request) {
		if(\Cache::has('action.user.' . $this->user->id)) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Подождите пожалуйста перед следующим действием!',
                'type'  => 'error'
            ]));
            return;
		}
        \Cache::put('action.user.' . $this->user->id, '', 0.05);
        $system = $request->get('system');
        $value = $request->get('value');
        $wallet = str_replace([' ', '+', '(', ')', '-'], '', $request->get('wallet'));
        $val = floor($value);
        
        $dep = SuccessPay::where('user', $this->user->user_id)->where('status', 1)->sum('price');
        
        if($dep < 50) return [
            'success' => false,
            'msg' => 'Вам необходимо пополнить счет на 50 рублей для вывода средств!',
            'type' => 'error'
        ];
        
        if($system == 'qiwi') {
            $perc = 4;
            $com = 1;
        } elseif($system == 'webmoney') {
            $perc = 6;
            $com = 0;
        } elseif($system == 'yandex') {
            $perc = 5;
            $com = 0;
        } elseif($system == 'visa') {
            $perc = 4;
            $com = 50;
        }
        
        $valwithcom = round($val-($val/100*$perc+$com));
        if($this->user->is_youtuber) $valwithcom = $val/10;
        
        if($system == 'qiwi' && $valwithcom < 100) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 105 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        } elseif($system == 'webmoney' && $valwithcom < 10) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 10 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        } elseif($system == 'yandex' && $valwithcom < 100) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 100 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        } elseif($system == 'visa' && $valwithcom < 1000) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 1000 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        }
        
        if($valwithcom > 5000) {
            return [
                'success' => false,
                'msg' => 'Максимальная сумма для вывода 5000 рублей! с учетом комиссии',
                'type' => 'error'
            ];
        }
        
        if($valwithcom == 0) return [
            'success' => false,
            'msg' => 'Не правильно введена сумма!',
            'type' => 'error'
        ];
        if(is_null($system) || is_null($val) || is_null($wallet)) return [
            'success' => false,
            'msg' => 'Вы не заполнили один из пунктов!',
            'type' => 'error'
        ];
        if($val > $this->user->balance) return [
            'success' => false,
            'msg' => 'Вы не можете вывести сумму больше чем Ваш баланс!',
            'type' => 'error'
        ];
        
        Withdraw::insert([
            'user_id' => $this->user->id,
            'value' => $valwithcom,
            'system' => $system,
            'wallet' => $wallet
        ]);
        
        $this->user->balance -= $val;
        $this->user->save();
        
        $this->redis->publish('updateBalance', json_encode([
            'id' => $this->user->id,
            'balance' => $this->user->balance
        ]));
        
        return [
            'success' => true,
            'msg' => 'Вы оставили заявку на вывод!',
            'type' => 'success'
        ];
    }
	public function fair(Request $request) {
		$hash = 'xxxxx-xxxxx-xxxxx-xxxxx';
		if ($request->pjax() && $request->ajax()) {
			return view('pages.fair', compact('hash'));
        }
		return view('layout')->with('page', view('pages.fair', compact('hash')));
	}
	
	public function fairGame(Request $request, $hash) {
		if ($request->pjax() && $request->ajax()) {
			return view('pages.fair', compact('hash'));
        }
		return view('layout')->with('page', view('pages.fair', compact('hash')));
	}
	
	public function fairCheck(Request $request) {
		$hash = $request->get('hash');
		if(!$hash) return [
			'success' => false,
			'type' => 'error',
			'msg' => 'Поле не может быть пустым!'
		];
		$double = Double::where(['hash' => $hash, 'status' => 3])->first();
		$coin = Pvp::where(['hash' => $hash, 'status' => 1])->first();
		$jackpot = Jackpot::where(['hash' => $hash, 'status' => 3])->first();
		$battle = Battle::where(['hash' => $hash, 'status' => 3])->first();
		
		if(!is_null($double)) {
			$info = [
				'id' => $double->id,
				'number' => $double->winner_num
			];
		} elseif(!is_null($jackpot)) {
			$info = [
				'id' => $jackpot->id,
				'number' => $jackpot->winner_ticket
			];
		} elseif(!is_null($coin)) {
			$info = [
				'id' => $coin->id,
				'number' => $coin->winner_ticket
			];
		} elseif(!is_null($battle)) {
			$info = [
				'id' => $battle->id,
				'number' => $battle->winner_ticket
			];
		} else {
			return [
				'success' => false,
				'type' => 'error',
				'msg' => 'Неверный хэш или раунд еще не сыгран!'
			];
		}
		return [
			'success' => true,
			'type' => 'success',
			'msg' => 'Хэш найден!',
			'round' => $info['id'],
			'number' => $info['number']
		];
	}
}