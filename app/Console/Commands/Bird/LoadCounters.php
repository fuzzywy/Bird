<?php 
namespace App\Console\Commands\Bird;
use PDO;
class LoadCounters{

	public static function loadcounter($type=null)
	{
		if($type == "GSM"){
			$lines  = file("./app/Console/Commands/Bird/Counters_2G.txt");
			$result = array();
		    foreach ($lines as $line) {
		        $pair = explode("=", $line);
		        $result[trim($pair[0])] = trim($pair[1]);
		    }

		    return $result;

		}else if($type == "NBIOT"){
			$lines  = file("./app/Console/Commands/Bird/Counters_NBIOT.txt");
		}else if($type =="TDD"||$type =="FDD"){
			$lines  = file("./app/Console/Commands/Bird/Counters.txt");
		}
		$result = array();
	    foreach ($lines as $line) {
	        $pair = explode("=", $line);
	        $result[strtolower(trim($pair[0]))] = trim($pair[1]);
	    }

	    return $result;
	}

	public static function getSubNets($db,$city,$type)
	{
		if($type == "TDD"){
			$sub = "subNetwork";
		}else if($type =="FDD"){
			$sub = "subNetworkFDD";
		}else if($type == "NBIOT"){
			$sub ="subNetworkNbiot";
		}
		$sql = "select $sub from databaseconn where connName ='$city'";
		$res = $db->query($sql);
	    $row = $res->fetch(PDO::FETCH_ASSOC);
	    $subNets = $row[$sub];
	    return $subNets;

	} 
	 public static function getSubnetWork($oss) {
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
            case "changzhou":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            case "suzhou4":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            case "zhenjiang1":
                $SN = "substring(substring(SN, charindex(',', SN)+12), 0, charindex(',', substring(SN, charindex(',', SN)+12))-1)";
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
}
