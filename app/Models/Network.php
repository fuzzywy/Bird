<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
	protected $connection = 'Bird';
    protected $table = 'network';
    public $timestamps = false;
    
}
