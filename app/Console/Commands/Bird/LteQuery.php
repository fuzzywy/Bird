<?php 

namespace App\Console\Commands\Bird;
use App\Console\Commands\Bird\LoadCounters;
use PDO;

class LteQuery{


	public static function queryTemplate($db,$dbServers,$subNets,$counters,$template,$locationDim,$timeDim,$city,$type,$interval){

			$host     = $dbServers[0]['host'];
		    $port     = $dbServers[0]['port'];
		    $dbName   = $dbServers[0]['dbName'];
		    $userName = $dbServers[0]['userName'];
		    $password = $dbServers[0]['password'];
		    $pmDbDSN  = "dblib:host=".$host.":".$port.";dbname=".$dbName;
		    $pmDB     = new PDO($pmDbDSN, $userName, $password);



		    if ($timeDim == "hour") {
            $startTime = date("Y-m-d H:i:s", mktime(date('H') - $interval - 1, 0, 0, date('n'),
                date('j'), date('Y')));
            $endTime = date("Y-m-d H:i:s", mktime(date('H'), 0, 0, date('n'), date('j'),
                date('Y')));
            }else if ($timeDim == "quarter") {
                $startTime = date("Y-m-d H:i:s", mktime(date('H'), date('i') - $interval * 15 -
                    45, 0, date('n'), date('j'), date('Y')));
                $endTime = date("Y-m-d H:i:s", mktime(date('H'), date('i') - $interval * 15 - 30,
                    0, date('n'), date('j'), date('Y')));
            }else if ($timeDim == "day") {
                $startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('n'), date('j') - $interval -
                    1, date('Y')));
                $endTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('n'), date('j'), date('Y')));
            } else if ($timeDim == "month") {
                $startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('n') - $interval - 1, 0,
                    date('Y')));
                $endTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('n'), 0, date('Y')));
            }
            $kpis = self::getKpis($db,$template);
            
		    // [ids] => 4191,4790,4791,1087,4792,4793
		    // [names] => VoLTE无线接通率,VOLTE无线掉线率,VOLTE切换成功率,eSrvcc切换成功率,VOLTE上行丢包率,VOLTE下行丢包率

            $sql = self::createSql($db,$pmDB,$kpis['ids'],$subNets,$counters,$template,$locationDim,$timeDim,$city,$type,$startTime,$endTime);
            // echo $sql;exit;
            $result = $pmDB->query($sql)->fetchall(PDO::FETCH_ASSOC);

            return $result;

	}


	public static function createSql($localDB,$pmDB,$kpiName,$subNetwork,$counters,$template,$locationDim,$timeDim,$city,$type,$startTime,$endTime)
	{
		$resultText ="";
		 //Creat the kpi name group
	    $kpiset = "(" . $kpiName . ")";
	    $location = "('" . str_replace(",", "','", $subNetwork) . "')";
	    $kpis = "";
	    //Query all the formula
	    $queryFormula = "select kpiName,kpiFormula,kpiPrecision,instr('$kpiName',id) as sort from kpiformula where id in " .
	        $kpiset . " order by sort";
	    $index = 0;
	    $tables = array();
	    $tableName = current($tables);
	    //$tableName = ($timeDim == "day" ? ("dc.".current($tables) . "_day") : ("dc.".current($tables) .
	    //"_row"));
	    $selectSQL = "select";
	    $counterMap = array();
	    foreach ($localDB->query($queryFormula) as $row) {
	        $kpi = $row['kpiFormula'];
	        //print_r($kpi . '|');
	        self::parserKPI($kpi, $counters, $counterMap,$timeDim);
	        if($kpi == 'max(pmRrcConnMax)'){
	            $kpi = 'pmRrcConnMax';
	        }
	        $formula = "cast(" . $kpi . " as decimal(18," . $row['kpiPrecision'] .
	            "))";
	        $kpis = $kpis . $formula . " as kpi" . $index . ",";
	        //print_r($kpis.'|');
	        $index++;
	    }
	    //print_r($kpis);
	    $kpis = substr($kpis, 0, strlen($kpis) - 1);
        $citySQL = LoadCounters::getSubnetWork($city);

	    //$citySQL = "substring($tableName.SN,charindex('=',substring($tableName.SN,32,25))+32,charindex(',',substring($tableName.SN,32,25))-charindex('=',substring($tableName.SN,32,25))-1)";
	    $joinSql = "dc." . $tableName;
	    $time_id = ($timeDim=="day")? "date_id" : "datetime_id";

	    $whereSQL = " where $time_id>='$startTime' and $time_id<'$endTime' and $citySQL in $location";
	    //Create select sql.
	    $aggGroupSQL = "";
	    $aggSelectSQL = "";
	    $aggOrderSQL = "";
	    if ($timeDim == "day") {
	        $selectSQL = $selectSQL . " convert(char(10),date_id) as day,";
	        $aggGroupSQL="group by date_id,";
	        $aggSelectSQL = "select AGG_TABLE0.day";
	        $aggOrderSQL = "order by AGG_TABLE0.day";
	        $resultText = $resultText."day,";
	    } else if ($timeDim == "hour") {
	            $selectSQL = $selectSQL .
	                " convert(char(10),date_id) as day,hour_id as hour,";
	            $aggGroupSQL="group by date_id,hour_id,";
	            $aggSelectSQL = "select AGG_TABLE0.day,AGG_TABLE0.hour";
	            $aggOrderSQL = "order by AGG_TABLE0.day,AGG_TABLE0.hour";
	            $resultText = $resultText."day,hour,";
	    } else if ($timeDim == "quarter") {
	                $selectSQL = $selectSQL .
	                    " convert(char(10),date_id) as day,hour_id as hour,min_id as minute,";
	                $aggGroupSQL="group by date_id,hour_id,min_id,";
	                $aggSelectSQL = "select AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute";
	                $aggOrderSQL = "order by AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute";
	                $resultText = $resultText."day,hour,minute,";
	    }
	    if ($locationDim == "city") {
	        $selectSQL = $selectSQL . " '$city' as location,";
	        $loc = "SN";
	        $aggGroupSQL = $aggGroupSQL."location";
	        $aggSelectSQL=$aggSelectSQL.",AGG_TABLE0.location,";
	        $resultText = $resultText."location,";
	    }else if ($locationDim == "subNetwork") {
	            $selectSQL = $selectSQL . "$citySQL as location,";
	            $loc = "SN";
	            $aggGroupSQL = $aggGroupSQL."location";
	            $aggSelectSQL=$aggSelectSQL.",AGG_TABLE0.location,";
	            $resultText = $resultText."location,";
	    }else if ($locationDim == "erbs") {
	                $selectSQL = $selectSQL . "$citySQL as subNet,erbs as location,";
	                $loc = "ERBS";
	                $aggSelectSQL=$aggSelectSQL.",AGG_TABLE0.subNet,AGG_TABLE0.location,";
	                $aggGroupSQL = $aggGroupSQL."SN,location";
	                $resultText = $resultText."subNet,location,";
	    }else if ($locationDim == "cell") {
	                if($type=="NBIOT"){
	                    $selectSQL = $selectSQL . " '$city' as city, $citySQL as subNet,NbIotCell as location,";
	                    $loc = "NbIotCell";
	                }else{
	                   $selectSQL = $selectSQL . " '$city' as city, $citySQL as subNet,EutranCellTDD as location,";
	                     $loc = "EutranCellTDD"; 
	                } 
	                $aggSelectSQL=$aggSelectSQL.",AGG_TABLE0.city,AGG_TABLE0.subNet,AGG_TABLE0.location,";
	                $aggGroupSQL = $aggGroupSQL."SN,location";
	                $resultText = $resultText."city,subNet,location,";
	    } else if ($locationDim == "cellGroup") {
	                    $cellCollection = $_POST['cell'] == "" ? "All" : $_POST['cell'];
	                    $aggSelectSQL = $aggSelectSQL. ",'$cellCollection' as cellGroup,";
	                    $resultText = $resultText."cellGroup,";
	                    $aggGroupSQL = substr($aggGroupSQL,0,strlen($aggGroupSQL)-1);
	    }

	    //Create where sql.
	    $erbs = isset($_POST['erbs']) ? $_POST['erbs'] : "";
	    if ($erbs != "" && $locationDim == "erbs") {
	        $erbs = "('" . str_replace(",", "','", $erbs) . "')";
	        $whereSQL = $whereSQL . " and erbs in " . $erbs;
	    }

	    $cell = isset($_POST['cell']) ? $_POST['cell'] : "";
	    //$cell = "LF32F01A";
	    if ($cell != "" && $locationDim=="cell") {
	        $cell = "('" . str_replace(",", "','", $cell) . "')";
	        $whereSQL = $whereSQL . " and EutranCellTDD in " . $cell;
	    }

	    if ($timeDim == "hour" || $timeDim == "quarter") {
	       	$hour = date("H",strtotime($startTime));
	        $hour = "(" . $hour . ")";
	        $whereSQL = $whereSQL . " and hour_id in " . $hour;
	    }
	    $min = isset($_POST['minute']) ? $_POST['minute'] : "";
	    if ($min != "" && $timeDim == "quarter") {
	        $min = "(" . $min . ")";
	        $whereSQL = $whereSQL . " and min_id in " . $min;
	    }    
	    //$aggGroupSQL = "group by collecttime,location";
	    $tables = array_keys(array_count_values($counterMap));

	    // It should not work in cell dim. especially in kpi backup.
	    // The first step is discard badHandoverCell.
	    if(count($tables) == 1 && $template != 'badHandoverCell') {
	        $currTable = $tables[0];
	        if(trim(substr($currTable,0,strlen($currTable)-4)) == "DC_E_ERBS_EUTRANCELLRELATION"){
	            $aggSelectSQL = $aggSelectSQL ."AGG_TABLE0.relation,";
	            $selectSQL = $selectSQL . "EUtranCellRelation as relation,";
	            $aggGroupSQL = $aggGroupSQL.",relation";
	            $resultText = $resultText . "EUtranCellRelation,";  
	        }
	    }
	    

	    $aggSelectSQL = $aggSelectSQL. $kpis;
	    $tempTableSQL = "";
	    $index = 0;
	    foreach ($tables as $table) {
	        $countersForQuery = array_keys($counterMap, $table);
	        $tableSQL = self::createTempTable($selectSQL, $whereSQL, $table, $countersForQuery,$aggGroupSQL);
	        //print_r($tableSQL);
	        $tableSQL = $tableSQL . "as AGG_TABLE$index ";
	        if ($index == 0) {
	            if ($index != (sizeof($tables) - 1)) {
	                $tableSQL = $tableSQL . " left join";
	            }
	        } else {
	            if($timeDim == "day") {
	                $tableSQL = $tableSQL . "on AGG_TABLE0.day = AGG_TABLE$index.day";
	            } else if ($timeDim == "hour") {
	                $tableSQL = $tableSQL . "on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour";
	            } else if($timeDim == "quarter") {
	                $tableSQL = $tableSQL . "on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour and AGG_TABLE0.minute = AGG_TABLE$index.minute";
	            }

	            if($locationDim != "cellGroup") {
	                $tableSQL = $tableSQL . " and AGG_TABLE0.location = AGG_TABLE$index.location";
	            }
	            //$tableSQL = $tableSQL . "on AGG_TABLE0.collecttime = AGG_TABLE$index.collecttime and AGG_TABLE0.location = AGG_TABLE$index.location";
	            if ($index != (sizeof($tables) - 1)) {
	                $tableSQL = $tableSQL . " left join ";
	            }
	        }
	        $tempTableSQL = $tempTableSQL . $tableSQL;
	        $index++;
	    }

	    //$sql = $aggSelectSQL . " from " . $tempTableSQL .
	        //" $aggOrderSQL";
	    $sql = $aggSelectSQL . " from " . $tempTableSQL;
	    $sql = str_replace("\n", "", $sql);
	    if($type =="FDD"){
	    	$sql = str_replace("TDD", "FDD", $sql);
	    }
	    return $sql;
	}

	/**
	 * getKpis()
	 * 
	 * @param mixed $localDB
	 * @return
	 */
	public static function  getKpis($localDB,$templateName)
	{
	    $queryKpiset = "select elementId from template where templateName='$templateName'";
	    $res = $localDB->query($queryKpiset, PDO::FETCH_ASSOC);
	    $kpis = $res->fetchColumn();
	    $queryKpiName = "select kpiName,instr('$kpis',id) as sort from kpiformula where id in ($kpis) order by sort";
	    $res = $localDB->query($queryKpiName, PDO::FETCH_ASSOC);
	    $kpiNames = "";
	    foreach ($res as $row) {
	        $kpiNames = $kpiNames . $row['kpiName'] . ",";
	    }
	    $kpiNames = substr($kpiNames, 0, strlen($kpiNames) - 1);
	    $result = array();
	    $result['ids'] = $kpis;
	    $result['names'] = $kpiNames;
	    return $result;
	}
 	public static function parserKPI($kpi, $counters, &$counterMap, $timeDim)
	{   
	    $pattern = "/[\(\)\+\*-\/]/";
	    $columns = preg_split($pattern, $kpi);
	    //print_r($columns);
	    foreach ($columns as $column) {
	        $column = trim($column);
	        $counterName = $column;
	        if (stripos($counterName, "pm") === false) {
	            continue;
	        }
	        if (stripos($counterName, "_") !== false) {
	            $elements = explode("_", $counterName);
	            $counterName = $elements[0];
	        }

	        $table = $counters[strtolower($counterName)];
	        $table = ($timeDim == "day") ? $table . "_day" : $table . "_raw";
	        if (!array_key_exists($column, $counterMap)) {
	            $counterMap[$column] = $table;
	        }
	    }
	}
	/**
	 * createTempTable()
	 * 
	 * @param mixed $selectSQL
	 * @param mixed $whereSQL
	 * @param mixed $tableName
	 * @param mixed $counters
	 * @return
	 */
	public static function createTempTable($selectSQL, $whereSQL, $tableName, $counters, $groupSQL)
	{
	    foreach ($counters as $counter) {
	        $counter = trim($counter);
	        $counterName = $counter;
	        if (stripos($counter, "_") !== false) {
	            $elements = explode("_", $counter);
	            $name = $elements[0];
	            $index = $elements[1];
	            $counter = self::convertInternalCounter($name, $index);
	        } else {
	            if($counter == 'pmRrcConnMax'){
	                $counter = "max($counter)";
	            }else{
	                $counter = "sum($counter)";            
	            }
	        }
	        // if($counter == 'pmRrcConnMax'){
	        //     $selectSQL = $selectSQL . $counter . " as 'agg0',";
	        // }else{
	             $selectSQL = $selectSQL . $counter . " as '$counterName',";
	        // }
	        
	    }
	    $selectSQL = substr($selectSQL, 0, strlen($selectSQL) - 1);
	    return "($selectSQL from dc.$tableName $whereSQL $groupSQL)";
	}
	/**
	 * covertInternalCounter()
	 * 
	 * @param mixed $counterName
	 * @param mixed $tableName
	 * @return
	 */
	public static function convertInternalCounter($counterName, $index)
	{
	    $SQL = "sum(case DCVECTOR_INDEX when $index then $counterName else 0 end)";
	    return str_replace("\n", "", $SQL);
	}
}

class SubnetWorkService
{	
	// protected $oss;
    public static function getSubnetWork($oss) {
    	$SN = "";
      	switch ($oss) {
      		case 'wuxiENM':
      			$SN = "substring(substring(SN, 0, charindex(',',SN)-1), 12)";
      			break;
      		case "wuxi1":
      			$SN = "substring(SN, 12, charindex(',', SN)-12)";
      			break;
          case "wuxi":
            $SN = "substring(SN, 12, charindex(',', SN)-12)";
            break;
      		case "suzhou3":
      			$SN = "substring(SN, 12, charindex(',', SN)-12)";
      			break;
      		default:
      			$SN = "substring(SN,charindex('=',substring(SN,32,25))+32,charindex(',',substring(SN,32,25))-charindex('=',substring(SN,32,25))-1)";
      			break;
      	}
      	return $SN;
    }

    public function getSN($format, $oss) {
    	$SN = "";
    	switch ($format) {
    		case 'NBIOT':
    			switch ($oss) {
    				case 'wuxiENM':
    					$SN = "SN  as site";
    					break;
    				case "suzhou3":
    					$SN = "SN  as site";
    					break;
    				default:
    					$SN = "substring(substring(SN,charindex (',', substring(SN, 32, 25)) + 32),11,25) as site";
    					break;
    			}
    			break;
    		default:
    			switch ($oss) {
    				case 'wuxiENM':
    					$SN = "SN  as site";
    					break;
    				case "suzhou3":
    					$SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
    					break;
    				case "wuxi1":
              $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
    					break;
            case "wuxi":
              $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
              break;
    				default:
    					$SN = "substring(substring(SN,charindex (',', substring(SN, 32, 25)) + 32),11,25) as site";
    					break;
    			}
    			break;
    	}
    	return $SN;
    }

    // public function __construct($oss)
    // {	
    // 	$this->oss = $oss;
    // }
}