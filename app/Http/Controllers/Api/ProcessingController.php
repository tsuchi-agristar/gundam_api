<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Games;
use App\Models\Settings;
use App\Models\Attacks;
use App\Models\Summaries;

class ProcessingController extends Controller
{
    /**
     * 
     * 
     */
    public function start(Request $request)
    {
        \Log::debug('[processing.start] request=' . json_encode($request->json()->all()));
        $response_data = null;

        DB::beginTransaction();
        try {
            Attacks::truncate();
            Summaries::truncate();

            $game = Games::create(
              [
                'game_start_at' => Carbon::createFromTimestamp($request->start_time)
              ]
            );

            //平日/休日対応
            $dayOfWeek = Carbon::now()->dayOfWeek; //曜日は0(日) ～ 6 (土)
            $dayType = $dayOfWeek == 0 || $dayOfWeek == 6 ? config('const.day_type.holiday') : config('const.day_type.weekday');
            $response_data = Settings::find($dayType);
            $response_data['game_id'] = $game->game_id;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::critical('[processing.start] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
            $response_data['error'][] = $e->getMessage();
            return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return \Response::json($response_data, Response::HTTP_OK);
    }

    /**
     * 
     * 
     */
    public function data(Request $request)
    {
        //\Log::debug('[processing.data] request=' . json_encode($request->json()->all()));
        $response_data = null;

        try {
            $attacks = Attacks::select(['id', 'sid', 'team'])->where('id', '>', $request->id)->get();
            $response_data = $attacks;
        } catch (\Exception $e) {
            \Log::critical('[processing.data] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
            $response_data['error'][] = $e->getMessage();
            return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return \Response::json($response_data, Response::HTTP_OK);
    }

    /**
     * 
     * 
     */
    public function result(Request $request)
    {
        \Log::debug('[processing.result] request=' . json_encode($request->json()->all()));
        $response_data = null;

        DB::beginTransaction();
        try {
            Games::find($request->game_id)->update($request->all());
            $response_data = Games::find($request->game_id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::critical('[processing.result] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
            $response_data['error'][] = $e->getMessage();
            return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return \Response::json($response_data, Response::HTTP_OK);
    }

    /**
     * 
     * 
     */
    public function end(Request $request)
    {
        \Log::debug('[processing.end] request=' . json_encode($request->json()->all()));
        $response_data = null;

        DB::beginTransaction();
        try {
            $japan = Attacks::distinct('sid')->where('language', config('const.language.japanese'))->count();
            $korea = Attacks::distinct('sid')->where('language', config('const.language.korean'))->count();
            $english = Attacks::distinct('sid')->where('language', config('const.language.english'))->count();
            $chinese = Attacks::distinct('sid')->where('language', config('const.language.chinese'))->count();
            $a_participants = Attacks::distinct('sid')->where('team', config('const.team.a'))->count();
            $b_participants = Attacks::distinct('sid')->where('team', config('const.team.b'))->count();
            $a_launches = Attacks::where('team', config('const.team.a'))->count();
            $b_launches = Attacks::where('team', config('const.team.b'))->count();
            $total_participants = $a_participants + $b_participants;
            $total_launches = $a_launches + $b_launches;

            Games::find($request->game_id)->update(
              [
                'game_end_at' => Carbon::createFromTimestamp($request->end_time),
                'total_participants' => $total_participants,
                'total_launches' => $total_launches,
                'japan' => $japan,
                'korea' => $korea,
                'english' => $english,
                'chinese' => $chinese,
                'a_participants' => $a_participants,
                'a_launches' => $a_launches,
                'b_participants' => $b_participants,
                'b_launches' => $b_launches
              ]
            );
            $response_data = Games::find($request->game_id);

            //Summary
            $attacks_team_a = Attacks::select(DB::raw('sid, language, team, count(sid) AS attack'))->groupBy('sid', 'language', 'team')->where('team', config('const.team.a'))->orderBy('attack', 'desc')->get();
            $attacks_team_b = Attacks::select(DB::raw('sid, language, team, count(sid) AS attack'))->groupBy('sid', 'language', 'team')->where('team', config('const.team.b'))->orderBy('attack', 'desc')->get();
            $order_team_a = 1;
            foreach ($attacks_team_a as $key => $attack)
            {
                if ($key == 0) {
                    $attack['rank'] = $order_team_a;
                } else {
                    if ($attack['attack'] === $attacks_team_a[$key-1]['attack']) {
                        $attack['rank'] = $order_team_a;
                    } else {
                        $order_team_a = $key + 1;
                        $attack['rank'] = $order_team_a;
                    }
                }
            }
            $order_team_b = 1;
            foreach ($attacks_team_b as $key => $attack)
            {
                if ($key == 0) {
                    $attack['rank'] = $order_team_b;
                } else {
                    if ($attack['attack'] === $attacks_team_b[$key-1]['attack']) {
                        $attack['rank'] = $order_team_b;
                    } else {
                        $order_team_b = $key + 1;
                        $attack['rank'] = $order_team_b;
                    }
                }
            }

            //\Log::debug($attacks_team_a);
            //\Log::debug($attacks_team_b);

            if (!empty($attacks_team_a))
            {
                $attacks_team_a_Collection = collect($attacks_team_a);
                $attacks_team_a_Chunks = $attacks_team_a_Collection->chunk(50);
                foreach ($attacks_team_a_Chunks as $attacks_team_a_Chunk)
                {
                    Summaries::insert($attacks_team_a_Chunk->toArray());
                }
            }
            if (!empty($attacks_team_b))
            {
                $attacks_team_b_Collection = collect($attacks_team_b);
                $attacks_team_b_Chunks = $attacks_team_b_Collection->chunk(50);
                foreach ($attacks_team_b_Chunks as $attacks_team_b_Chunk)
                {
                    Summaries::insert($attacks_team_b_Chunk->toArray());
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::critical('[processing.end] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
            $response_data['error'][] = $e->getMessage();
            return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return \Response::json($response_data, Response::HTTP_OK);
    }
}
