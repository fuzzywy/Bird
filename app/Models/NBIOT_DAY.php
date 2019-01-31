<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBIOT_DAY extends Model
{
   	public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_NBIOT_DAY';
}
