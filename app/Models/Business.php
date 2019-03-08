<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'business';
}