<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Games;

class AdminController extends Controller
{
    /**
     * 
     * 
     */
    public function result(Request $request)
    {
        $response_data = null;
        $sid = null;

        try {
            $games = Games::whereNotNull('game_end_at')->orderBy('game_id', 'desc')->get();
            $response_data = $games;

        } catch (\Exception $e) {
            \Log::critical('[admin.result] ' . __METHOD__ . '::' . (__LINE__) . PHP_EOL . $e);
            $response_data['error'][] = $e->getMessage();
            return \Response::json($response_data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return \Response::json($response_data, Response::HTTP_OK);
    }
}
