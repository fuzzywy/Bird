<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B_S_GSM extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'B_S_GSM';
}
