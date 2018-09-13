<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class NbIotCell extends Model
{
    protected $connection = 'kget';
    protected $table = 'NbIotCell';
    public $timestamps = false;
}
