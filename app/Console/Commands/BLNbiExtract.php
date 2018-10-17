<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\Databaseconns;
use App\Models\B_L_NBIOT;
use App\Models\City;
use PDO;

class BLNbiExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BLNbi:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'B_L_NBIOT 数据备份';

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
        $City = City::select()->get()->toArray();

        $startTime = date("Y-m-d",strtotime("-1 day"));
  
        foreach ($City as $key => $values) {
            $dbserve = Databaseconns::select()->where("cityChinese",$values['cityChinese'])->get()->toArray();
            $item = array();
            foreach ($dbserve as $key => $value) {
                $subNetWork = $dbc->getSubNetsByconnName("NBIOT",$value['connName']);
                $SN         = $dbc->getSN($value['connName']);
                
                $result = array();
                if($subNetWork){
                    $host = $value['host'];
                    $port = $value['port'];
                    $dbName = $value['dbName'];
                    $userName = $value['userName'];
                    $password = $value['password'];
                    $city = $value['connName'];
                    $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;
                     $pmDB = new PDO($pmDbDSN, $userName, $password);

                     
                        $sql = "select max(t1.kpi0) as rrc_users_cell,max(t2.kpi0) as npdcch from (select AGG_TABLE0.day,AGG_TABLE0.hour,AGG_TABLE0.cellNum,AGG_TABLE0.location,cast(pmRrcConnCe0Max+pmRrcConnCe1Max+pmRrcConnCe2Max as decimal(18,2)) as kpi0 from (select convert(char(10),date_id) as day,hour_id as hour,NbIotCell as location,COUNT(DISTINCT(NbIotCell)) AS cellNum,sum(pmRrcConnCe0Max) as 'pmRrcConnCe0Max',sum(pmRrcConnCe1Max) as 'pmRrcConnCe1Max',sum(pmRrcConnCe2Max) as 'pmRrcConnCe2Max' from dc.DC_E_ERBS_NBIOTCELL_RAW  where date_id>='$startTime' and date_id<='$startTime' and $SN IN ($subNetWork) group by date_id,hour_id,location)as AGG_TABLE0  order by AGG_TABLE0.day,AGG_TABLE0.hour)t1,(select AGG_TABLE0.day,AGG_TABLE0.hour,cast((pmNpdcchCceUtil_0*2.5+pmNpdcchCceUtil_1*7.5+pmNpdcchCceUtil_2*12.5+pmNpdcchCceUtil_3*17.5+pmNpdcchCceUtil_4*22.5+pmNpdcchCceUtil_5*27.5+pmNpdcchCceUtil_6*32.5+pmNpdcchCceUtil_7*37.5+pmNpdcchCceUtil_8*42.5+pmNpdcchCceUtil_9*47.5+pmNpdcchCceUtil_10*52.5+pmNpdcchCceUtil_11*57.5+pmNpdcchCceUtil_12*62.5+pmNpdcchCceUtil_13*67.5+pmNpdcchCceUtil_14*72.5+pmNpdcchCceUtil_15*77.5+pmNpdcchCceUtil_16*82.5+pmNpdcchCceUtil_17*87.5+pmNpdcchCceUtil_18*92.5+pmNpdcchCceUtil_19*97.5)/(pmNpdcchCceUtil_0+pmNpdcchCceUtil_1+pmNpdcchCceUtil_2+pmNpdcchCceUtil_3+pmNpdcchCceUtil_4+pmNpdcchCceUtil_5+pmNpdcchCceUtil_6+pmNpdcchCceUtil_7+pmNpdcchCceUtil_8+pmNpdcchCceUtil_9+pmNpdcchCceUtil_10+pmNpdcchCceUtil_11+pmNpdcchCceUtil_12+pmNpdcchCceUtil_13+pmNpdcchCceUtil_14+pmNpdcchCceUtil_15+pmNpdcchCceUtil_16+pmNpdcchCceUtil_17+pmNpdcchCceUtil_18+pmNpdcchCceUtil_19) as decimal(18,2)) as kpi0 from (select convert(char(10),date_id) as day,hour_id as hour,sum(case DCVECTOR_INDEX when 0 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_0',sum(case DCVECTOR_INDEX when 1 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_1',sum(case DCVECTOR_INDEX when 2 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_2',sum(case DCVECTOR_INDEX when 3 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_3',sum(case DCVECTOR_INDEX when 4 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_4',sum(case DCVECTOR_INDEX when 5 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_5',sum(case DCVECTOR_INDEX when 6 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_6',sum(case DCVECTOR_INDEX when 7 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_7',sum(case DCVECTOR_INDEX when 8 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_8',sum(case DCVECTOR_INDEX when 9 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_9',sum(case DCVECTOR_INDEX when 10 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_10',sum(case DCVECTOR_INDEX when 11 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_11',sum(case DCVECTOR_INDEX when 12 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_12',sum(case DCVECTOR_INDEX when 13 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_13',sum(case DCVECTOR_INDEX when 14 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_14',sum(case DCVECTOR_INDEX when 15 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_15',sum(case DCVECTOR_INDEX when 16 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_16',sum(case DCVECTOR_INDEX when 17 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_17',sum(case DCVECTOR_INDEX when 18 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_18',sum(case DCVECTOR_INDEX when 19 then pmNpdcchCceUtil else 0 end) as 'pmNpdcchCceUtil_19' from dc.DC_E_ERBS_NBIOTCELL_V_RAW  where date_id>='$startTime' and date_id<='$startTime' and $SN IN ($subNetWork) group by date_id,hour_id)as AGG_TABLE0  order by AGG_TABLE0.day,AGG_TABLE0.hour)t2";
                        // echo $sql;exit;
                       
                        // $sql2 = "select max(t.kpi0) as npdcch from ";
                        $row = array();
                        $row = $pmDB->query($sql)->fetchall(PDO::FETCH_ASSOC);
                        // $row2 = $pmDB->query($sql2)->fetchall(PDO::FETCH_ASSOC);
     
                        if($row){
                            array_push($item,$row[0]);
                        }
                        // if($row1){
                        //     $row['pmRrcConnMax']=$row1[0]['pmRrcConnMax'];
                        // }

                        // if($row2){
                        //     $row['npdcch']=$row2[0]['npdcch'];
                        // }
                        

                }        

            }
                        // print_r($item);exit;
            if($item){
                $new_item = array();
                foreach ($item as $key => $value) {
                    $new_item['day_id'] = $startTime;
                    $new_item['rrc_users_cell'][] = $value['rrc_users_cell']?$value['rrc_users_cell']:'0';
                    $new_item['npdcch'][] = $value['npdcch']?$value['npdcch']:'0';
                }
                // print_r($new_item);exit;
                $B_L_NBIOT = new B_L_NBIOT;
                $B_L_NBIOT->day_id = $new_item['day_id'];
                $B_L_NBIOT->location=$values['cityChinese'];
                $B_L_NBIOT->rrc_users_cell=max($new_item['rrc_users_cell']);
                $B_L_NBIOT->npdcch=max($new_item['npdcch']);
                $B_L_NBIOT->save();
            }

            // var_dump(max($new_item['pmRrcConnMax']));


        }
    }
}
