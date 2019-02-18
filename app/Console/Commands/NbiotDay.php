<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Databaseconns;
use App\Models\City;
use App\Models\NBIOT_DAY;
use App\Console\Commands\Bird\LteBackup;
use App\Http\Controllers\Common\DataBaseConnection;
use PDO;
class NbiotDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbiotday:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '指标概览NBIOT天级别数据备份';

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
        $db = $dbc->getDB("Bird");
        $result = City::select("connName")->get();
        $startTime = date("Y-m-d",strtotime("-1 day"));
        foreach ($result as $key => $values) {
         $B_NBIOT = new LteBackup("B_NBIOT_DAY","city","day",$values->connName,"NBIOT",$db);
         $citys = Databaseconns::select()->where('connName','like',$values->connName."%")->get()->toArray();
            $i=0;$j=0;
            foreach ($citys as $key => $value) {
                $subNetWork = $dbc->getSubNetsByconnName("NBIOT",$value['connName']);
                if($subNetWork){
                    $SN         = $dbc->getSN($value['connName']);
                    $dc         = $dbc->getDC($value['connName']);
                    $host     = $value['host'];
                    $port     = $value['port'];
                    $dbName   = $value['dbName'];
                    $userName = $value['userName'];
                    $password = $value['password'];
                    $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;
                    try {
                        $pmDB     = new PDO($pmDbDSN, $userName, $password);
                    } catch (\Exception $e) {
                        return;
                    }

                    $sql = "select AGG_TABLE0.day,AGG_TABLE0.cellNum,AGG_TABLE0.subNet,AGG_TABLE0.location,cast(10*log10(((power(10,(-121/10.0))*(pmRadioRecInterferencePwr_0))+(power(10,(-120.5/10.0))*(pmRadioRecInterferencePwr_1))+(power(10,(-119.5/10.0))*(pmRadioRecInterferencePwr_2))+(power(10,(-118.5/10.0))*(pmRadioRecInterferencePwr_3))+(power(10,(-117.5/10.0))*(pmRadioRecInterferencePwr_4))+(power(10,(-116.5/10.0))*(pmRadioRecInterferencePwr_5))+(power(10,(-115.5/10.0))*(pmRadioRecInterferencePwr_6))+(power(10,(-114.5/10.0))*(pmRadioRecInterferencePwr_7))+(power(10,(-113.5/10.0))*(pmRadioRecInterferencePwr_8))+(power(10,(-112.5/10.0))*(pmRadioRecInterferencePwr_9))+(power(10,(-110/10.0))*(pmRadioRecInterferencePwr_10))+(power(10,(-106/10.0))*(pmRadioRecInterferencePwr_11))+(power(10,(-102/10.0))*(pmRadioRecInterferencePwr_12))+(power(10,(-98/10.0))*(pmRadioRecInterferencePwr_13))+(power(10,(-94/10.0))*(pmRadioRecInterferencePwr_14))+(power(10,(-90/10.0))*(pmRadioRecInterferencePwr_15)))/((pmRadioRecInterferencePwr_0+pmRadioRecInterferencePwr_1+pmRadioRecInterferencePwr_2+pmRadioRecInterferencePwr_3+pmRadioRecInterferencePwr_4+pmRadioRecInterferencePwr_5+pmRadioRecInterferencePwr_6+pmRadioRecInterferencePwr_7+pmRadioRecInterferencePwr_8+pmRadioRecInterferencePwr_9+pmRadioRecInterferencePwr_10+pmRadioRecInterferencePwr_11+pmRadioRecInterferencePwr_12+pmRadioRecInterferencePwr_13+pmRadioRecInterferencePwr_14+pmRadioRecInterferencePwr_15))) as decimal(18,2)) as kpi0 from (select convert(char(10),date_id) as day,$SN as subNet,NbIotCell AS location,       COUNT(DISTINCT(NbIotCell)) AS cellNum,sum(case DCVECTOR_INDEX when 0 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_0',sum(case DCVECTOR_INDEX when 1 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_1',sum(case DCVECTOR_INDEX when 2 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_2',sum(case DCVECTOR_INDEX when 3 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_3',sum(case DCVECTOR_INDEX when 4 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_4',sum(case DCVECTOR_INDEX when 5 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_5',sum(case DCVECTOR_INDEX when 6 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_6',sum(case DCVECTOR_INDEX when 7 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_7',sum(case DCVECTOR_INDEX when 8 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_8',sum(case DCVECTOR_INDEX when 9 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_9',sum(case DCVECTOR_INDEX when 10 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_10',sum(case DCVECTOR_INDEX when 11 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_11',sum(case DCVECTOR_INDEX when 12 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_12',sum(case DCVECTOR_INDEX when 13 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_13',sum(case DCVECTOR_INDEX when 14 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_14',sum(case DCVECTOR_INDEX when 15 then pmRadioRecInterferencePwr else 0 end) as 'pmRadioRecInterferencePwr_15' from ".$dc."DC_E_ERBS_NBIOTCELL_V_RAW  where date_id>='$startTime' and date_id<='$startTime' and $SN in ($subNetWork) group by date_id,SN,location)as AGG_TABLE0  order by AGG_TABLE0.day";
                    $res = $pmDB->query($sql)->fetchall(PDO::FETCH_ASSOC);
                     if($res){
                        foreach ($res as $key => $value) {
                            $i++;
                            if($value['kpi0']&&$value['kpi0']>'-110'){
                                $j++;
                            }
                        }
                     }
                }
             

            }
            if($i==0){
                $num=0;
            }else{
                $num=$j*100/$i;
            }
            NBIOT_DAY::where(['location'=>$values->connName,'day_id'=>$startTime])->update(['interfererate'=>$num]);

        }
    }
}
