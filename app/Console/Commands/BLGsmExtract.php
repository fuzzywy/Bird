<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Databaseconn2G;
use App\Models\B_L_GSM;
use PDO;
class BLGsmExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BLGsm:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'B_L_GSM 数据备份';

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
        
        $cityList = Databaseconn2G::select()->get()->toArray();

        $startTime = date("Y-m-d",strtotime("-1 hour"));
        $hour      = date("H",strtotime("-1 hour"));
        foreach ($cityList as $key => $value) {
            $host     = $value['host'];
            $port     = $value['port'];
            $dbName   = $value['dbName'];
            $userName = $value['userName'];
            $password = $value['password'];
            $pmDbDSN  = "dblib:host=".$host.":".$port.";dbname=".$dbName;
            try {
                $pmDB     = new PDO($pmDbDSN, $userName, $password);
            } catch (\Exception $e) {
                return;
            }
            if($value['connName']=="changzhou"){
            	$k="0.69";
            }elseif($value['connName']=="suzhou"){
            	$k="0.71";
            }else{
            	$k="1";
            }
            if($pmDB){
                // $sql = "select CONVERT (CHAR(10), date_id) AS date_id,count(distinct BSC) as BSC,count(distinct CELL_NAME) as cell from dc.DC_E_BSS_CELL_PS_RAW WHERE date_id >= '$startTime' AND date_id < '$endTime' group by date_id" ;
                $sql="SELECT AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.location,cast(SUM(CELTCHF_TFTRALACC/CELTCHF_TFNSCAN+CELTCHH_THTRALACC/CELTCHH_THNSCAN) as decimal(18,2)) as kpi0,cast(SUM((CELLQOSEG_ULTHP1EGDATA+CELLQOSEG_ULTHP2EGDATA+CELLQOSEG_ULTHP3EGDATA+CELLQOSEG_ULBGEGDATA+CELLQOSG_ULTHP1GDATA+CELLQOSG_ULTHP2GDATA+CELLQOSG_ULTHP3GDATA+CELLQOSG_ULBGGDATA)/8/1024)+SUM((CELLQOSEG_DLTHP1EGDATA+CELLQOSEG_DLTHP2EGDATA+CELLQOSEG_DLTHP3EGDATA+CELLQOSEG_DLBGEGDATA+CELLQOSG_DLTHP1GDATA+CELLQOSG_DLTHP2GDATA+CELLQOSG_DLTHP3GDATA+CELLQOSG_DLBGGDATA)/8/1024) as decimal(18,2)) as kpi1,cast(SUM((CELTCHF_TFTRALACC/CELTCHF_TFNSCAN+CELTCHH_THTRALACC/CELTCHH_THNSCAN)/((CLTCH_TAVAACC)/(CLTCH_TAVASCAN+0.0001))) as decimal(18,2)) as kpi2,cast(SUM((CELTCHF_TFTRALACC/CELTCHF_TFNSCAN+CELTCHH_THTRALACC/CELTCHH_THNSCAN)/(CELLGPRS_ALLPDCHACTACC/(CELLGPRS_ALLPDCHSCAN+0.0001))) as decimal(18,2)) as kpi3,cast(SUM(100*(CELTCHF_TFTRALACC/(CELTCHF_TFNSCAN+0.000001)+CELTCHH_THTRALACC/(CELTCHH_THNSCAN+0.000001)+CELLGPRS_ALLPDCHACTACC/(CELLGPRS_ALLPDCHSCAN+.000001))/(CLTCH_TAVAACC/(CLTCH_TAVASCAN+0.000001)+0.000001)/$k) as decimal(18,2)) as kpi4 FROM (SELECT CONVERT(char(10),date_id) as day, hour_id as hour, 'changzhou' as location, sum(CELTCHF_TFTRALACC) as 'CELTCHF_TFTRALACC', sum(CELTCHF_TFNSCAN) as 'CELTCHF_TFNSCAN', sum(CELTCHH_THTRALACC) as 'CELTCHH_THTRALACC', sum(CELTCHH_THNSCAN) as 'CELTCHH_THNSCAN', sum(CLTCH_TAVAACC) as 'CLTCH_TAVAACC', sum(CLTCH_TAVASCAN) as 'CLTCH_TAVASCAN' from dc.DC_E_BSS_CELL_CS_RAW where date_id='$startTime' AND TIMELEVEL='HOUR' and hour_id in ($hour) GROUP BY day,hour, location)as AGG_TABLE0 left join(SELECT CONVERT(char(10),date_id) as day, hour_id as hour, 'changzhou' as location, sum(CELLQOSEG_ULTHP1EGDATA) as 'CELLQOSEG_ULTHP1EGDATA', sum(CELLQOSEG_ULTHP2EGDATA) as 'CELLQOSEG_ULTHP2EGDATA', sum(CELLQOSEG_ULTHP3EGDATA) as 'CELLQOSEG_ULTHP3EGDATA', sum(CELLQOSEG_ULBGEGDATA) as 'CELLQOSEG_ULBGEGDATA', sum(CELLQOSG_ULTHP1GDATA) as 'CELLQOSG_ULTHP1GDATA', sum(CELLQOSG_ULTHP2GDATA) as 'CELLQOSG_ULTHP2GDATA', sum(CELLQOSG_ULTHP3GDATA) as 'CELLQOSG_ULTHP3GDATA', sum(CELLQOSG_ULBGGDATA) as 'CELLQOSG_ULBGGDATA', sum(CELLQOSEG_DLTHP1EGDATA) as 'CELLQOSEG_DLTHP1EGDATA', sum(CELLQOSEG_DLTHP2EGDATA) as 'CELLQOSEG_DLTHP2EGDATA', sum(CELLQOSEG_DLTHP3EGDATA) as 'CELLQOSEG_DLTHP3EGDATA', sum(CELLQOSEG_DLBGEGDATA) as 'CELLQOSEG_DLBGEGDATA', sum(CELLQOSG_DLTHP1GDATA) as 'CELLQOSG_DLTHP1GDATA', sum(CELLQOSG_DLTHP2GDATA) as 'CELLQOSG_DLTHP2GDATA', sum(CELLQOSG_DLTHP3GDATA) as 'CELLQOSG_DLTHP3GDATA', sum(CELLQOSG_DLBGGDATA) as 'CELLQOSG_DLBGGDATA', sum(CELLGPRS_ALLPDCHACTACC) as 'CELLGPRS_ALLPDCHACTACC', sum(CELLGPRS_ALLPDCHSCAN) as 'CELLGPRS_ALLPDCHSCAN' from dc.DC_E_BSS_CELL_PS_RAW where date_id='$startTime' AND TIMELEVEL='HOUR' and hour_id in ($hour) GROUP BY day,hour, location)as AGG_TABLE1 on AGG_TABLE0.day = AGG_TABLE1.day and AGG_TABLE0.hour = AGG_TABLE1.hour GROUP BY AGG_TABLE0.day,AGG_TABLE0.hour, AGG_TABLE0.location ORDER BY AGG_TABLE0.day,AGG_TABLE0.hour";
                $res = $pmDB->query($sql)->fetchall(PDO::FETCH_ASSOC);  
                // print_r($res);exit;
                  if($res){
                  	  $B_S_GSM = new B_L_GSM;
                      $B_S_GSM->day_id=$res[0]['day'];
                      $B_S_GSM->hour=$res[0]['hour'];
                      $B_S_GSM->location=$value['cityChinese'];
                      $B_S_GSM->tel_traffic=$res[0]['kpi0'];
                      $B_S_GSM->data_traffic=$res[0]['kpi1'];
                      $B_S_GSM->tel_traffic_tch=$res[0]['kpi2'];
                      $B_S_GSM->data_traffic_pdch=$res[0]['kpi3'];
                      $B_S_GSM->wireless_rate=$res[0]['kpi4'];
                      $B_S_GSM->save();
                     
                }
                
            }

        }
    }
}
