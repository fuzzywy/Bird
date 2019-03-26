<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Networks extends Model
{
	protected $connection = 'Bird';
    protected $table = 'networks';
    public $timestamps = false;
    
}
