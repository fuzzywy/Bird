<?php 
namespace App\Console\Commands\Bird;

use App\Models\Databaseconns;
use App\Console\Commands\Bird\LoadCounters;
use App\Console\Commands\Bird\LteQuery;
use App\Http\Controllers\Common\DataBaseConnection;
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
		$dbServers = Databaseconns::select("connName","host","port","dbName","userName","password")->where("connName","like",$this->city."%")->get()->toArray();

		$timeDim = $this->timeDim;
		$interval = $this->interval?$this->interval:'0';

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



		if(count($dbServers)>=2){
	      $OssGetTogether = new OssGetTogether();
	      $result = $OssGetTogether->is_2oss($this->db,$dbServers,$counters,$this->template,$this->locationDim,$this->timeDim,$this->type,$startTime,$endTime);
		}else{
		  $subNets  = LoadCounters::getSubNets($this->db,$this->city,$this->type);
         if(!$subNets){
            return;
         }
		  $result = LteQuery::queryTemplate($this->db,$dbServers,$subNets,$counters,$this->template,$this->locationDim,$this->timeDim,$this->city,$this->type,$this->interval);
		}
        // print_r($result);exit;
        if($result){
            if($result){
                self::loadData($this->db,$result,$this->template);
            }
        }


	}
    public function loadData($db,$result,$template)
    {
        foreach ($result as $key => $value) {
            $value['location'] =preg_replace('|[0-9/]+|','',$value['location']);
            $str = "null,";

            foreach ($value as $k => $v) {
                if($v==''){
                    $v=0;
                }
                $str.="'".$v."',";
            }
            $str =rtrim($str,",");
            $sql = "insert into $template value($str)";
            $db->query($sql);

        }
    }
}
class OssGetTogether{

	public function is_2oss($db,$dbServers,$counters,$template,$locationDim,$timeDim,$type,$startTime,$endTime){
		$return = [];
        $items = [];
        $columnMax = [];
        if ($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup' || $locationDim == 'cellGroup') {
            $mergeArr = [];
            foreach ($dbServers as $dbinfo) {
            	$host = $dbinfo['host'];
            	$port = $dbinfo['port'];
            	$dbName = $dbinfo['dbName'];
            	$userName = $dbinfo['userName'];
            	$password = $dbinfo['password'];
            	$city = $dbinfo['connName'];
            	$pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;
            	$subNets = LoadCounters::getSubNets($db,$city,$type);
                 try {
                // $pmDB = sybase_connect($pmDbDSN, $userName, $password);
                  $pmDB = new PDO($pmDbDSN, $userName, $password);
                if ($pmDB == null) {
                    continue;
                    }
                } catch (\Exception $e) {
                   continue;
                }
            	$ossGetTogether = $this->queryTemplate($db, $pmDB, $counters, $template,$timeDim, $locationDim, $startTime, $endTime, $city, $subNets,$type,$mergeArr);
                //     //合并数据
                    if ($ossGetTogether['flag'] != 0) {
                        array_push($mergeArr, $ossGetTogether['return']);
                        $columnMax = $ossGetTogether['columnMax'];
                    }
             

            }
            $columnMax = array_unique($columnMax);
            $getTextAndRows = [];
            // if(($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup'|| $locationDim == 'cellGroup') && ($timeDim == 'day'||$timeDim == 'daygroup'|| $timeDim == 'hour' || $timeDim == 'hourgroup' || $timeDim == 'quarter')) {
            //合并pm值
            $testArr = [];   

            //如果只有一个oss或者只有一个oss有数据的话
            if (count($mergeArr) == 1 && count($mergeArr)!=0) {  
                for ($i=0; $i < count($mergeArr[0]); $i++) {
                    foreach ($mergeArr[0][$i] as $key => $value) {
                        if ($value == false || $value == '') {
                            $value = 0;
                        }
                        if ($key == 'day' || $key == 'location' || $key == 'hour' || $key == 'minute') {
                            $testArr[$i][$key] = $value;
                        } elseif (!array_key_exists($key, $testArr[$i])) {
                            $testArr[$i][$key] = $value;
                        } else {
                            $testArr[$i][$key] = $mergeArr[$j][$i][$key] + $testArr[$i][$key];
                        }
                    }
                }
            } elseif( count($mergeArr)!=0) {  //如果两个或两个以上的oss有数据的话
                for ($i=0; $i < count($mergeArr[0]); $i++) { 
                    for ($j=0; $j < count($mergeArr); $j++) { 
                        
                        foreach ($mergeArr[0][$i] as $key => $value) {
                            $flag = 0;
                            if ($value == false || $value == '') {
                                $value = 0;
                            }
                            if ($key == 'day' || $key == 'location' || $key == 'hour' || $key == 'minute') {
                                $testArr[$i][$key] = $value;
                            } elseif (!array_key_exists($key, $testArr[$i])){
                                $testArr[$i][$key] = $value;
                            } else {
                                foreach ($columnMax as $k1 => $v1) {
                                    if (strtolower($v1) == $key) {
                                         // echo $v1.'=';echo $key;
                                        $flag = 1;
                                    } else {
                                        continue;
                                    }
                                }
                                if ($flag == 1) {
                                    $testArr[$i][$key] = max($mergeArr[$j][$i][$key], $testArr[$i][$key]);
                                } else {
                                    $testArr[$i][$key] = $mergeArr[$j][$i][$key] + $testArr[$i][$key];
                                }  
                            }
                        }                            
                    }
                }     
            }
                          
            // }
            //获取合并计算后的数据
            $getTextAndRows = $this->getTextAndRows($db, $template, $testArr, $locationDim, $timeDim, $type);

            return $getTextAndRows["newFormula"];
        } 
	}

	public function queryTemplate($localDB, $pmDB, $counters, $template,$timeDim, $locationDim, $startTime,$endTime, $city, $subNets, $format,  $mergeArr)
    {
        $result         = array();
     	$aggTypes = $this->loadAggTypes();
        $kpis           = $this->getKpis($localDB, $template);
        $result['text'] = $kpis['names'];
        $sqlArr            = $this->createSQL($localDB, $pmDB, $kpis['ids'], $counters, $timeDim, $locationDim, $startTime, $endTime, $city, $subNets, $resultText, $aggTypes,$format);
        // print_r($kpis);
        // print_r($sql);return;
        $sql = $sqlArr['sql'];
        // echo $sql;exit;
        try {
            //将地市文件放到数组里 ---2017-11-21
            $res = $pmDB->query($sql);
        } catch (Exception $e) {
            return 'Caught exception: '.$e->getMessage();
        }//end try
        $rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $result['flag'] = 1;
        if (count($rows) == 0) {
            $result['flag'] = 0;
        }
        $return = [];
        foreach ($rows as $key => $value) {
            array_push($return, $value);
        }
        // print_r($rows);
        // return $return;
        $result['return'] = $return;
        $result['columnMax'] = $sqlArr['columnMax'];
        return $result;
    }
        protected function createSQL($localDB, $pmDB, $kpiName, $counters, $timeDim, $locationDim,
        $startTime, $endTime, $city, $subNetwork, &$resultText, $aggTypes,$format
    ) {
        // var_dump($timeDim);
        // var_dump($locationDim);
        // return;
        $kpiset        = "(".$kpiName.")";
        $subNetwork1   = $subNetwork;    
        $location    = str_replace("[", "", $subNetwork1);
        $location    = str_replace("]", "", $location);
        $subNetwork2    = str_replace('"', "", $location);
        $location      = "('".str_replace(",", "','", $subNetwork2)."')";
        $kpis          = "";
        $kpiNameStr    = $kpiName.',';
        $queryFormula  = "select kpiName,kpiFormula,kpiPrecision,instr('$kpiNameStr',CONCAT(id,',')) as sort from kpiformula where id in ".$kpiset." order by sort";
        $index         = 0;
        $selectSQL     = "select";
        $counterMap    = array();
        $nosum_map     = array();
        $pattern_nosum = "/(max|min|avg)\((.*)\)/";
        $matches       = array();
        
        $columnMax = [];

        foreach ($localDB->query($queryFormula) as $row) {
            $kpi = $row['kpiFormula'];
            $this->parserKPI($kpi, $counters, $counterMap, $nosum_map,$format,$timeDim);
            $kpi = $row['kpiFormula'];
            $pattern       = "/[\(\)\+\*-\/]/";
            $columns       = preg_split($pattern, $kpi);

            $flag = 0;
            foreach ($columns as $column) {
                $column      = trim($column);
                $counterName = $column;

                if ($counterName == 'max' || $counterName == 'MAX') {
                    $flag = 1;
                }

                if (stripos($counterName, "pm") === false) {
                    continue;
                }
                if ($flag == 1) {
                    array_push($columnMax, $counterName);
                }
                $kpis = $kpis . strtolower($counterName) . ",";
            }
        }
        $kpis    = substr($kpis, 0, (strlen($kpis) - 1));

        $citySQL =  $citySQL = LoadCounters::getSubnetWork($city);

        $time_id = "date_id";

        $whereSQL = " where $time_id>='$startTime' and $time_id<='$endTime' and $citySQL in $location";

        $aggGroupSQL  = "";
        $aggSelectSQL = "";
        $aggOrderSQL  = "";

        if ($timeDim == "daygroup") {
            $selectSQL    = $selectSQL." 'ALLDAY' as day,";
            $aggGroupSQL  = "group by DAY,";
            $aggSelectSQL = "select AGG_TABLE0.day";
            $resultText   = $resultText."day,";
        } else if ($timeDim == "day") {
            $selectSQL    = $selectSQL." convert(char(10),date_id) as day,";
            $aggSelectSQL = "select AGG_TABLE0.day";
            $resultText   = $resultText."day,";

            $aggGroupSQL = "group by date_id,";
            $aggOrderSQL = "order by AGG_TABLE0.day";
        } else if ($timeDim == "hour") {
            $selectSQL    = $selectSQL." convert(char(10),date_id) as day,hour_id as hour,";
            $aggSelectSQL = "select AGG_TABLE0.day,AGG_TABLE0.hour";
            $resultText   = $resultText."day,hour,";

            $aggGroupSQL = "group by date_id,hour_id,";
            $aggOrderSQL = "order by AGG_TABLE0.day,AGG_TABLE0.hour";
        } else if ($timeDim == "quarter") {
            $selectSQL    = $selectSQL." convert(char,date_id) as day,hour_id as hour,min_id as minute,";
            $aggGroupSQL  = "group by date_id,hour_id,min_id,";
            $aggOrderSQL  = "order by AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute";
            $aggSelectSQL = "select AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute";
            $resultText   = $resultText."day,hour,minute,";
        }

        if ($locationDim == "city") {
            $selectSQL    = $selectSQL." '$city' as location,";
            $aggGroupSQL  = $aggGroupSQL."location";
            $aggSelectSQL = $aggSelectSQL.",AGG_TABLE0.location,";
            $resultText   = $resultText."location,";
        } else if ($locationDim == "subNetworkGroup") {
            $selectSQL    = $selectSQL."\"$subNetwork\" as location,";
            $aggGroupSQL  = $aggGroupSQL."location";
            $aggSelectSQL = $aggSelectSQL.",AGG_TABLE0.location,";
            $resultText   = $resultText."location,";
        }
 		

        
	    if ($timeDim == "hour" || $timeDim == "quarter") {
	    	$hour = date("H",strtotime($startTime));
	        $hour = "(" . $hour . ")";
	        $whereSQL = $whereSQL . " and hour_id in " . $hour;
	    }
        $tables            = array_keys(array_count_values($counterMap));
        // print_r($tables);return;

        $aggSelectSQL = $aggSelectSQL.$kpis;
        // print_r($aggSelectSQL);return;
        $tempTableSQL = "";
        $index        = 0;
        foreach ($tables as $table) {
            $countersForQuery = array_keys($counterMap, $table);
            $tableSQL         = $this->createTempTable($locationDim, $selectSQL, $whereSQL, $table, $countersForQuery, $aggGroupSQL, $aggTypes, $nosum_map, $counterMap, $format, $resultText,$city);
            $tableSQL         = $tableSQL."as AGG_TABLE$index ";
            if ($index == 0) {
                if ($index != (sizeof($tables) - 1)) {
                    $tableSQL = $tableSQL." left join";
                }
            } else {
                if ($timeDim == "daygroup") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.DAY = AGG_TABLE$index.DAY";
                } else if ($timeDim == "day" || $timeDim == 'daygroup') {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day";
                } else if ($timeDim == "hour") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour";
                } else if ($timeDim == "hourgroup") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour";
                } else if ($timeDim == "quarter") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour and AGG_TABLE0.minute = AGG_TABLE$index.minute";
                }

                if ($locationDim == "cellGroup" || $timeDim == "daygroup"  || $locationDim == "erbsGroup") {
                    $tableSQL = $tableSQL;
                } else {
                    $tableSQL = $tableSQL." and AGG_TABLE0.location = AGG_TABLE$index.location";
                }

                if ($index != (sizeof($tables) - 1)) {
                    $tableSQL = $tableSQL." left join ";
                }
            }//end if
            $tempTableSQL = $tempTableSQL.$tableSQL;
            $index++;
        }//end foreach
        $sql = $aggSelectSQL." from ".$tempTableSQL." $aggOrderSQL";
        $sql = str_replace("\n", "", $sql);
        if($format =="FDD"){
            $sql = str_replace("TDD", "FDD", $sql);
        }
        $return['sql'] = $sql;    
        $return['columnMax'] = $columnMax;
        // print_r($sql);return;
        // print_r($return);
        return $return;
        // return $sql;

    }//end createSQL()

    function createTempTable($locationDim, $selectSQL, $whereSQL, $tableName, $counters, $groupSQL, $aggTypes, $nosum_map, $counterMap, $format, &$resultText,$city
    ) {
        $tables = array_keys(array_count_values($counterMap));
        $flag   = 'true';
        $flag1  = 'true';
        foreach ($tables as $table) {
            if (trim(substr($table, 0, (strlen($table) - 4))) == 'DC_E_CPP_GIGABITETHERNET' || trim(substr($table, 0, (strlen($table) - 4))) == 'DC_E_CPP_PLUGINUNIT_V') {
                $flag = 'false';
            }

            if (trim(substr($table, 0, (strlen($table) - 4))) == 'DC_E_ERBS_SECTORCARRIER' || trim(substr($table, 0, (strlen($table) - 4))) == 'DC_E_CPP_PROCESSORLOAD_V' || trim(substr($table, 0, (strlen($table) - 4))) == 'DC_E_ERBS_ENODEBFUNCTION' || trim(substr($table, 0, (strlen($table) - 4))) == 'DC_E_ERBS_BBPROCESSINGRESOURCE') {
                $flag1 = 'false';
            }
        }

        // if (!$local_flag) {
            // if ($format == 'TDD') {
            //     if ($flag == 'false') {
            //         $selectSQL .= "COUNT(DISTINCT(ERBS)) AS cellNum,";
            //         $resultText = str_replace('cellNum', 'erbsNum', $resultText);
            //     } else {
            //         if ($flag1 == 'false') {
            //             $selectSQL .= "COUNT(DISTINCT(ERBS)) AS cellNum,";
            //             $resultText = str_replace('cellNum', 'erbsNum', $resultText);
            //         } else {
            //             $selectSQL .= "COUNT(DISTINCT(EutranCellTDD)) AS cellNum,";
            //         }
            //     }
            // } else if ($format == 'FDD'||$format=='NBIOT') {
            //     $selectSQL .= "COUNT(DISTINCT(ERBS)) AS cellNum,";
            //     $resultText = str_replace('cellNum', 'erbsNum', $resultText);
            // }
        // }
            // print_r($resultText);return;


        $pattern_nosum = "/(max|min|avg)\((.*)\)/";
        $i=0;
        foreach ($counters as $counter) {
            $counter     = trim($counter);
            $counterName = $counter;

            if (preg_match($pattern_nosum, $counter, $match)) {
                // $counterName = $nosum_map[$counter];
                $minmaxavg = $match[1];
                $pmCounter = $match[2];
                if (stripos($pmCounter, "_") !== false) {
                    $elements = explode("_", $pmCounter);
                    $name     = $elements[0];
                    $index    = $elements[1];
                    $counter  = $this->convertInternalCounter_minmaxavg($minmaxavg, $name, $index);
                    // str_replace(search, replace, subject)
                    $selectSQL = $selectSQL.$counter." as agg".$i++.",";
                    // print_r($selectSQL);return;
                } else {
                    $pattern_nosum = "/(max|min|avg)\((.*)\)/";
                    preg_match($pattern_nosum, $counter, $match);
                    // $counterName = $nosum_map[$counter];
                    $counterName = $match[2];
                    // $counterName = $counterName.'('.$match[2].')';

                    $selectSQL = $selectSQL.$counter." as '$counterName',";
                }
                
            } else if (stripos($counter, "_") !== false) {
                $elements = explode("_", $counter);
                $name     = $elements[0];
                $index    = $elements[1];
                $counter  = $this->convertInternalCounter($name, $index);
                $selectSQL = $selectSQL.$counter." as '$counterName',";
            } else {
                $aggType = $this->getAggType($aggTypes, $counter);
                $counter = "$aggType($counter)";
                $selectSQL = $selectSQL.$counter." as '$counterName',";
            }

            // $selectSQL = $selectSQL.$counter." as '$counterName',";
        }

        $selectSQL = substr($selectSQL, 0, (strlen($selectSQL) - 1));
        // if (!$local_flag) {
            $dbc = new DataBaseConnection();
            $dc = $dbc->getDC($city);
            return "($selectSQL from ".$dc."$tableName $whereSQL $groupSQL)";
        // } else {
        //     $tableName = substr("$tableName", 0, strripos("$tableName", "_"));
        //     if ($locationDim == 'city') {
        //         $tableName = trim($tableName)."_HOUR_CITY";
        //     } else if ($locationDim == 'subNetwork' || $locationDim == 'subNetworkGroup') {
        //         $tableName = trim($tableName)."_HOUR_SUBNET";
        //     } else if ($locationDim == 'erbs') {
        //         $tableName = trim($tableName)."_HOUR_ERBS";
        //     } else {
        //         $tableName = trim($tableName)."_HOUR";
        //     }

        //     return "($selectSQL from $tableName $whereSQL $groupSQL)";
        // }

    }//end createTempTable()
    public function getKpis($localDB, $templateName)
    {
        $queryKpiset  = "select elementId from template where templateName='$templateName'";
        $res          = $localDB->query($queryKpiset);
        $kpis         = $res->fetchColumn();
        $kpisStr      = $kpis.',';
        $queryKpiName = "select kpiName,instr('$kpisStr',CONCAT(',',id,',')) as sort from kpiformula where id in ($kpis) order by sort";
        $res          = $localDB->query($queryKpiName);
        $kpiNames     = "";
        foreach ($res as $row) {
            $kpiNames = $kpiNames.$row['kpiName'].",";
        }

        $kpiNames        = substr($kpiNames, 0, (strlen($kpiNames) - 1));
        $result          = array();
        $result['ids']   = $kpis;
        $result['names'] = $kpiNames;
        return $result;
    }
    protected function getAggType($aggTypes, $counterName)
    {
        if (!array_key_exists($counterName, $aggTypes)) {
            return "sum";
        }
        return trim($aggTypes[$counterName]);

    }//end getAggType()

    protected function convertInternalCounter($counterName, $index)
    {
        $SQL = "sum(case DCVECTOR_INDEX when $index then $counterName else 0 end)";
        return str_replace("\n", "", $SQL);

    }//end convertInternalCounter()

    protected function convertInternalCounter_minmaxavg($minmaxavg, $counterName, $index)
    {
        $SQL = $minmaxavg."(case DCVECTOR_INDEX when $index then $counterName else 0 end)";
        return str_replace("\n", "", $SQL);

    }//end convertInternalCounter()
       public function loadAggTypes()
    {
        $aggTypeDefs = file("./app/Console/Commands/Bird/AggTypeDefine.txt");
        $aggTypes    = array();

        foreach ($aggTypeDefs as $aggTypeDef) {
            $aggType = explode("=", $aggTypeDef);
            $aggTypes[$aggType[0]] = $aggType[1];
        }

        return $aggTypes;

    }//end loadAggTypes()

    /**
     * 解析KPI
     *
     * @param string $kpi        KPI公式
     * @param array  $counters   计数器集合
     * @param array  $counterMap 计数器表名映射
     * @param array  $nosum_map  非SUM指标集合
     *
     * @return string
     */
    protected function parserKPI($kpi, $counters, &$counterMap, &$nosum_map,$format,$timeDim)
    {
        //$kpi是指标公式
        $pattern       = "/[\(\)\+\*-\/]/";
        $columns       = preg_split($pattern, $kpi);
        $pattern_nosum = "/(max|min|avg)\((.*)\)/";
        $matches       = array();
        foreach ($columns as $column) {
            $column      = trim($column);
            $counterName = $column;
            if (stripos($counterName, "pm") === false) {
                continue;
            }

            if (stripos($counterName, "_") !== false) {
                $elements    = explode("_", $counterName);
                $counterName = $elements[0];
            }
            if (array_key_exists(strtolower($counterName), $counters)) {
                $table = $counters[strtolower($counterName)];
            } else {
                // return strtolower($counterName);
                continue;
            }

            
            if ($timeDim=='NBIOT') {
                 $table   = trim($table)."_RAW";
            } else {
               $table   = ($timeDim == "day") ? trim($table)."_day" : trim($table)."_raw";
               
            // $table   = ($timeDim == "day") ? trim($table)."_day" : trim($table)."_raw";
            }
            if (preg_match($pattern_nosum, $kpi, $matches)) {
            
                $counterMap[$kpi] = $table;

                $data = str_replace($matches[0], "agg".count($nosum_map), $kpi);
                $nosum_map[$kpi]  = $data;
                // $nosum_map[$kpi]  = "agg".count($nosum_map);
                // print_r($counterMap);
                break;
            }


            if (!array_key_exists($column, $counterMap)) {
                $counterMap[$column] = $table;
            }
        }//end foreach

    }//end parserKPI()
        protected function getTextAndRows($db, $template, $testArr, $locationDim, $timeDim, $format) {
        // print_r($testArr);return;
        $kpiformula = $this->getKpis_1($db, $template);
        $i = 0;
        $newFormula = [];

        foreach ($testArr as $key => $value) {
            if (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && $timeDim == 'day') {
                $newFormula[$i]['day'] = $value['day'];
                // $newFormula[$i]['cellNum'] = $value['cellNum'];
                $newFormula[$i]['location'] = $value['location'];
            } elseif (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && $timeDim == 'daygroup') {
                $newFormula[$i]['day'] = $value['day'];
                $newFormula[$i]['location'] = $value['location'];
            } elseif (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && ($timeDim == 'hour' || $timeDim == 'hourgroup')) {
                $newFormula[$i]['day'] = $value['day'];
                $newFormula[$i]['hour'] = $value['hour'];
                // $newFormula[$i]['cellNum'] = $value['cellNum'];
                $newFormula[$i]['location'] = $value['location'];
            } elseif (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && $timeDim == 'quarter') {
                $newFormula[$i]['day'] = $value['day'];
                $newFormula[$i]['hour'] = $value['hour'];
                $newFormula[$i]['minute'] = $value['minute'];
                // $newFormula[$i]['cellNum'] = $value['cellNum'];
                $newFormula[$i]['location'] = $value['location'];
            } elseif ($locationDim == 'cellGroup' && $timeDim == 'day') {
                $newFormula[$i]['day'] = $value['day'];
                // $newFormula[$i]['cellNum'] = $value['cellNum'];
            } elseif ($locationDim == 'cellGroup' && $timeDim == 'daygroup') {
                $newFormula[$i]['day'] = $value['day'];
            } elseif ($locationDim == 'cellGroup' && ($timeDim == 'hour' || $timeDim == 'hourgroup')) {
                $newFormula[$i]['day'] = $value['day'];
                $newFormula[$i]['hour'] = $value['hour'];
                // $newFormula[$i]['cellNum'] = $value['cellNum'];
            } elseif ($locationDim == 'cellGroup' && $timeDim == 'quarter') {
                $newFormula[$i]['day'] = $value['day'];
                $newFormula[$i]['hour'] = $value['hour'];
                $newFormula[$i]['minute'] = $value['minute'];
                // $newFormula[$i]['cellNum'] = $value['cellNum'];
            }
            $j = 0;
            foreach ($kpiformula['kpiformula'] as $k => $v) {
                // print_r($v);echo'--';
                $newFormula[$i]['kpi'.$j] = $this->getCalculationRes($v['kpiFormula'], $v['kpiPrecision'], $value);
                $j++;
            }
            $i++;
        }
        
        if (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && $timeDim == 'day') {
            if ($format == 'TDD'||$format=="NBIOT") {
                $return['kpisName'] = "day,location,".$kpiformula['kpiNames'];
            } else {
                $return['kpisName'] = "day,location,".$kpiformula['kpiNames'];
            }
            
        } elseif (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && $timeDim == 'daygroup') {
            $return['kpisName'] = "day,location,".$kpiformula['kpiNames'];
        } elseif (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && ($timeDim == 'hour' || $timeDim == 'hourgroup')) {
            if ($format == 'TDD'||$format=="NBIOT") {
                $return['kpisName'] = "day,hour,location,".$kpiformula['kpiNames'];
            } else {
                $return['kpisName'] = "day,hour,location,".$kpiformula['kpiNames'];
            }
            
        } elseif (($locationDim == 'city' || $locationDim == 'subNetworkGroup' || $locationDim == 'erbsGroup') && $timeDim == 'quarter') {
            if ($format == 'TDD'||$format=="NBIOT") {
                $return['kpisName'] = "day,hour,minute,location,".$kpiformula['kpiNames'];
            } else {
                $return['kpisName'] = "day,hour,minute,location,".$kpiformula['kpiNames'];
            }
            
        } elseif ($locationDim == 'cellGroup' && $timeDim == 'day') {
            if ($format == 'TDD'||$format=="NBIOT") {
                $return['kpisName'] = "day,".$kpiformula['kpiNames'];
            } else {
                $return['kpisName'] = "day,".$kpiformula['kpiNames'];
            }
            
        } elseif ($locationDim == 'cellGroup' && $timeDim == 'daygroup') {
            $return['kpisName'] = "day,".$kpiformula['kpiNames'];
        } elseif ($locationDim == 'cellGroup' && ($timeDim == 'hour' || $timeDim == 'hourgroup')) {
            if ($format == 'TDD'||$format=="NBIOT") {
                $return['kpisName'] = "day,hour,".$kpiformula['kpiNames'];
            } else {
                $return['kpisName'] = "day,hour,".$kpiformula['kpiNames'];
            }
            
        } elseif ($locationDim == 'cellGroup' && $timeDim == 'quarter') {
            if ($format == 'TDD'||$format=="NBIOT") {
                $return['kpisName'] = "day,hour,minute,".$kpiformula['kpiNames'];
            } else {
                $return['kpisName'] = "day,hour,minute,".$kpiformula['kpiNames'];
            }
            
        }
    
        $return['newFormula'] = $newFormula;
        return $return;
    }

    protected function getKpis_1($db, $template) {
        $queryKpiset  = "select elementId from template where templateName='$template'";
        $res          = $db->query($queryKpiset);
        $kpis         = $res->fetchColumn();
        $kpisStr      = $kpis.',';
        $queryKpiName = "select kpiName,instr('$kpisStr',CONCAT(',',id,',')) as sort from kpiformula where id in ($kpis) order by sort";
        $res          = $db->query($queryKpiName);
        $kpiNames     = "";
        foreach ($res as $row) {
            $kpiNames = $kpiNames.$row['kpiName'].",";
        }
        $kpiNames        = substr($kpiNames, 0, (strlen($kpiNames) - 1));
        $kpisName = $kpis.',';
        $queryFormula  = "select kpiName,kpiFormula,kpiPrecision,instr('$kpisName',CONCAT(',',id,',')) as sort from kpiformula where id in (".$kpis.") order by sort";
        $kpiformula = $db->query($queryFormula)->fetchall(PDO::FETCH_ASSOC);
        $return['kpiformula'] = $kpiformula;
        $return['kpiNames'] = $kpiNames;
        return $return;
    }

    protected function getCalculationRes($kpiformula, $kpiPrecision, $arr) {
        $kpiformula = strtolower($kpiformula);
        $pattern       = "/[\(\)\+\*-\/]/";
        $columns       = preg_split($pattern, $kpiformula);
        // print_r($columns);
        foreach ($columns as $column) {
            $column      = strtolower(trim($column));
            if (stripos($column, "pm") !== false) {
                preg_match('/^(max|min|avg)\((.*)\)/', $kpiformula, $match);
                if (count($match) == 3) {
                    $columns = $match[1]."\(".$match[2]."\)";
                    // $kpiformula = str_replace($columns, $arr[$column], $kpiformula);
                    $columnstr = "/$columns/";
                    $kpiformula = preg_replace($columnstr, $arr[$column], $kpiformula, 1);
                } else {
                    // $kpiformula = str_replace($column, $arr[$column], $kpiformula);
                    $myColumn = "/$column/";
                    $kpiformula = preg_replace($myColumn, $arr[$column], $kpiformula, 1);
                }
            }
        }//end foreach
        //eval不支持power
        $kpiformula = str_replace('power', 'pow', $kpiformula);
        // print_r($kpiformula);
        try {
            return round(eval("return $kpiformula;"), $kpiPrecision);
            
        } catch (\Exception $e) {
            return round('0', $kpiPrecision);
            
        }
    }
}