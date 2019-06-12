<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B_K_LTE_FDD_HOUR_LOST extends Model
{
    public $timestamps = false;
    protected $connection = 'ENIQAggregation';
    protected $table = 'B_K_LTE_FDD_HOUR_LOST';
}
