<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LTE_FDD_DAY extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_LTE_FDD_DAY';
}
