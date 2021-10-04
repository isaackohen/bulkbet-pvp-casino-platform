<?php namespace App\Http\Controllers;

use App\User;
use App\Rooms;
use App\Jackpot;
use App\Pvp;
use App\Battle;
use App\Double;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
	
	public function jackpot(Request $request) 
	{
		$rooms = Rooms::where('status', 0)->orderBy('id', 'asc')->get();
        $games = Jackpot::where('status', 3)->where('updated_at', '>=', Carbon::today())->orderBy('game_id', 'desc')->get();
		if(is_null($games)) return redirect()->route('jackpotHistory');
        $history = [];
        foreach($games as $game) {
            $winner = User::where('id', $game->winner_id)->first();
			if(isset($winner)) {
				$history[] = [
					'game_id' => $game->game_id,
					'room' => $game->room,
					'winner_id' => $game->winner_id,
					'winner_name' => $winner->username,
					'winner_avatar' => $winner->avatar,
					'winner_chance' => $game->winner_chance,
					'winner_sum' => $game->winner_sum,
					'winner_ticket' => $game->winner_ticket,
					'hash' => $game->hash,
					'data' => Carbon::parse($game->updated_at)->diffForHumans(),
					'price' => $game->price,
					'bets' => \App\Http\Controllers\JackpotController::getChancesOfGame($game->room, $game->game_id)
				];
			}
        }
		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.history.jackpotHistory', compact('rooms', 'history'));
        }

		return view('layout')->with('page', view('pages.history.jackpotHistory', compact('rooms', 'history')));
    }
	
	public function double(Request $request) 
    {
        $games = Double::select('id', 'price', 'updated_at', 'winner_color', 'winner_num', 'hash')->where('status', 3)->orderBy('id', 'desc')->limit(30)->get();
		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.history.doubleHistory', compact('games'));
        }

		return view('layout')->with('page', view('pages.history.doubleHistory', compact('games')));
    }
	
	public function battle(Request $request) 
    {
        $games = Battle::select('id', 'price', 'updated_at', 'winner_team', 'winner_factor', 'winner_ticket', 'hash')->where('status', 3)->orderBy('id', 'desc')->limit(30)->get();
         		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.history.battleHistory', compact('games'));
        }

		return view('layout')->with('page', view('pages.history.battleHistory', compact('games')));
    }
	
	public function pvp(Request $request) 
	{
		$games = Pvp::where('status', 1)->orderBy('id', 'desc')->limit(6)->get();
		         		
		if ($request->pjax() && $request->ajax()) {
			return view('pages.history.pvpHistory', compact('games'));
        }

		return view('layout')->with('page', view('pages.history.pvpHistory', compact('games')));
	}
    
}