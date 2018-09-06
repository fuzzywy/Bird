<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Databaseconns extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'databaseconn';
    protected $fillable = ['connName', 'cityChinese'];//
}
