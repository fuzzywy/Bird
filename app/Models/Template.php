<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $connection = 'Bird';
    protected $table = 'template';
    public $timestamps = false;
}