<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LTE_TDD extends Model
{
   public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_LTE_TDD';
}
