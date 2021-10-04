<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
	
	protected $fillable = ['domain', 'sitename', 'title', 'desc', 'keys', 'tg_url', 'fake', 'mrh_ID', 'mrh_secret1', 'mrh_secret2', 'fk_api', 'fk_wallet', 'double_timer', 'double_min_bet', 'double_max_bet', 'double_fake_min', 'double_fake_max', 'battle_timer', 'battle_min_bet', 'battle_max_bet', 'battle_commission'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
}