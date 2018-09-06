<?php 
namespace App\Console\Commands\Bird;

use App\Models\Mongs\Databaseconns;
use App\Console\Commands\Bird\LoadCounters;
use App\Console\Commands\Bird\LteQuery;
use PDO;
class LteBackup{

	public function __construct($template,$locationDim,$timeDim,$city,$type,$db,$interval=null)
	{
		$this->template    = $template;
		$this->locationDim = $locationDim;
		$this->timeDim     = $timeDim;
		$this->city 	   = $city;
		$this->type 	   = $type;
		$this->db          = $db;
		$this->interval    = $interval;
		$this->query();
	}

	public function query()
	{
		$counters = LoadCounters::loadcounter($this->type);
		$dbServers = Databaseconns::select("connName","host","port","dbName","userName","password")->where("connName",$this->city)->get()->toArray();

		$host     = $dbServers[0]['host'];
	    $port     = $dbServers[0]['port'];
	    $dbName   = $dbServers[0]['dbName'];
	    $userName = $dbServers[0]['userName'];
	    $password = $dbServers[0]['password'];
	    $pmDbDSN  = "dblib:host=".$host.":".$port.";dbname=".$dbName;
	    $pmDB     = new PDO($pmDbDSN, $userName, $password);
	    if($pmDB){
			$subNets  = LoadCounters::getSubNets($this->db,$this->city,$this->type);

			if(!$subNets){
				return;
			}
		    $result = LteQuery::queryTemplate($this->db,$pmDB,$subNets,$counters,$this->template,$this->locationDim,$this->timeDim,$this->city,$this->type,$this->interval);
		     if($result){
		    	self::loadData($this->db,$result,$this->template);
		    }
	
	    }
	}

	public function loadData($db,$result,$template)
	{
		foreach ($result as $key => $value) {
			$str = "'',";

			foreach ($value as $k => $v) {
				$str.="'".$v."',";
			}
			$str =rtrim($str,",");
			$sql = "insert into ENIQ.$template value($str)";
			$db->query($sql);

		}
	}
}