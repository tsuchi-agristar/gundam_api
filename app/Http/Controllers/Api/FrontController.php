<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use \Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FrontRequest;
use App\Models\Attacks;

class FrontController extends Controller
{
    /**
     * 
     * 
     */
    public function initialize(Request $request)
    {
        $response_data = null;
        $sid = null;

        // try {
        //     $sql = "SELECT "
        //     . "CASE"
        //     . "　WHEN game_start_at IS NULL AND game_end_at IS NULL THEN " . config('const.game_status.not')
        //     . "  WHEN game_end_at IS NOT NULL AND game_start_at + 900 < now THEN " . config('const.game_status.not')
        //     . "  WHEN now <= attack_start_at THEN " . config('const.game_status.wait')
        //     . "  WHEN attack_start_at < now AND now <= attack_end_at THEN " . config('const.game_status.start')
        //     . "  WHEN attack_end_at < now AND now <= result_at THEN " . config('const.game_status.end')
        //     . "  ELSE " . config('const.game_status.result')
        //     . " END AS status, attack_start_at, attack_end_at, result_at "
        //     . "FROM ("
        //     . "  SELECT (game_start_at + attack_start_time) AS attack_start_at, (game_start_at + attack_end_time) AS attack_end_at, (game_start_at + result_time) AS result_at, game_start_at, game_end_at, now FROM ("
        //     . "    (SELECT "
        //     . "      attack_start_time, "
        //     . "      attack_end_time, "
        //     . "      result_time, "
        //     . "      (SELECT TOP 1 DATEDIFF(SECOND,{d '1970-01-01'}, TODATETIMEOFFSET(game_start_at, '+09:00')) FROM games ORDER BY game_id DESC) AS game_start_at,"
        //     . "      (SELECT TOP 1 game_end_at FROM games ORDER BY game_id DESC) AS game_end_at,"
        //     . "      (SELECT " . Carbon::now()->timestamp . ") AS now "
        //     . "     FROM settings WHERE id = 1)"
        //     . "  ) AS table1"
        //     . ") AS table2";
        //     //\Log::debug($sql);
        
        //     $game_status = DB::select($sql);
        //     $status = $game_status[0]->status;

        //     //攻撃中以後はセッションIDを発行しない
        //     if ($status < config('const.game_status.start')) {
                Session::regenerate();
                $sid = Session::getId();
          //   }

          // } catch (\Exception $e) {
          //   \Log::critical('[front.initialize] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
          //   $response_data['error'][] = $e->getMessage();
          //   return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
          // }
        
        $response_data['sid'] = $sid;
        return \Response::json($response_data, Response::HTTP_OK);
    }

    /**
     * 
     * 
     */
    public function status(Request $request)
    {
      $response_data = null;

      try {
          $sql = "SELECT "
          . "CASE"
          . "　WHEN game_start_at IS NULL AND game_end_at IS NULL THEN " . config('const.game_status.not')
          . "  WHEN game_end_at IS NOT NULL AND game_start_at + 900 < now THEN " . config('const.game_status.not')
          . "  WHEN now <= attack_start_at THEN " . config('const.game_status.wait')
          . "  WHEN attack_start_at < now AND now <= attack_end_at THEN " . config('const.game_status.start')
          . "  WHEN attack_end_at < now AND now <= result_at THEN " . config('const.game_status.end')
          . "  ELSE " . config('const.game_status.result')
          . " END AS status, attack_start_at, attack_end_at, result_at "
          . "FROM ("
          . "  SELECT (game_start_at + attack_start_time) AS attack_start_at, (game_start_at + attack_end_time) AS attack_end_at, (game_start_at + result_time) AS result_at, game_start_at, game_end_at, now FROM ("
          . "    (SELECT "
          . "      attack_start_time, "
          . "      attack_end_time, "
          . "      result_time, "
          . "      (SELECT TOP 1 DATEDIFF(SECOND,{d '1970-01-01'}, TODATETIMEOFFSET(game_start_at, '+09:00')) FROM games ORDER BY game_id DESC) AS game_start_at,"
          . "      (SELECT TOP 1 game_end_at FROM games ORDER BY game_id DESC) AS game_end_at,"
          . "      (SELECT " . Carbon::now()->timestamp . ") AS now "
          . "     FROM settings WHERE id = 1)"
          . "  ) AS table1"
          . ") AS table2";
          //\Log::debug($sql);
      
          $game_status = DB::select($sql);
          $status = $game_status[0]->status;

          $response_data['status'] = $status;
          $response_data['attack_start_at'] = $status == config('const.game_status.not') ? null : $game_status[0]->attack_start_at;
          $response_data['attack_end_at']   = $status == config('const.game_status.not') ? null : $game_status[0]->attack_end_at;
          $response_data['result_at']       = $status == config('const.game_status.not') ? null : $game_status[0]->result_at;
        
      } catch (\Exception $e) {
          \Log::critical('[front.status] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
          $response_data['error'][] = $e->getMessage();
          return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      return \Response::json($response_data, Response::HTTP_OK);
    }

    /**
     * 
     * 
     */
    public function attack(FrontRequest $request)
    {
      $response_data = null;

      DB::beginTransaction();
      try {
          $sql = "SELECT "
          . "CASE"
          . "　WHEN game_start_at IS NULL AND game_end_at IS NULL THEN " . config('const.game_status.not')
          . "  WHEN game_end_at IS NOT NULL AND game_start_at + 900 < now THEN " . config('const.game_status.not')
          . "  WHEN now <= attack_start_at THEN " . config('const.game_status.wait')
          . "  WHEN attack_start_at < now AND now <= attack_end_at THEN " . config('const.game_status.start')
          . "  WHEN attack_end_at < now AND now <= result_at THEN " . config('const.game_status.end')
          . "  ELSE " . config('const.game_status.result')
          . " END AS status, attack_start_at, attack_end_at, result_at "
          . "FROM ("
          . "  SELECT (game_start_at + attack_start_time) AS attack_start_at, (game_start_at + attack_end_time) AS attack_end_at, (game_start_at + result_time) AS result_at, game_start_at, game_end_at, now FROM ("
          . "    (SELECT "
          . "      attack_start_time, "
          . "      attack_end_time, "
          . "      result_time, "
          . "      (SELECT TOP 1 DATEDIFF(SECOND,{d '1970-01-01'}, TODATETIMEOFFSET(game_start_at, '+09:00')) FROM games ORDER BY game_id DESC) AS game_start_at,"
          . "      (SELECT TOP 1 game_end_at FROM games ORDER BY game_id DESC) AS game_end_at,"
          . "      (SELECT " . Carbon::now()->timestamp . ") AS now "
          . "     FROM settings WHERE id = 1)"
          . "  ) AS table1"
          . ") AS table2";
          //\Log::debug($sql);
        
          $game_status = DB::select($sql);
          $status = $game_status[0]->status;

          //攻撃中のみデータベースへ登録
          if ($status == config('const.game_status.start')) {
            $attack = Attacks::create(
              [
                'sid'         => $request->input('sid'),
                'language'    => $request->input('language'),
                'team'        => $request->input('team'),
                'attacked_at' => Carbon::now()
              ]
            );
          }
          DB::commit();
      } catch (\Exception $e) {
          DB::rollback();
          \Log::critical('[front.attack] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
          $response_data['error'][] = $e->getMessage();
          return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      $response_data['status']   = $status;
      $response_data['sid']      = $request->input('sid');
      $response_data['language'] = $request->input('language');
      $response_data['team']     = $request->input('team');
      return \Response::json($response_data, Response::HTTP_OK);
    }

    /**
     * 
     * 
     */
    public function result(FrontRequest $request)
    {
      $response_data = null;

      try {
          $sql = "SELECT game_at, total_participants, rank, "
          . "CASE WHEN winner = team THEN 1 ELSE 0 END AS result, "
          . "CASE team WHEN 1 THEN a_participants WHEN 2 THEN b_participants END AS team_participants "
          . "FROM "
          . "  (SELECT TOP 1 "
          . "  TODATETIMEOFFSET(game_start_at, '+09:00') AS game_at, "
          . "  total_participants, winner, a_participants, b_participants "
          . "  FROM games ORDER BY game_id DESC) AS table1, "
          . "  (SELECT sid, team, rank FROM summaries WHERE sid = '" . $request->input('sid') . "') AS table2";
          //\Log::debug($sql);

          $game_result = DB::select($sql);
          if (!empty($game_result)) {
            $game_result[0]->game_at = Carbon::parse($game_result[0]->game_at)->format('Y-m-d H:i:s');
            $response_data = $game_result[0];
          }

      } catch (\Exception $e) {
          \Log::critical('[front.attack] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
          $response_data['error'][] = $e->getMessage();
          return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      return \Response::json($response_data, Response::HTTP_OK);
    }
}
