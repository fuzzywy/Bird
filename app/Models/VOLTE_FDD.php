<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VOLTE_FDD extends Model
{
   public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_VOLTE_FDD';
}
