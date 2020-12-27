<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'difficulty',
        'superior_attack_rate',
        'judge_seconds',
        'fixed_attacks_percent',
        'fixed_attacks_down_percent',
        'attack_start_time',
        'attack_end_time',
        'result_time'
    ];
    
    public $timestamps = false;
}
