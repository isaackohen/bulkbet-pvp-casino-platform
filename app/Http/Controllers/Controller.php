<?php

namespace App\Http\Controllers;

use App\Jackpot;
use App\Rooms;
use App\Settings;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            view()->share('u', $this->user);
            if ($this->user) {
                view()->share('ref', $this->getRef($this->user->affiliate_id));
            }

            return $next($request);
        });
        Carbon::setLocale('ru');
        $this->settings = Settings::first();
        $this->redis = Redis::connection();
        view()->share('settings', $this->settings);
        view()->share('messages', $this->chatMessage());
        view()->share('maxPriceToday', $this->maxPriceToday());
        view()->share('maxPrice', $this->maxPrice());
        view()->share('gamesToday', $this->gamesToday());
    }

    public function chatMessage()
    {
        $messages = ChatController::chat();

        return $messages;
    }

    public function getRef($affiliate_id)
    {
        $ref = User::where('referred_by', $affiliate_id)->count();
        if ($ref < 10) {
            $lvl = 1;
            $perc = 0.5;
        }
        if ($ref >= 10 && $ref < 100) {
            $lvl = 2;
            $perc = 0.7;
        }
        if ($ref >= 100 && $ref < 500) {
            $lvl = 3;
            $perc = 1;
        }
        if ($ref > 500) {
            $lvl = 4;
            $perc = 1.5;
        }
        $data = [
            'count' => $ref,
            'lvl' => $lvl,
            'perc' => $perc,
        ];

        return $data;
    }

    public static function maxPriceToday()
    {
        $price = ($price = Jackpot::where('updated_at', '>=', Carbon::today())->max('price')) ? $price : 0;

        return $price;
    }

    public static function maxPrice()
    {
        $games = Jackpot::where('status', 3)->max('price');

        return $games;
    }

    public static function gamesToday()
    {
        $games = Jackpot::where('status', 3)->where('updated_at', '>=', Carbon::today())->count();

        return $games;
    }
}
