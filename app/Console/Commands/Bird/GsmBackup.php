<?php 
namespace App\Console\Commands\Bird;
 
use App\Console\Commands\Bird\LoadCounters;
use App\Console\Commands\Bird\GSMQuery;
use App\Models\Databaseconns;
use App\Models\Databaseconn2G;
use PDO;
class GsmBackup{

 	public function __construct($template,$locationDim,$timeDim,$city,$db)
 	{
 		$this->template    = $template;
 		$this->locationDim = $locationDim;
 		$this->timeDim     = $timeDim;
 		$this->city        = $city;
 		$this->db          = $db;
 		$this->query();
 	}

 	public function query()
 	{
 		$counters = LoadCounters::loadcounter("GSM");

 		$dbinfo  = Databaseconn2G::select("host","port","dbName","userName","password")->where("connName",$this->city)->get()->toArray();
 		if(!$dbinfo){
 			return;
 		}
 		$host     = $dbinfo[0]['host'];
	    $port     = $dbinfo[0]['port'];
	    $dbName   = $dbinfo[0]['dbName'];
	    $userName = $dbinfo[0]['userName'];
	    $password = $dbinfo[0]['password'];
	    // $pmDbDSN  = "dblib:host=".$host.":".$port.";dbname=".$dbName;
        $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;

	    try {
	    	$pmDB     = new PDO($pmDbDSN, $userName, $password);
	    } catch (\Exception $e) {
	    	return;
	    }

	    if($pmDB){
	    	 // $db = new PDO("mysql:host=10.39.148.186;dbname=mongs", 'root', 'mongs');
		     if($this->timeDim == "hour") {
		            $startTime = date("Y-m-d");
		            $endTime = date("Y-m-d");
		        }elseif ($this->timeDim  == "quarter") {
		                $startTime = date("Y-m-d H:i:s", mktime(date('H'), date('i') - $interval * 15 -
		                    45, 0, date('n'), date('j'), date('Y')));
		                $endTime = date("Y-m-d H:i:s", mktime(date('H'), date('i') - $interval * 15 - 30,
		                    0, date('n'), date('j'), date('Y')));
		        }elseif ($this->timeDim  == "day") {
		                $startTime = date("Y-m-d",strtotime("-1 day"));
		                $endTime = date("Y-m-d");
		        }
		    $result = GSMQuery::queryTemplate($this->db,$pmDB,$counters,$this->template,$this->locationDim,$this->timeDim,$this->city,$startTime,$endTime);
		    if($result){
		    	self::loadData($this->db,$result,$this->template);
		    }
	    }

 	}

 	public function loadData($db,$result,$template)
	{
		foreach ($result as $key => $value) {
			$str = "null,";

			foreach ($value as $k => $v) {
				$str.="'".$v."',";
			}
			$str =rtrim($str,",");
			$sql = "insert into $template value($str)";
			$db->query($sql);

		}
	}
}