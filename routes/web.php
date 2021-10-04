<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DoubleController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\JackpotController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PvpController;
use App\Http\Controllers\RouletteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [JackpotController::class, 'index'])->name('jackpot');
Route::get('/pvp', [PvpController::class, 'index'])->name('pvp');
Route::get('/double', [DoubleController::class, 'index'])->name('double');
Route::get('/battle', [BattleController::class, 'index'])->name('battle');

Route::get('/history/jackpot', [HistoryController::class, 'jackpot'])->name('jackpotHistory');
Route::get('/history/double', [HistoryController::class, 'double'])->name('doubleHistory');
Route::get('/history/battle', [HistoryController::class, 'battle'])->name('battleHistory');
Route::get('/history/pvp', [HistoryController::class, 'pvp'])->name('pvpHistory');

Route::get('/partnership', [PagesController::class, 'partnership'])->name('partnership');
Route::get('/help', [PagesController::class, 'help'])->name('help');
Route::get('/rules', [PagesController::class, 'rules'])->name('rules');
Route::get('/fair', [PagesController::class, 'fair'])->name('fair');
Route::get('/fair/{hash}', [PagesController::class, 'fairGame'])->name('fairGame');
Route::post('/fair/check', [PagesController::class, 'fairCheck']);
Route::get('/result', [PagesController::class, 'result']);
Route::get('/success', [PagesController::class, 'success']);
Route::get('/fail', [PagesController::class, 'fail']);

Route::prefix('/auth')->group(function () {
    Route::get('/telegram/callback', [AuthController::class, 'handleTelegramCallback'])->name('auth.telegram.handle');
});

Route::middleware('auth')->group(function () {
    Route::prefix('roulette')->group(function () {
        Route::post('/addBet', [DoubleController::class, 'addBet']);
        Route::post('/getBet', [DoubleController::class, 'getBet']);
        Route::get('/history', [DoubleController::class, 'history']);
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/referral', [PagesController::class, 'referral'])->name('ref');
    Route::get('/bonus', [PagesController::class, 'bonus'])->name('bonus');
    Route::get('/pay', [PagesController::class, 'pay'])->name('pay');
    Route::get('/pay/history', [PagesController::class, 'payHistory'])->name('payhistory');
    Route::get('/withdraw/cancel/{id}', [PagesController::class, 'withdraw_cancel']);
    Route::post('/withdraw', [PagesController::class, 'withdraw']);
    Route::post('/ref/activate', [PagesController::class, 'refActivate']);
    Route::post('/ref/getMoney', [PagesController::class, 'getMoney']);
    Route::post('/bonus/getBonus', [PagesController::class, 'getBonus']);
    Route::post('/battle/addBet', [BattleController::class, 'newBet']);
    Route::post('/newBet', [JackpotController::class, 'newBet']);
    Route::post('/chat', [ChatController::class, 'add_message']);
    Route::post('/flip/newGame', [PvpController::class, 'createRoom']);
    Route::post('/flip/joinRoom', [PvpController::class, 'joinRoom']);
});

Route::middleware('access:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/botOn', [AdminController::class, 'botOn'])->name('botOn');
    Route::get('/admin/botOff', [AdminController::class, 'botOff'])->name('botOff');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('adminUsers');
    Route::get('/admin/bots', [AdminController::class, 'bots'])->name('adminBots');
    Route::get('/admin/user/{id}', [AdminController::class, 'user'])->name('adminUser');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('adminSettings');
    Route::get('/admin/withdraw', [AdminController::class, 'withdraw'])->name('adminWithdraw');
    Route::get('/admin/bonuses', [AdminController::class, 'bonus'])->name('adminBonus');
    Route::get('/admin/promo', [AdminController::class, 'promo'])->name('adminPromo');
    Route::get('/admin/user/delete/{id}', [AdminController::class, 'userDelete']);
    Route::post('/admin/userSave', [AdminController::class, 'userSave']);
    Route::post('/admin/usersAjax', [AdminController::class, 'usersAjax']);
    Route::post('/admin/promoNew', [AdminController::class, 'promoNew']);
    Route::post('/admin/promoSave', [AdminController::class, 'promoSave']);
    Route::get('/admin/promoDelete/{id}', [AdminController::class, 'promoDelete']);
    Route::post('/admin/bonusNew', [AdminController::class, 'bonusNew']);
    Route::post('/admin/bonusSave', [AdminController::class, 'bonusSave']);
    Route::get('/admin/bonusDelete/{id}', [AdminController::class, 'bonusDelete']);
    Route::post('/admin/settingSave', [AdminController::class, 'settingsSave']);
    Route::get('/admin/withdraw/{id}', [AdminController::class, 'withdrawSend']);
    Route::get('/admin/return/{id}', [AdminController::class, 'withdrawReturn']);
    Route::post('/admin/getBalance', [AdminController::class, 'getBalans_frw']);
    Route::post('/admin/gotDouble', [RouletteController::class, 'gotDouble']);
    Route::post('/admin/gotRoulette', [JackpotController::class, 'gotRoulette']);
    Route::post('/admin/getVKUser', [AdminController::class, 'getVKUser']);
    Route::post('/admin/fakeSave', [AdminController::class, 'fakeSave']);
    Route::post('/admin/chatSend', [AdminController::class, 'add_message']);
    Route::post('/chatdel', [ChatController::class, 'delete_message']);
    Route::post('/admin/gotDouble', [DoubleController::class, 'gotThis']);
    Route::post('/admin/gotJackpot', [JackpotController::class, 'gotThis']);
    Route::post('/admin/gotBattle', [BattleController::class, 'gotThis']);
    Route::post('/admin/betJackpot', [JackpotController::class, 'adminBet']);
    Route::post('/admin/betDouble', [DoubleController::class, 'adminBet']);
});

Route::group(['middleware' => 'auth', 'middleware' => 'moder:Moder'], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/promo', [AdminController::class, 'promo'])->name('adminPromo');
    Route::post('/admin/promoNew', [AdminController::class, 'promoNew']);
    Route::post('/admin/promoSave', [AdminController::class, 'promoSave']);
    Route::get('/admin/promoDelete/{id}', [AdminController::class, 'promoDelete']);
    Route::post('/chatdel', [ChatController::class, 'delete_message']);
});

Route::prefix('api')->middleware('secretKey')->group(function () {
    Route::prefix('jackpot')->group(function () {
        Route::post('/newGame', [JackpotController::class, 'newGame']);
        Route::post('/getSlider', [JackpotController::class, 'getSlider']);
        Route::post('/getStatus', [JackpotController::class, 'getStatus']);
        Route::post('/setStatus', [JackpotController::class, 'setStatus']);
        Route::post('/getRooms', [JackpotController::class, 'getRooms']);
        Route::post('/addBetFake', [JackpotController::class, 'newBetFake']);
    });
    Route::prefix('roulette')->group(function () {
        Route::post('/getGame', [DoubleController::class, 'getGame']);
        Route::post('/updateStatus', [DoubleController::class, 'updateStatus']);
        Route::post('/getSlider', [DoubleController::class, 'getSlider']);
        Route::post('/newGame', [DoubleController::class, 'newGame']);
        Route::post('/addBetFake', [DoubleController::class, 'addBetFake']);
    });
    Route::prefix('battle')->group(function () {
        Route::post('/newGame', [BattleController::class, 'newGame']);
        Route::post('/getSlider', [BattleController::class, 'getSlider']);
        Route::post('/getStatus', [BattleController::class, 'getStatus']);
        Route::post('/setStatus', [BattleController::class, 'setStatus']);
    });
    Route::post('/getMerchBalance', [AdminController::class, 'getMerchBalance']);
    Route::post('/getParam', [AdminController::class, 'getParam']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
