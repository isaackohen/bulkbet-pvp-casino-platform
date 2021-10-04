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
Route::get('/', ['as' => 'jackpot', 'uses' => 'JackpotController@index']);
Route::get('/pvp', ['as' => 'pvp', 'uses' => 'PvpController@index']);
Route::get('/double', ['as' => 'double', 'uses' => 'DoubleController@index']);
Route::get('/battle', ['as' => 'battle', 'uses' => 'BattleController@index']);

Route::get('/history/jackpot', ['as' => 'jackpotHistory', 'uses' => 'HistoryController@jackpot']);
Route::get('/history/double', ['as' => 'doubleHistory', 'uses' => 'HistoryController@double']);
Route::get('/history/battle', ['as' => 'battleHistory', 'uses' => 'HistoryController@battle']);
Route::get('/history/pvp', ['as' => 'pvpHistory', 'uses' => 'HistoryController@pvp']);

Route::get('/partnership', ['as' => 'partnership', 'uses' => 'PagesController@partnership']);
Route::get('/help', ['as' => 'help', 'uses' => 'PagesController@help']);
Route::get('/rules', ['as' => 'rules', 'uses' => 'PagesController@rules']);
Route::get('/fair', ['as' => 'fair', 'uses' => 'PagesController@fair']);
Route::get('/fair/{hash}', ['as' => 'fairGame', 'uses' => 'PagesController@fairGame']);
Route::post('/fair/check', 'PagesController@fairCheck');
Route::get('/result', 'PagesController@result');
Route::get('/success', 'PagesController@success');
Route::get('/fail', 'PagesController@fail');

Route::group(['prefix' => '/auth'], function () {
    Route::get('/telegram/callback', 'AuthController@handleTelegramCallback')->name('auth.telegram.handle');
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'roulette'], function () {
        Route::post('/addBet', 'DoubleController@addBet');
        Route::post('/getBet', 'DoubleController@getBet');
        Route::get('/history', 'DoubleController@history');
    });
    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    Route::get('/referral', ['as' => 'ref', 'uses' => 'PagesController@referral']);
    Route::get('/bonus', ['as' => 'bonus', 'uses' => 'PagesController@bonus']);
    Route::get('/pay', ['as' => 'pay', 'uses' => 'PagesController@pay']);
    Route::get('/pay/history', ['as' => 'payhistory', 'uses' => 'PagesController@payHistory']);
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

Route::group(['middleware' => 'auth', 'middleware' => 'access:admin'], function () {
    Route::get('/admin', ['as' => 'admin', 'uses' => 'AdminController@index']);
    Route::get('/admin/botOn', ['as' => 'botOn', 'uses' => 'AdminController@botOn']);
    Route::get('/admin/botOff', ['as' => 'botOff', 'uses' => 'AdminController@botOff']);
    Route::get('/admin/users', ['as' => 'adminUsers', 'uses' => 'AdminController@users']);
    Route::get('/admin/bots', ['as' => 'adminBots', 'uses' => 'AdminController@bots']);
    Route::get('/admin/user/{id}', ['as' => 'adminUser', 'uses' => 'AdminController@user']);
    Route::get('/admin/settings', ['as' => 'adminSettings', 'uses' => 'AdminController@settings']);
    Route::get('/admin/withdraw', ['as' => 'adminWithdraw', 'uses' => 'AdminController@withdraw']);
    Route::get('/admin/bonuses', ['as' => 'adminBonus', 'uses' => 'AdminController@bonus']);
    Route::get('/admin/promo', ['as' => 'adminPromo', 'uses' => 'AdminController@promo']);
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
    Route::get('/admin', ['as' => 'admin', 'uses' => 'AdminController@index']);
    Route::get('/admin/promo', ['as' => 'adminPromo', 'uses' => 'AdminController@promo']);
    Route::post('/admin/promoNew', 'AdminController@promoNew');
    Route::post('/admin/promoSave', 'AdminController@promoSave');
    Route::get('/admin/promoDelete/{id}', 'AdminController@promoDelete');
    Route::post('/chatdel', 'ChatController@delete_message');
});

Route::group(['prefix' => 'api', 'middleware' => 'secretKey'], function () {
    Route::group(['prefix' => 'jackpot'], function () {
        Route::post('/newGame', 'JackpotController@newGame');
        Route::post('/getSlider', 'JackpotController@getSlider');
        Route::post('/getStatus', 'JackpotController@getStatus');
        Route::post('/setStatus', 'JackpotController@setStatus');
        Route::post('/getRooms', 'JackpotController@getRooms');
        Route::post('/addBetFake', 'JackpotController@newBetFake');
    });
    Route::group(['prefix' => 'roulette'], function () {
        Route::post('/getGame', 'DoubleController@getGame');
        Route::post('/updateStatus', 'DoubleController@updateStatus');
        Route::post('/getSlider', 'DoubleController@getSlider');
        Route::post('/newGame', 'DoubleController@newGame');
        Route::post('/addBetFake', 'DoubleController@addBetFake');
    });
    Route::group(['prefix' => 'battle'], function () {
        Route::post('/newGame', 'BattleController@newGame');
        Route::post('/getSlider', 'BattleController@getSlider');
        Route::post('/getStatus', 'BattleController@getStatus');
        Route::post('/setStatus', 'BattleController@setStatus');
    });
    Route::post('/getMerchBalance', 'AdminController@getMerchBalance');
    Route::post('/getParam', 'AdminController@getParam');
});
