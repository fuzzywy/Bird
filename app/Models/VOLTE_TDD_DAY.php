<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VOLTE_TDD_DAY extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_VOLTE_TDD_DAY';
}
