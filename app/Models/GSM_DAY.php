<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GSM_DAY extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_GSM_DAY';
}
