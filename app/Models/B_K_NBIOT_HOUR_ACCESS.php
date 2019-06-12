<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B_K_NBIOT_HOUR_ACCESS extends Model
{
    public $timestamps = false;
    protected $connection = 'ENIQAggregation';
    protected $table = 'B_K_NBIOT_HOUR_ACCESS';
}
