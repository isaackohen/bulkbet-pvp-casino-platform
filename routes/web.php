<?php

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
Route::get('/', 'JackpotController@index')->name('jackpot');
Route::get('/pvp', 'PvpController@index')->name('pvp');
Route::get('/double', 'DoubleController@index')->name('double');
Route::get('/battle', 'BattleController@index')->name('battle');

Route::get('/history/jackpot', 'HistoryController@jackpot')->name('jackpotHistory');
Route::get('/history/double', 'HistoryController@double')->name('doubleHistory');
Route::get('/history/battle', 'HistoryController@battle')->name('battleHistory');
Route::get('/history/pvp', 'HistoryController@pvp')->name('pvpHistory');

Route::get('/partnership', 'PagesController@partnership')->name('partnership');
Route::get('/help', 'PagesController@help')->name('help');
Route::get('/rules', 'PagesController@rules')->name('rules');
Route::get('/fair', 'PagesController@fair')->name('fair');
Route::get('/fair/{hash}', 'PagesController@fairGame')->name('fairGame');
Route::post('/fair/check', 'PagesController@fairCheck');
Route::get('/result', 'PagesController@result');
Route::get('/success', 'PagesController@success');
Route::get('/fail', 'PagesController@fail');

Route::prefix('/auth')->group(function () {
    Route::get('/telegram/callback', 'AuthController@handleTelegramCallback')->name('auth.telegram.handle');
});

Route::middleware('auth')->group(function () {
    Route::prefix('roulette')->group(function () {
        Route::post('/addBet', 'DoubleController@addBet');
        Route::post('/getBet', 'DoubleController@getBet');
        Route::get('/history', 'DoubleController@history');
    });
    Route::get('/logout', 'AuthController@logout')->name('logout');
    Route::get('/referral', 'PagesController@referral')->name('ref');
    Route::get('/bonus', 'PagesController@bonus')->name('bonus');
    Route::get('/pay', 'PagesController@pay')->name('pay');
    Route::get('/pay/history', 'PagesController@payHistory')->name('payhistory');
    Route::get('/withdraw/cancel/{id}', 'PagesController@withdraw_cancel');
    Route::post('/withdraw', 'PagesController@withdraw');
    Route::post('/ref/activate', 'PagesController@refActivate');
    Route::post('/ref/getMoney', 'PagesController@getMoney');
    Route::post('/bonus/getBonus', 'PagesController@getBonus');
    Route::post('/battle/addBet', 'BattleController@newBet');
    Route::post('/newBet', 'JackpotController@newBet');
    Route::post('/chat', 'ChatController@add_message');
    Route::post('/flip/newGame', 'PvpController@createRoom');
    Route::post('/flip/joinRoom', 'PvpController@joinRoom');
});

Route::middleware('access:admin')->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin');
    Route::get('/admin/botOn', 'AdminController@botOn')->name('botOn');
    Route::get('/admin/botOff', 'AdminController@botOff')->name('botOff');
    Route::get('/admin/users', 'AdminController@users')->name('adminUsers');
    Route::get('/admin/bots', 'AdminController@bots')->name('adminBots');
    Route::get('/admin/user/{id}', 'AdminController@user')->name('adminUser');
    Route::get('/admin/settings', 'AdminController@settings')->name('adminSettings');
    Route::get('/admin/withdraw', 'AdminController@withdraw')->name('adminWithdraw');
    Route::get('/admin/bonuses', 'AdminController@bonus')->name('adminBonus');
    Route::get('/admin/promo', 'AdminController@promo')->name('adminPromo');
    Route::get('/admin/user/delete/{id}', 'AdminController@userDelete');
    Route::post('/admin/userSave', 'AdminController@userSave');
    Route::post('/admin/usersAjax', 'AdminController@usersAjax');
    Route::post('/admin/promoNew', 'AdminController@promoNew');
    Route::post('/admin/promoSave', 'AdminController@promoSave');
    Route::get('/admin/promoDelete/{id}', 'AdminController@promoDelete');
    Route::post('/admin/bonusNew', 'AdminController@bonusNew');
    Route::post('/admin/bonusSave', 'AdminController@bonusSave');
    Route::get('/admin/bonusDelete/{id}', 'AdminController@bonusDelete');
    Route::post('/admin/settingSave', 'AdminController@settingsSave');
    Route::get('/admin/withdraw/{id}', 'AdminController@withdrawSend');
    Route::get('/admin/return/{id}', 'AdminController@withdrawReturn');
    Route::post('/admin/getBalance', 'AdminController@getBalans_frw');
    Route::post('/admin/gotDouble', 'RouletteController@gotDouble');
    Route::post('/admin/gotRoulette', 'JackpotController@gotRoulette');
    Route::post('/admin/getVKUser', 'AdminController@getVKUser');
    Route::post('/admin/fakeSave', 'AdminController@fakeSave');
    Route::post('/admin/chatSend', 'AdminController@add_message');
    Route::post('/chatdel', 'ChatController@delete_message');
    Route::post('/admin/gotDouble', 'DoubleController@gotThis');
    Route::post('/admin/gotJackpot', 'JackpotController@gotThis');
    Route::post('/admin/gotBattle', 'BattleController@gotThis');
    Route::post('/admin/betJackpot', 'JackpotController@adminBet');
    Route::post('/admin/betDouble', 'DoubleController@adminBet');
});

Route::group(['middleware' => 'auth', 'middleware' => 'moder:Moder'], function () {
    Route::get('/admin', 'AdminController@index')->name('admin');
    Route::get('/admin/promo', 'AdminController@promo')->name('adminPromo');
    Route::post('/admin/promoNew', 'AdminController@promoNew');
    Route::post('/admin/promoSave', 'AdminController@promoSave');
    Route::get('/admin/promoDelete/{id}', 'AdminController@promoDelete');
    Route::post('/chatdel', 'ChatController@delete_message');
});

Route::prefix('api')->middleware('secretKey')->group(function () {
    Route::prefix('jackpot')->group(function () {
        Route::post('/newGame', 'JackpotController@newGame');
        Route::post('/getSlider', 'JackpotController@getSlider');
        Route::post('/getStatus', 'JackpotController@getStatus');
        Route::post('/setStatus', 'JackpotController@setStatus');
        Route::post('/getRooms', 'JackpotController@getRooms');
        Route::post('/addBetFake', 'JackpotController@newBetFake');
    });
    Route::prefix('roulette')->group(function () {
        Route::post('/getGame', 'DoubleController@getGame');
        Route::post('/updateStatus', 'DoubleController@updateStatus');
        Route::post('/getSlider', 'DoubleController@getSlider');
        Route::post('/newGame', 'DoubleController@newGame');
        Route::post('/addBetFake', 'DoubleController@addBetFake');
    });
    Route::prefix('battle')->group(function () {
        Route::post('/newGame', 'BattleController@newGame');
        Route::post('/getSlider', 'BattleController@getSlider');
        Route::post('/getStatus', 'BattleController@getStatus');
        Route::post('/setStatus', 'BattleController@setStatus');
    });
    Route::post('/getMerchBalance', 'AdminController@getMerchBalance');
    Route::post('/getParam', 'AdminController@getParam');
});
