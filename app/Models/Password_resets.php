<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Password_resets extends Model
{
	protected $connection = 'Bird';
    protected $table = 'password_resets';
    public $timestamps = false;
    
}
