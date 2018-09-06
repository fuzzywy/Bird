<?php 
namespace App\Console\Commands\Bird;
use PDO;
class GSMQuery{

	public static function queryTemplate($db,$pmDB,$counters,$template,$locationDim,$timeDim,$city,$startTime,$endTime)
	{	
		 	$result         = array();
	        $kpis           =self::getKpis($db,$template);
	        $result['text'] = $kpis['names'];
	        $sql            = self::createSQL(
	            $db,
	            $pmDB,
	            $kpis['ids'],$startTime,$endTime,
	            $counters,
	            $timeDim,
	            $locationDim,
	            $city,
	            $resultText
	        );
	        $result = $pmDB->query($sql)->fetchall(PDO::FETCH_ASSOC);

	        return $result;
	       
	}



	public static function getKpis($db,$template)
	{	
	    $sql = "select elementId from `template_2G` where templateName = '$template'";
	    $res = $db->query($sql,PDO::FETCH_ASSOC)->fetchColumn();
	    $queryKpiName = "select kpiName,instr('$res',id) as sort from kpiformula_2G 
	                where id in ($res) order by sort";
	    $resKpiName          = $db->query($queryKpiName, PDO::FETCH_ASSOC);
	    $kpiNames     = "";
	    foreach ($resKpiName as $row) {
	        $kpiNames = $kpiNames.$row['kpiName'].",";
	    }

	    $kpiNames        = substr($kpiNames, 0, (strlen($kpiNames) - 1));
	    $result          = array();
	    $result['ids']   = $res;
	    $result['names'] = $kpiNames;
	    return $result;
	}



	public static function createSql($localDB, $pmDB, $kpiName,$startTime,$endTime, $counters, $timeDim,
        $locationDim, $city, &$resultText)
	{
	        $kpiset       = "(".$kpiName.")";
	        $kpis         = "";
	        $queryFormula = "select kpiName,kpiFormula,kpiPrecision,instr('$kpiName',id) as sort 
	                    from kpiformula_2G where id in ".$kpiset." order by sort";
	        $index        = 0;
	        $selectSQL    = "SELECT";
	        $counterMap   = array();
	        foreach ($localDB->query($queryFormula) as $row) {
	            $kpi = $row['kpiFormula'];
	          	self::parserKPI($kpi, $counters, $counterMap);
	            $formula = $row['kpiFormula'];
	            $formula = "cast(".self::formulaTransform($formula)." as decimal(18,".$row['kpiPrecision']."))";
	            $kpis    = $kpis.$formula." as kpi".$index.",";
	            $index++;
	        }
	        $kpis     = substr($kpis, 0, (strlen($kpis) - 1));

	        $time_id  = "date_id";
	         if ($timeDim == 'day' || $timeDim == 'hour' || $timeDim == 'hourgroup') {
	            $timelevel = "HOUR";
	        } else {
	            $timelevel = "15MIN";
	        }
	         $whereSQL = " where date_id>='$startTime' and date_id<='$endTime' AND TIMELEVEL='$timelevel' ";
	        $aggGroupSQL  = "";
	        $aggSelectSQL = "";
	        $aggOrderSQL  = "";
	        $myGroup      = "";
	            if ($timeDim == 'day') {
	                if ($locationDim == 'erbs') {
	                    $selectSQL   = $selectSQL." DATETIME_ID,CONVERT(varchar(10),date_id) as day,";
	                    $aggGroupSQL = " GROUP BY day,DATETIME_ID,BSC,";
	                } else {
	                    $selectSQL   = $selectSQL." CONVERT(varchar(10),date_id) as day,";
	                    $aggGroupSQL = " GROUP BY day,";
	                }

	                $myGroup      = " GROUP BY AGG_TABLE0.day,";
	                $aggSelectSQL = " SELECT AGG_TABLE0.day";
	                $resultText   = $resultText."day,";
	                $aggOrderSQL  = " ORDER BY AGG_TABLE0.day";
	        } else if ($timeDim == 'hour') {
	            if ($locationDim == 'erbs') {
	                $selectSQL   = $selectSQL." CONVERT(varchar(10),date_id) as day, hour_id as hour,";
	                $aggGroupSQL = " GROUP BY day,hour,BSC,";
	            } else {
	                $selectSQL   = $selectSQL." CONVERT(varchar(10),date_id) as day, hour_id as hour,";
	                $aggGroupSQL = " GROUP BY day,hour,";
	            }

	            $myGroup      = " GROUP BY AGG_TABLE0.day,AGG_TABLE0.hour,";
	            $aggSelectSQL = " SELECT AGG_TABLE0.day,AGG_TABLE0.hour";
	            $resultText   = $resultText."day,hour,";
	            $aggOrderSQL  = " ORDER BY AGG_TABLE0.day,AGG_TABLE0.hour";
	        } else if ($timeDim == 'quarter') {
	            if ($locationDim == 'erbs') {
	                $selectSQL   = $selectSQL." CONVERT(varchar(10),date_id) as day, hour_id as hour, min_id as minute,";
	                $aggGroupSQL = " GROUP BY day,hour,minute,BSC,";
	            } else {
	                $selectSQL   = $selectSQL." CONVERT(varchar(10),date_id) as day, hour_id as hour, min_id as minute,";
	                $aggGroupSQL = " GROUP BY day,hour,minute,";
	            }

	            $myGroup      = " GROUP BY AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute,";
	            $aggSelectSQL = "SELECT AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute";
	            $resultText   = $resultText."day,hour,minute,";
	            $aggOrderSQL  = " ORDER BY AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.minute";
	        }
	        //  else if ($timeDim == 'hourgroup') {
	        //     if ($locationDim == 'erbs') {
	        //         $hourcollection = Input::get('hour');
	        //         $selectSQL      = $selectSQL." convert(varchar(10),date_id) as day,'".$hourcollection."' as hour,";
	        //         $aggGroupSQL    = " group by day,hour,BSC,";
	        //     } else {
	        //         $hourcollection = Input::get('hour');
	        //         $selectSQL      = $selectSQL." convert(varchar(10),date_id) as day,'".$hourcollection."' as hour,";
	        //         $aggGroupSQL    = " group by day,";
	        //     }

	        //     $myGroup      = " GROUP BY AGG_TABLE0.day,AGG_TABLE0.hour,";
	        //     $aggSelectSQL = " SELECT AGG_TABLE0.day,AGG_TABLE0.hour";
	        //     $aggOrderSQL  = " order by AGG_TABLE0.day,AGG_TABLE0.hour";
	        //     $resultText   = $resultText."day,hour,";
	        // }//end if

	        if ($locationDim == 'city') {
	            $selectSQL    = $selectSQL." '$city' as location,";
	            $myGroup      = $myGroup." AGG_TABLE0.location";
	            $aggGroupSQL  = $aggGroupSQL." location";
	            $aggSelectSQL = $aggSelectSQL.",AGG_TABLE0.location,";
	            $resultText   = $resultText."location,";
	        } else if ($locationDim == 'cell') {
	            if ($timeDim == "hourgroup") {
	                $selectSQL    = $selectSQL." '$city' as location, MO,";
	            } else {
	                $selectSQL    = $selectSQL." '$city' as location,";
	            }
	            
	            $myGroup      = $myGroup." AGG_TABLE0.location,AGG_TABLE0.MO";

	            $aggGroupSQL  = $aggGroupSQL." location";
	            $aggSelectSQL = $aggSelectSQL.",AGG_TABLE0.location,AGG_TABLE0.MO,";
	            $resultText   = $resultText." location,CELL_NAME,";
	        } else if ($locationDim == 'erbs') {
	            $selectSQL    = $selectSQL." '$city' as location, BSC,";
	            $myGroup      = $myGroup." AGG_TABLE0.location,AGG_TABLE0.BSC";
	            $aggGroupSQL  = $aggGroupSQL." location";
	            $aggSelectSQL = $aggSelectSQL.",AGG_TABLE0.location,AGG_TABLE0.BSC,";
	            $resultText   = $resultText." location,BSC,";
	        }



	        $inputHour = date("H",strtotime("-1 hour"));
	        $inputHour = ltrim($inputHour, '[');
	        $inputHour = rtrim($inputHour, ']');
	        $inputHour = ltrim($inputHour, '"');
	        $inputHour = rtrim($inputHour, '"');
	        $inputHour = str_replace('","', ',', $inputHour);
	        $hour      = isset($inputHour) ? $inputHour : "";
	        if ($hour != "" && ($timeDim == "hourgroup" || $timeDim == "hour" || $timeDim == "quarter")) {
	            $hour     = "(".$hour.")";
	            // $whereSQL = $whereSQL." and DATE_FORMAT(DATETIME_ID, '%H') in ".$hour;
	            $whereSQL = $whereSQL." and hour_id in ".$hour;
	        }



	        if ($timeDim == "quarter") {
	            $min      = "(".$min.")";
	            $whereSQL = $whereSQL." and min_id in ".$min;
	        }

	        @$tables = array_keys(array_count_values($counterMap));

	        $tempTableSQL = "";
	        $index        = 0;

	        // echo $whereSQL."\n";
	        // echo $selectSQL."\n";
	        // echo $aggGroupSQL."\n";exit;
	        foreach ($tables as $table) {
	            $countersForQuery = array_keys($counterMap, $table);
	            $tableSQL         = self::createTempTable(
	                $selectSQL,
	                $whereSQL,
	                $table,
	                $countersForQuery,
	                $aggGroupSQL,
	                $timeDim
	            );
	             $tableSQL         = $tableSQL."as AGG_TABLE$index ";
	            if ($index == 0) {
	                if ($index != (sizeof($tables) - 1)) {
	                    $tableSQL = $tableSQL." left join";
	                }
	            } else {
	                if ($timeDim == "day") {
	                    if ($locationDim == 'erbs') {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day                         
	                            and AGG_TABLE0.BSC=AGG_TABLE$index.BSC";
	                    } elseif ($locationDim == 'cell') {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day                         
	                            and AGG_TABLE0.CELL_NAME=AGG_TABLE$index.CELL_NAME";
	                    } else {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day";
	                    }
	                } else if ($timeDim == "hour") {
	                    if ($locationDim == 'erbs') {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day 
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour
	                            and AGG_TABLE0.BSC=AGG_TABLE$index.BSC";
	                    } elseif ($locationDim == 'cell') {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day  
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour                       
	                            and AGG_TABLE0.CELL_NAME=AGG_TABLE$index.CELL_NAME";
	                    } else {
	                 
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day 
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour";    
	                    }
	                } else if ($timeDim == "quarter") {
	                    if ($locationDim == 'erbs') {
	                    
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day 
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour 
	                            and AGG_TABLE0.minute = AGG_TABLE$index.minute
	                            and AGG_TABLE0.BSC=AGG_TABLE$index.BSC";
	                    } elseif ($locationDim == 'cell') {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day  
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour  
	                            and AGG_TABLE0.minute = AGG_TABLE$index.minute                     
	                            and AGG_TABLE0.CELL_NAME=AGG_TABLE$index.CELL_NAME";
	                    } else {
	                  
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day 
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour 
	                            and AGG_TABLE0.minute = AGG_TABLE$index.minute";
	                    }
	                } else if ($timeDim == "hourgroup") {
	                    if ($locationDim == 'erbs') {
	                  
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day 
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour";
	                    } elseif ($locationDim == 'cell') {
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day  
	                            and AGG_TABLE0.hour = AGG_TABLE$index.hour";
	                    } else {
	                       
	                        $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day";
	                    }
	                }//end if

	                if ($index != (sizeof($tables) - 1)) {
	                    $tableSQL = $tableSQL." left join ";
	                }
	            }//end if
	            $tempTableSQL = $tempTableSQL.$tableSQL;
	            $index++;
	        }//end foreach

	        $sql = $aggSelectSQL.$kpis." FROM ".$tempTableSQL.$myGroup.$aggOrderSQL;
	        return $sql;
	}

	public static function parserKPI($kpi, $counters, &$counterMap)
	{
	    $pattern = "/[\(\)\+\*-\/]/";
	    $columns = preg_split($pattern, $kpi);
	    foreach ($columns as $column) {
	        $column      = trim($column);
	        $counterName = $column;
	        @$table      = $counters[strtolower($counterName)];
	        if (!array_key_exists($column, $counterMap)) {
	            $counterMap[$column] = $table;
	        }
	    }

	}//end parserKPI()

	public static function createTempTable($selectSQL, $whereSQL, $tableName, $counters, $groupSQL, $timeDim)
    {
	        foreach ($counters as $counter) {
	            $counter     = trim($counter);
	            $counterName = $counter;
	            $selectSQL = $selectSQL." sum(".$counter.") as '$counterName',";
	        }

	        $selectSQL = substr($selectSQL, 0, (strlen($selectSQL) - 1));
	        return "($selectSQL from dc.$tableName $whereSQL $groupSQL)";

	}
	//公式转换
	public static function formulaTransform($formula)
	{
	    if (strpos($formula, 'AVG') === 0 || strpos($formula, 'avg') === 0) {
	        // $formula = "AVG(".$formula.")";
	        return $formula;
	    } else if (strpos($formula, '(') == false && strpos($formula, ')') == false) {
	        $formula = "SUM(".$formula.")";
	        return $formula;
	    } else {
	        $firStr  = '';
	        $finStr  = '';
	        $formula = preg_replace("/\s/", "", $formula);
	        $firPos  = strpos($formula, '(');
	        if ($firPos != 0) {
	            $firStr  = substr($formula, 0, $firPos);
	            $formula = substr($formula, $firPos);
	        }

	        $finPos = strrpos($formula, ')');
	        if (($finPos + 1) != strlen($formula)) {
	            $finStr  = substr($formula, ($finPos + 1));
	            $formula = substr($formula, 0, ($finPos + 1));
	        }

	        $arr = [0];
	        $sum = 0;
	        for ($i = 0; $i < strlen($formula); $i++) {
	            if ($formula[$i] == '(') {
	                $sum = ($sum + 1);
	            }

	            if ($formula[$i] == ')') {
	                $sum = ($sum - 1);
	            }

	            if ($sum == 0) {
	                array_push($arr, $i);
	            }
	        }

	        $comStr = self::formulaAddSum($arr, $formula);

	        if (strlen($firStr) > 0 && strlen($finStr) == 0) {
	            $comStr = $firStr.$comStr;
	        } else if (strlen($firStr) == 0 && strlen($finStr) > 0) {
	            $comStr = $comStr.$finStr;
	        } else if (strlen($firStr) > 0 && strlen($finStr) > 0) {
	            $comStr = $firStr.$comStr.$finStr;
	        }

	        return $comStr;
	    }//end if

	}//end formulaTransform()

	public static function formulaAddSum($arr, $formula)
	{
	    if ((count($arr) % 2) != 0 && count($arr) < 2) {
	        return false;
	    }

	    $comStr = '';
	    if (count($arr) == 2) {
	        $comStr = "SUM".$formula;
	    } else if (count($arr) > 2) {
	        for ($i = 0; $i < (count($arr) - 1); $i++) {
	            if (($i % 2) == 0 && $i == 0) {
	                $comStr = $comStr."SUM".substr($formula, $arr[$i], ($arr[($i + 1)] - $arr[$i] + 1));
	            } else if (($i % 2) == 0 && $i != 0 && $i != (count($arr) - 2)) {
	                $comStr = $comStr."SUM".substr($formula, ($arr[$i] + 1), ($arr[($i + 1)] - $arr[$i]));
	            }

	            if (($i % 2) == 1) {
	                $comStr = $comStr.$formula[$arr[($i + 1)]];
	            }

	            if ($i == (count($arr) - 2)) {
	                $comStr = $comStr."SUM".substr($formula, ($arr[$i] + 1), ($arr[($i + 1)] - $arr[$i] + 1));
	            }
	        }
	    }

	    return $comStr;

	}//end formulaAddSum()
}