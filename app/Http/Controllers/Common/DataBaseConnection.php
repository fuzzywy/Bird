<?php

/**
 * DataBaseConnection.php
 *
 * @category Common
 * @package  App\Http\Controllers\Common
 * @author   ericsson <genius@ericsson.com>
 * @license  MIT License
 * @link     https://laravel.com/docs/5.4/controllers
 */
namespace App\Http\Controllers\Common;

use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PDO;

/**
 * 工具类
 * Class DataBaseConnection
 *
 * @category Common
 * @package  App\Http\Controllers\Common
 * @author   ericsson <genius@ericsson.com>
 * @license  MIT License
 * @link     https://laravel.com/docs/5.4/controllers
 */
class DataBaseConnection
{    /**
     * 获取数据库连接句柄
     *
     * @param string $db 数据库名
     * 
     * @param null $dbName 数据库名
     *
     * @return PDO
     */
    public function getDB($db, $dbName = null)
    {
      if($dbName != null){
            Config::set("database.connections.$db.database",$dbName);
        }
        return DB::connection($db)->getPdo();

        // return $pdo;

    }//end getDB()

    /**
     * 获取城市中文名
     *@return string
     */
    public function getConnName($db,$city){
        $sql = "select connName from city where cityChinese='$city' limit 1";
        $res = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
        foreach ($res as $key => $value) {
            $connName = $value['connName'];
        }
        return $connName;

    }//end getConnName()
}//end class
