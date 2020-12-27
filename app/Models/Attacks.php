<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attacks extends Model
{
    protected $table = 'attacks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'sid',
        'language',
        'team',
        'attacked_at'
    ];
    
    public $timestamps = false;
}
