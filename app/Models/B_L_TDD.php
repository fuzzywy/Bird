<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B_L_TDD extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_L_TDD';
}
