<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B_K_GSM_HOUR extends Model
{
   public $timestamps = false;
    protected $connection = 'ENIQAggregation';
    protected $table = 'B_K_GSM_HOUR';
}
