<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summaries extends Model
{
    protected $table = 'summaries';

    protected $primaryKey = 'id';

    protected $fillable = [
        'sid',
        'language',
        'team',
        'attack',
        'rank'
    ];
    
    public $timestamps = false;
}
