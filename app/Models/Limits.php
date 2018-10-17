<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Limits extends Model
{
   public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'Limits';
}
