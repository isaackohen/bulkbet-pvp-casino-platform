<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
    const CHAT_CHANNEL = 'chat.message';
    const NEW_MSG_CHANNEL = 'new.msg';
    const CLEAR = 'chat.clear';
	const DELETE_MSG_CHANNEL = 'del.msg';

    public function __construct()
    {
        parent::__construct();
        $this->redis = Redis::connection();
    }
	
    public static function chat()
    {
        $redis = Redis::connection();

        $value = $redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $returnValue = NULL;
        $value = array_reverse($value);

        foreach ($value as $key => $newchat[$i]) {
            if ($i > 20) {
                break;
            }
            $value2[$i] = json_decode($newchat[$i], true);

            $value2[$i]['username'] = htmlspecialchars($value2[$i]['username']);

            $returnValue[$i] = 
				[
					'user_id' => $value2[$i]['user_id'],
					'avatar' => $value2[$i]['avatar'],
					'time' => $value2[$i]['time'],
					'time2' => $value2[$i]['time2'],
					'ban' => $value2[$i]['ban'],
					'messages' => $value2[$i]['messages'],
					'username' => $value2[$i]['username'],
					'access' => $value2[$i]['access']
				];

            $i++;

        }

       if(!is_null($returnValue)) return array_reverse($returnValue);
    }


    public function __destruct()
    {
        $this->redis->disconnect();
    }

    public function add_message(Request $request)
    {
        $val = \Validator::make($request->all(), [
            'messages' => 'required|string|max:255'
        ],[
            'required' => 'Сообщение не может быть пустым!',
            'string' => 'Сообщение должно быть строкой!',
            'max' => 'Максимальный размер сообщения 255 символов.',
        ]);
        $error = $val->errors();

        if($val->fails()){
            return response()->json(['message' => $error->first('messages'), 'status' => 'error']);
        }
        
        $messages = $request->get('messages');
        if(\Cache::has('addmsg.user.' . $this->user->id)) return response()->json(['message' => 'Вы слишком часто отправляете сообщения!', 'status' => 'error']);
        \Cache::put('addmsg.user.' . $this->user->id, '', 0.05);
        $nowtime = time();
        $banchat = $this->user->banchat;
        $lasttime = $nowtime - $banchat;
        /*$dep = SuccessPay::where('user', $this->user->user_id)->where('status', 1)->sum('price')/10;*/
        /*if(!$this->user->is_admin && !$this->user->is_moder && !$this->user->is_youtuber) {
            if($dep < 10) {
                return response()->json(['message' => 'Для того чтобы писать в чат, вам нужно пополнить счет на 10 рублей!', 'status' => 'error']);
            }
        }*/
        
        if($banchat >= $nowtime) {
            return response()->json(['message' => 'Вы заблокированы до: '.date("d.m.Y H:i:s", $banchat), 'status' => 'error']);
        } else {
            User::where('user_id', $this->user->user_id)->update(['banchat' => null]);
        }
		
        $time = date('H:i', time());
        $moder = $this->user->acess == 'moder';
        $youtuber = $this->user->access == 'youtuber';
        $admin = $this->user->access == 'admin';
        $ban = $this->user->banchat;
		$user_id = $this->user->user_id;
        $username = htmlspecialchars($this->user->username);
        $avatar = $this->user->avatar;
        if($this->user->access == 'admin') {
            if(strpos($messages, '/a') !== false) {
                $admin = 1;
                $messages = str_replace('/a ', '', $messages);
            }
        }
        if ($admin) {
            $user_id = '';
        }

        function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        $words = file_get_contents(dirname(__FILE__) . '/words.json');
        $words = object_to_array(json_decode($words));

        foreach ($words as $key => $value) {
            $messages = str_ireplace($key, $value, $messages);
        }
		if(substr_count(strtolower($messages), '/send ')) {
			$rep = str_replace("/send ", "", $messages);
			$mes = explode(" ", $rep);
			$usr = User::where('user_id', $mes[0])->first();
			$with = Withdraw::where('user_id', $this->user->id)->where('status', 3)->sum('value');
			
			if($with < 250) return response()->json(['message' => 'Для отправки Вам необходимо сделать вывод на 250 рублей!', 'status' => 'error']);
			if(floor($mes[1]*1.05) > $this->user->balance) return response()->json(['message' => 'На Вашем балансе не достаточно монет для отправки!', 'status' => 'error']);
			if($mes[1] < 20) return response()->json(['message' => 'Минимальная сумма перевода 20 монет!', 'status' => 'error']);
			if(!$usr) return response()->json(['message' => 'Не удалось найти пользователя с таким ID', 'status' => 'error']);
			if($usr->user_id == $this->user->user_id) return response()->json(['message' => 'Вы не можете отправить монеты самому себе!', 'status' => 'error']);
			if (!empty($mes[1])) {
				$this->user->balance -= floor($mes[1]*1.05);
				$this->user->save();
				$usr->balance += $mes[1];
				$usr->save();
				
				$this->redis->publish('updateBalance', json_encode([
					'id'      => $this->user->id,
					'balance' => $this->user->balance
				]));

				$this->redis->publish('updateBalance', json_encode([
					'id'      => $usr->id,
					'balance' => $usr->balance
				]));
			} else {
				return response()->json(['message' => 'Вы не ввели ID игрока или количество монет', 'status' => 'error']);
			}
			
			return response()->json(['message' => 'Вы успешно отправили '.$mes[0].' монет игроку: '.$usr->username.'!', 'status' => 'success']);
		}
        if($this->user->access == 'admin' || $this->user->access == 'moder') {
            if (substr_count(strtolower($messages), '/clear')) {
                $this->redis->del(self::CHAT_CHANNEL);
                $this->redis->publish(self::CLEAR, 1);
                return response()->json(['message' => 'Вы очистили чат!', 'status' => 'success']);
            }
			if(substr_count(strtolower($messages), '/ban ')) {
                $admin = $this->user->access == 'admin';
                if ($admin) {
                    $user_id = '';
                }
                $rep = str_replace("/ban ", "", $messages);
                $mes = explode(" ", $rep);
                $usr = User::where('user_id', $mes[0])->first();
				if(!$usr) return response()->json(['message' => 'Не удалось найти пользователя с таким ID', 'status' => 'error']);
                if($usr->user_id == $this->user->user_id) return response()->json(['message' => 'Вы не можете заблокировать себя!', 'status' => 'error']);
                if (!empty($mes[1])) {
                    User::where('user_id', $usr->user_id)->update(['banchat' => Carbon::now()->addMinutes($mes[1])->getTimestamp()]);
                } else {
                    return response()->json(['message' => 'Вы не ввели ID игрока или время бана', 'status' => 'error']);
                }
                $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #65a5e1;">Пользователь "'.$usr->username.'" заблокирован в чате на '.$mes[1].' мин.</span>', 'username' => $username, 'ban' => 0, 'access' => $this->user->access];
                $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
                $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
                return response()->json(['message' => 'Вы успешно забанили игрока', 'status' => 'success']);
            }
            if(substr_count(strtolower($messages), '/unban')) {
                $admin = $this->user->access == 'admin';
                if ($admin) {
                    $user_id = '';
                }
                $userid = str_replace("/unban ", "", $messages);
                $usr = User::where('user_id', $userid)->first();
				if(!$usr) return response()->json(['message' => 'Не удалось найти пользователя с таким ID!', 'status' => 'error']);
                if($usr->user_id == $this->user->user_id) return response()->json(['message' => 'Вы не можете разблокировать себя!', 'status' => 'error']);
                if (!empty($userid)) {
                    User::where('user_id', $usr->user_id)->update(['banchat' => null]);
                } else {
                    return response()->json(['message' => 'Вы не ввели ID игрока', 'status' => 'error']);
                }
                $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #65a5e1;">Пользователь "'.$usr->username.'" разблокирован в чате</span>', 'username' => $username, 'ban' => 0, 'access' => $this->user->access];
                $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
                $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
                return response()->json(['message' => 'Вы успешно разбанили игрока', 'status' => 'success']);
            }
        } else {
			if(preg_match("/href|url|http|https|www|.ru|.com|.net|.info|csgo|winner|ru|xyz|com|net|info|.org/i", $messages)) {
				return response()->json(['message' => 'Ссылки запрещены!', 'status' => 'error']);
            }
            if(substr_count(str_replace(' ', '', $messages), $this->user->affiliate_id)) {
				return response()->json(['message' => 'Отправка промокодов запрещена!', 'status' => 'error']);
            }

        }
        $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => htmlspecialchars($messages), 'username' => $username, 'ban' => $ban, 'access' => $this->user->access];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
		return response()->json(['message' => 'Ваше сообщение успешно отправлено!', 'status' => 'success']);
	}
	
	public function delete_message(Request $request) {
        $value = $this->redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $json = json_encode($value);
        $json = json_decode($json);
        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->time2 == $request->get('messages')) {
                $this->redis->lrem(self::CHAT_CHANNEL, 1, json_encode($val));
                $this->redis->publish(self::DELETE_MSG_CHANNEL, json_encode($val));
            }
            $i++;
        }
		return response()->json(['message' => 'Сообщение удалено!', 'status' => 'success']);
    }
}
