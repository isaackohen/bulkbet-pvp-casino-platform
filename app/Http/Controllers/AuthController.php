<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Azate\LaravelTelegramLoginAuth\TelegramLoginAuth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Socialite;

class AuthController extends Controller
{
    protected $telegram;

    /**
     * Get user info and log in (hypothetically)
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function handleTelegramCallback(TelegramLoginAuth $telegramLoginAuth, Request $request)
    {
        if ($data = $telegramLoginAuth->validate($request)) {
            $user = $this->createOrGetUser($data);
            Auth::login($user, true);

            return redirect()->intended('/');
        }
    }

    public function createOrGetUser($user)
    {
        $u = User::where('user_id', $user->getId())->first();
        if ($u) {
            $username = $user->getFirstName().' '.$user->getLastName();
            User::where('user_id', $user->getId())->update([
                'username' => $username,
                'avatar' => $user->getPhotoUrl(),
                'ip' => request()->ip(),
            ]);
            $user = $u;
        } else {
            $username = $user->getFirstName().' '.$user->getLastName();
            $user = User::create([
                'user_id' => $user->getId(),
                'username' => $username,
                'avatar' => $user->getPhotoUrl() ?? '/assets/images/telegram.png',
                'affiliate_id' => str_random(10),
                'ip' => request()->ip(),
            ]);
        }

        return $user;
    }

    public function logout()
    {
        Cache::flush();
        Auth::logout();
        Session::flush();

        return redirect()->intended('/');
    }
}
