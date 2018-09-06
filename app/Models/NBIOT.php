<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBIOT extends Model
{
   public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_NBIOT';
}
