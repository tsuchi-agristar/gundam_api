<?php
return [
    /*
     *--------------------------------------------------------------------------
     * 言語
     *--------------------------------------------------------------------------
     */
    'language' => [
        'japanese' => 1,
        'english' => 2,
        'chinese' => 3,
        'korean' => 4,
    ],
    /*
     *--------------------------------------------------------------------------
     * チーム
     *--------------------------------------------------------------------------
     */
    'team' => [
        'a' => 1,
        'b' => 2,
    ],
    /*
     *--------------------------------------------------------------------------
     * ゲームステータス
     *--------------------------------------------------------------------------
     */
    'game_status' => [
        //キャナルStartシグナル受信前
        'not' => -1,
        //攻撃開始前
        'wait' => 0,
        //攻撃開始
        'start' => 1,
        //攻撃終了
        'end' => 2,
        //勝敗判定完了
        'result' => 3,
    ],
    /*
     *--------------------------------------------------------------------------
     * 平日/休日
     *--------------------------------------------------------------------------
     */
    'day_type' => [
        //平日
        'weekday' => 1,
        //休日
        'holiday' => 2,
    ],
];