<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\Databaseconns;
use App\Models\B_L_TDD;
use App\Models\City;
use App\Models\Kget\EUtranCellTDD;
use PDO;

class BLTddExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BLTdd:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '负荷概览B_L_Tdd 数据备份';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $dbc = new DataBaseConnection();
        $cities = City::select()->get()->toArray();
        $kgetName = $dbc->getKgetTime();
        $startTime = date("Y-m-d",strtotime("-1 day"));
  
        $db_kget = $dbc->getDB("kget",$kgetName);

        foreach ($cities as $key => $values) {
            $subNetWorkStr = $dbc->getSubNetsStr("TDD",$values['cityChinese']);
            $sql="select sum(t.t)/1000 as d_tti,sum(t.s)/1000 as u_tti from(SELECT
                                CASE
                            WHEN subframeAssignment = 1
                            AND specialSubframePattern = 5 THEN
                                3600 * 100 * 4*channelBandwidth
                            WHEN subframeAssignment = 1
                            AND specialSubframePattern IN (6, 7) THEN
                                3600 * 100 * 6*channelBandwidth
                            WHEN subframeAssignment = 2
                            AND specialSubframePattern = 5 THEN
                                3600 * 100 * 6*channelBandwidth
                            WHEN subframeAssignment = 2
                            AND specialSubframePattern IN (6, 7) THEN
                                3600 * 100 * 8*channelBandwidth
                            END AS t,
                             CASE
                            WHEN subframeAssignment = 1
                            AND specialSubframePattern = 5 THEN
                                3600 * 100 * 4*channelBandwidth
                            WHEN subframeAssignment = 1
                            AND specialSubframePattern IN (6, 7) THEN
                                3600 * 100 * 4*channelBandwidth
                            WHEN subframeAssignment = 2
                            AND specialSubframePattern = 5 THEN
                                3600 * 100 * 2*channelBandwidth
                            WHEN subframeAssignment = 2
                            AND specialSubframePattern IN (6, 7) THEN
                                3600 * 100 * 2*channelBandwidth
                            END AS s
                            FROM
                                EUtranCellTDD
                            WHERE
                                subNetWork IN ($subNetWorkStr ))t";
            $tti = $db_kget->query($sql)->fetch(PDO::FETCH_ASSOC);

            $dbserve = Databaseconns::select()->where("cityChinese",$values['cityChinese'])->get()->toArray();
            $item = array();
            $item2= array();
            foreach ($dbserve as $key => $value) {
                $subNetWork = $dbc->getSubNetsByconnName("TDD",$value['connName']);
                $SN         = $dbc->getSN($value['connName']);
                $dc         = $dbc->getDC($value['connName']);
                $result = array();
                if($subNetWork){
                    $host = $value['host'];
                    $port = $value['port'];
                    $dbName = $value['dbName'];
                    $userName = $value['userName'];
                    $password = $value['password'];
                    $city = $value['connName'];
                    $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;
                    try {
                        
                        $pmDB = new PDO($pmDbDSN, $userName, $password);
                    } catch (\Exception $e) {
                        echo '连接失败';
                        continue;
                    }
                     $sql="select convert(char(10),date_id) as day,hour_id as hour,min_id as minute,'".$value['connName']."' as location,COUNT(DISTINCT(EutranCellTDD)) AS cellNum,sum(pmRrcConnLevSum/pmRrcConnLevSamp) as 'agg1',sum(pmRrcConnMax) as 'agg0' from ".$dc."DC_E_ERBS_EUTRANCELLTDD_raw where date_id>='$startTime' and date_id<='$startTime' and $SN in ($subNetWork) group by date_id,hour_id,min_id,location";
                     // echo $sql;exit;
                     $res = $pmDB->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                     $sql2 = "select AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.cellNum,AGG_TABLE0.location,pmpdcpvoluldrb,pmpdcpvoldldrb,pmerabqcilevsum_2,pmerabqcilevsum_1,pmpdcchcceutil_0,pmpdcchcceutil_1,pmpdcchcceutil_2,pmpdcchcceutil_3,pmpdcchcceutil_4,pmpdcchcceutil_5,pmpdcchcceutil_6,pmpdcchcceutil_7,pmpdcchcceutil_8,pmpdcchcceutil_9,pmpdcchcceutil_10,pmpdcchcceutil_11,pmpdcchcceutil_12,pmpdcchcceutil_13,pmpdcchcceutil_14,pmpdcchcceutil_15,pmpdcchcceutil_16,pmpdcchcceutil_17,pmpdcchcceutil_18,pmpdcchcceutil_19,pmpdcchcceutil_0,pmpdcchcceutil_1,pmpdcchcceutil_2,pmpdcchcceutil_3,pmpdcchcceutil_4,pmpdcchcceutil_5,pmpdcchcceutil_6,pmpdcchcceutil_7,pmpdcchcceutil_8,pmpdcchcceutil_9,pmpdcchcceutil_10,pmpdcchcceutil_11,pmpdcchcceutil_12,pmpdcchcceutil_13,pmpdcchcceutil_14,pmpdcchcceutil_15,pmpdcchcceutil_16,pmpdcchcceutil_17,pmpdcchcceutil_18,pmpdcchcceutil_19,pmprbuseduldtch,pmprbusedulsrb,pmprbavailul,pmprbuseddldtch,pmprbuseddlbcch,pmprbuseddlpcch,pmprbuseddlsrbfirsttrans,pmprbuseddlretrans,pmprbuseddlfirsttrans,pmprbavaildl from (select convert(char(10),date_id) as day,hour_id as hour, 'changzhou' as location,COUNT(DISTINCT(EutranCellTDD)) AS cellNum,sum(pmPdcpVolUlDrb) as 'pmPdcpVolUlDrb',sum(pmPdcpVolDlDrb) as 'pmPdcpVolDlDrb',sum(pmPrbUsedUlDtch) as 'pmPrbUsedUlDtch',sum(pmPrbUsedUlSrb) as 'pmPrbUsedUlSrb',sum(pmPrbAvailUl) as 'pmPrbAvailUl',sum(pmPrbUsedDlDtch) as 'pmPrbUsedDlDtch',sum(pmPrbUsedDlBcch) as 'pmPrbUsedDlBcch',sum(pmPrbUsedDlPcch) as 'pmPrbUsedDlPcch',sum(pmPrbUsedDlSrbFirstTrans) as 'pmPrbUsedDlSrbFirstTrans',sum(pmPrbUsedDlReTrans) as 'pmPrbUsedDlReTrans',sum(pmPrbUsedDlFirstTrans) as 'pmPrbUsedDlFirstTrans',sum(pmPrbAvailDl) as 'pmPrbAvailDl' from ".$dc."DC_E_ERBS_EUTRANCELLTDD_raw where date_id>='$startTime' and date_id<='$startTime' and $SN in ($subNetWork) group by date_id,hour_id,location)as AGG_TABLE0 left join(select convert(char(10),date_id) as day,hour_id as hour, 'changzhou' as location,COUNT(DISTINCT(EutranCellTDD)) AS cellNum,sum(case DCVECTOR_INDEX when 2 then pmErabQciLevSum else 0 end) as 'pmErabQciLevSum_2',sum(case DCVECTOR_INDEX when 1 then pmErabQciLevSum else 0 end) as 'pmErabQciLevSum_1',sum(case DCVECTOR_INDEX when 0 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_0',sum(case DCVECTOR_INDEX when 1 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_1',sum(case DCVECTOR_INDEX when 2 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_2',sum(case DCVECTOR_INDEX when 3 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_3',sum(case DCVECTOR_INDEX when 4 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_4',sum(case DCVECTOR_INDEX when 5 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_5',sum(case DCVECTOR_INDEX when 6 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_6',sum(case DCVECTOR_INDEX when 7 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_7',sum(case DCVECTOR_INDEX when 8 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_8',sum(case DCVECTOR_INDEX when 9 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_9',sum(case DCVECTOR_INDEX when 10 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_10',sum(case DCVECTOR_INDEX when 11 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_11',sum(case DCVECTOR_INDEX when 12 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_12',sum(case DCVECTOR_INDEX when 13 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_13',sum(case DCVECTOR_INDEX when 14 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_14',sum(case DCVECTOR_INDEX when 15 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_15',sum(case DCVECTOR_INDEX when 16 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_16',sum(case DCVECTOR_INDEX when 17 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_17',sum(case DCVECTOR_INDEX when 18 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_18',sum(case DCVECTOR_INDEX when 19 then pmPdcchCceUtil else 0 end) as 'pmPdcchCceUtil_19' from ".$dc."DC_E_ERBS_EUTRANCELLTDD_V_raw where date_id>='$startTime' and date_id<='$startTime' and $SN in ($subNetWork) group by date_id,hour_id,location)as AGG_TABLE1 on AGG_TABLE0.day = AGG_TABLE1.day and AGG_TABLE0.hour = AGG_TABLE1.hour and AGG_TABLE0.location = AGG_TABLE1.location order by AGG_TABLE0.day,AGG_TABLE0.hour";
                     // print_r($res);exit;
                     $res2 = $pmDB->query($sql2)->fetchAll(PDO::FETCH_ASSOC);
                     if($res2){
                     	array_push($item2, $res2);
                     }
                     // print_r($res2);
                     // exit;
                     if($res){
                        array_push($item,$res);
                     	
                     }                    
                    
                }        

            }

            if(!$item){
                continue;
            }

            // print_r(count($item));exit;
            $item_len = count($item);
            $len     = count($item[0]);
            $new_item = array();
            $key = array_keys($item[0][0]);

            for($i=1;$i<$item_len;$i++){
            	for($j=0;$j<$len;$j++){
            		// $item[0][$j]['pmRrcConnLevSum']+=$item[$i][$j]['pmRrcConnLevSum'];
            		// $item[0][$j]['pmRrcConnLevSamp']+=$item[$i][$j]['pmRrcConnLevSamp'];
            		$item[0][$j]['cellNum']+=$item[$i][$j]['cellNum'];
                    $item[0][$j]['agg1']+=$item[$i][$j]['agg1'];
            		$item[0][$j]['agg0']+=$item[$i][$j]['agg0'];
            		// $item[0][$j]['agg0']= $item[0][$j]['agg0']>$item[$i][$j]['agg0']?$item[0][$j]['agg0']:$item[$i][$j]['agg0'];
            	

            	}
            }
            $rrc_users=array();
            $rrc_cell_user_mean=array();
            $agg0=array();
            $cellNum=array();
            $rrc_cell_users_max=array();
            foreach ($item[0] as $key => $value) {
            	$rrc_users[] = $value['agg1'];
            	$rrc_cell_user_mean[] = $value['agg1']/$value['cellNum'];
            	$agg0[] = $value['agg0'];
            	$cellNum[] = $value['cellNum'];
            	$rrc_cell_users_max[] = $value['agg0']/$value['cellNum'];
            }

            $subNetWorkArr = $dbc->getSubNetsArr("TDD",$values['cityChinese']);
        	$num=EutranCellTDD::whereIn("subNetWork",$subNetWorkArr)->sum('channelBandwidth');
   //      	print_r($num/1000);exit;
			// print_r($rrc_users);
			// print_r($rrc_cell_user_mean);
			// exit;           
			$B_L_TDD = new B_L_TDD;


			$B_L_TDD->day_id=$startTime;
			$B_L_TDD->location=$values['cityChinese'];
			$B_L_TDD->rrc_users=max($rrc_users);
			$B_L_TDD->rrc_cell_user_mean=max($rrc_users)/$cellNum[array_search(max($rrc_users),$rrc_users)];
			$B_L_TDD->rrc_cell_users_max=max($agg0)/$cellNum[array_search(max($agg0), $agg0)];
			$B_L_TDD->rrc_cell_band_users=$num==0?'0':max($rrc_users)/$num*1000;
            if($item2){
                $results = $this->getItem($item2);
                // var_dump("上行：".$results['flow_u']);
                // var_dump("下行：".$results['flow_d']);
                // var_dump("上行tti：".$tti['u_tti']);
                // var_dump("下行tti：".$tti['d_tti']);
                // var_dump("channelBandwidth:".$num);
                // exit;
                $B_L_TDD->flow= $results['flow_u']+$results['flow_d'];
                $B_L_TDD->flow_tti_u=$tti['u_tti']==0?'0':$results['flow_u']/($tti['u_tti']);
                $B_L_TDD->flow_tti_d=$tti['d_tti']==0?'0':$results['flow_d']/($tti['d_tti']);
                $B_L_TDD->volte_traffic=$results['volte_traffic'];
                $B_L_TDD->cce=$results['cce'];
                $B_L_TDD->prb=$results['prb'];
                
            }
			$B_L_TDD->save();


        }
    }

    public function getItem($item){
	    $item_len = count($item);
        $len     = count($item[0]);
        $new_item = array();
        $key = array_keys($item[0][0]);
        // print_r($key);exit;
        for($i=1;$i<$item_len;$i++){
        	for($j=0;$j<$len;$j++){
        		// for($k=0;$k<count($key)-3;$k++){
        		// 	$item[0][$j][$key[$k+3]]+=$item[$i][$j][$key[$k+3]];
        		// }
        		for($k=0;$k<count($key)-4;$k++){
        			$item[0][$j][$key[$k+4]]+=$item[$i][$j][$key[$k+4]];
        		}
        	}
        }

        foreach ($item[0] as $key => $value) {
            $flow_u[]=($value['pmpdcpvoluldrb'])/(1024*8*1024);
            $flow_d[]=($value['pmpdcpvoldldrb'])/(1024*8*1024);
        	$volte_traffic[]=($value['pmerabqcilevsum_2']+$value['pmerabqcilevsum_1'])/(180*4);
            if(($value['pmpdcchcceutil_0'] + $value['pmpdcchcceutil_1'] + $value['pmpdcchcceutil_2'] + $value['pmpdcchcceutil_3'] + $value['pmpdcchcceutil_4'] + $value['pmpdcchcceutil_5'] + $value['pmpdcchcceutil_6'] + $value['pmpdcchcceutil_7'] + $value['pmpdcchcceutil_8'] + $value['pmpdcchcceutil_9'] + $value['pmpdcchcceutil_10'] + $value['pmpdcchcceutil_11'] + $value['pmpdcchcceutil_12'] + $value['pmpdcchcceutil_13'] + $value['pmpdcchcceutil_14'] + $value['pmpdcchcceutil_15'] + $value['pmpdcchcceutil_16'] + $value['pmpdcchcceutil_17'] + $value['pmpdcchcceutil_18'] + $value['pmpdcchcceutil_19'])==0){
                $cce[]=0;
            }else{
                 $cce[]=(($value['pmpdcchcceutil_0'] ) * 2.5+ ( $value['pmpdcchcceutil_1'] ) * 7.5+ ( $value['pmpdcchcceutil_2'] ) * 12.5+ ( $value['pmpdcchcceutil_3'] ) * 17.5+ ( $value['pmpdcchcceutil_4'] ) * 22.5+ ( $value['pmpdcchcceutil_5'] ) * 27.5+ ( $value['pmpdcchcceutil_6'] ) * 32.5+ ( $value['pmpdcchcceutil_7'] ) * 37.5+ ( $value['pmpdcchcceutil_8'] ) * 42.5+ ( $value['pmpdcchcceutil_9'] ) * 47.5+ ( $value['pmpdcchcceutil_10'] ) * 52.5+ ( $value['pmpdcchcceutil_11'] ) * 57.5+ ( $value['pmpdcchcceutil_12'] ) * 62.5+ ( $value['pmpdcchcceutil_13'] ) * 67.5+ ( $value['pmpdcchcceutil_14'] ) * 72.5+ ( $value['pmpdcchcceutil_15'] ) * 77.5+ ( $value['pmpdcchcceutil_16'] ) * 82.5+ ( $value['pmpdcchcceutil_17'] ) * 87.5+ ( $value['pmpdcchcceutil_18'] ) * 92.5+ ( $value['pmpdcchcceutil_19'] ) * 97.5 ) / ($value['pmpdcchcceutil_0'] + $value['pmpdcchcceutil_1'] + $value['pmpdcchcceutil_2'] + $value['pmpdcchcceutil_3'] + $value['pmpdcchcceutil_4'] + $value['pmpdcchcceutil_5'] + $value['pmpdcchcceutil_6'] + $value['pmpdcchcceutil_7'] + $value['pmpdcchcceutil_8'] + $value['pmpdcchcceutil_9'] + $value['pmpdcchcceutil_10'] + $value['pmpdcchcceutil_11'] + $value['pmpdcchcceutil_12'] + $value['pmpdcchcceutil_13'] + $value['pmpdcchcceutil_14'] + $value['pmpdcchcceutil_15'] + $value['pmpdcchcceutil_16'] + $value['pmpdcchcceutil_17'] + $value['pmpdcchcceutil_18'] + $value['pmpdcchcceutil_19']); 
            }
           if($value['pmprbavailul']&&$value['pmprbuseddlfirsttrans']&&$value['pmprbavaildl']){

            $prb[]=(100 * ( $value['pmprbuseduldtch'] + $value['pmprbusedulsrb'] ) / ( $value['pmprbavailul'] / 100 ) / 96 + 100 * ( ( $value['pmprbuseddldtch'] + $value['pmprbuseddlbcch'] + $value['pmprbuseddlpcch'] ) + ( $value['pmprbuseddlsrbfirsttrans'] ) * ( 1+ ( $value['pmprbuseddlretrans'] ) / ( $value['pmprbuseddlfirsttrans'] ) ) ) / ( $value['pmprbavaildl'] / 100 ) / 100) / 2;
           }else{
            $prb[]=0;
           }
        }
        $new_item = array();
        $new_item['flow_u']=max($flow_u);
        $new_item['flow_d']=max($flow_d);
        $new_item['volte_traffic']=max($volte_traffic);
        $new_item['cce']=max($cce);
        $new_item['prb']=max($prb);

        // print_r($new_item);exit;
        return $new_item;
        // print_r($item[0]);exit;
	}
}
