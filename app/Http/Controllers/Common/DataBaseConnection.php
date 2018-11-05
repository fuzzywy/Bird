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
use App\Models\Databaseconns;
use PDO;
use APP;
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
        if (App::isLocale('en')) {
            return $city;
        }
// var_dump(App::isLocale('en'));exit;
        $sql = "select connName from city where cityChinese='$city' limit 1";
        $res = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
        foreach ($res as $key => $value) {
            $connName = $value['connName'];
        }
        return $connName;

    }//end getConnName()

    /**
     * 获取最新的kget时间
     */
    public function getKgetTime(){

        $dbn = $this->getDB('mongs');
        $sql = "select taskName from task where taskName like 'kget1_____' order by endTime desc limit 1 ";
        $res = $dbn->query($sql)->fetch(PDO::FETCH_ASSOC);
        
        return $res['taskName']; 
    }

    public function getSubNetsArr($type,$cityChinese){

        switch($type){
            case "TDD":
            $result = Databaseconns::select("subNetwork as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
            case "FDD":
            $result = Databaseconns::select("subNetworkFDD as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
            case "NBIOT":
            $result = Databaseconns::select("subNetworkNbiot as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
        }

        $subnet =array();
        foreach ($result as $key => $value) {
            $subnet= array_merge($subnet,explode(",", $value['subnet']));
        }
        return array_filter($subnet);
    }
        public function getSubNetsStr($type,$cityChinese){

        switch($type){
            case "TDD":
            $result = Databaseconns::select("subNetwork as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
            case "FDD":
            $result = Databaseconns::select("subNetworkFDD as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
            case "NBIOT":
            $result = Databaseconns::select("subNetworkNbiot as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
        }

        $subnet ="";
        foreach ($result as $key => $value) {
            $array = array_filter(explode(",", $value['subnet']));
            if($array){
                foreach ($array as $k => $v) {
                    $subnet.="'".$v."',";
                }
            }
           
        }
        $subnet = rtrim($subnet,",");
        return $subnet;
    }

     public function getSubNetsByconnName($type,$connName){

        switch($type){
            case "TDD":
            $result = Databaseconns::select("subNetwork as subnet")->where("connName",$connName)->get()->toArray();
            break;
            case "FDD":
            $result = Databaseconns::select("subNetworkFDD as subnet")->where("connName",$connName)->get()->toArray();
            break;
            case "NBIOT":
            $result = Databaseconns::select("subNetworkNbiot as subnet")->where("connName",$connName)->get()->toArray();
            break;
        }
        $subnet = "";
        if($result){
            $array = array_filter(explode(",", $result[0]['subnet']));
            if($array){
                foreach ($array as $k => $v) {
                    $subnet.="'".$v."',";
                }
            }
            $subnet = rtrim($subnet,",");
        }
        return $subnet;
    }

    public  function getSN($oss) {
        $SN = "";
        switch ($oss) {
            case 'wuxiENM':
            case "zhenjiang":
                $SN = "substring(substring(SN, 0, charindex(',',SN)-1), 12)";
                break;
            case "wuxi1":
                $SN = "substring(SN, 12, charindex(',', SN)-12)";
                break;
          case "wuxi":
            $SN = "substring(SN, 12, charindex(',', SN)-12)";
            break;
            case "zhenjiang1":
                $SN = "substring(substring(SN, charindex(',', SN)+12), 0, charindex(',', substring(SN, charindex(',', SN)+12))-1)";
                break;
            case "suzhou3":
                $SN = "substring(SN, 12, charindex(',', SN)-12)";
                break;
            case "changzhou3":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            case "suzhou4":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            default:
                $SN = "substring(SN,charindex('=',substring(SN,32,25))+32,charindex(',',substring(SN,32,25))-charindex('=',substring(SN,32,25))-1)";
                break;
        }
        return $SN;
    }
    public  function getDC($oss) {
        $dc = "";

        switch ($oss){
            case "qingyuan":
            $dc = "";
            break;
            default:
            $dc = "dc.";
        }
        return $dc;
    }
    /**
     * Check数据表是否存在
     *
     * @param string $schema 数据库名
     * 
     * @param string $table 数据表名
     *
     * @return boolean
     */
    public function tableIfExists($schema, $table)
    {
        $dbn = $this->getDB('mongs', 'information_schema');
        $sql = "select TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$schema' AND TABLE_NAME='$table'";
        $rs = $dbn->query($sql)->fetchcolumn();
        if ($rs) {
            return true;
        } else {
            return false;
        }

    }//end tableIfExists()


    public function getCddDB(){
        $dbn = $this->getDB('mongs', 'information_schema');
        $sql = "select TABLE_SCHEMA FROM information_schema.TABLES WHERE TABLE_SCHEMA like 'CDD_20______' order by TABLE_SCHEMA desc limit 1";
        $res = $dbn->query($sql)->fetchall(PDO::FETCH_ASSOC);
        return ($res[0]['TABLE_SCHEMA']);

    }

}//end class
