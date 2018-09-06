<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
	protected $connection = 'bird';
    protected $table = 'networks';
    public $timestamps = false;
    
}
