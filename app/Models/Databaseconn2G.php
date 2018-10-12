<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Databaseconn2G extends Model
{
    public $timestamps = false;
    protected $connection = 'Bird';
    protected $table = 'databaseconn_2G';
    protected $fillable = ['connName', 'cityChinese', 'port', 'host', 'dbName', 'userName', 'password'];
}
