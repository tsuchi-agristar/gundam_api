<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    protected $table = 'games';

    protected $primaryKey = 'game_id';

    protected $fillable = [
        'game_start_at',
        'game_end_at',
        'winner',
        'total_participants',
        'total_launches',
        'japan',
        'korea',
        'english',
        'chinese',
        'a_participants',
        'a_launches',
        'a_score',
        'b_participants',
        'b_launches',
        'b_score',
        'difficulty'
    ];
    
    public $timestamps = false;
}
