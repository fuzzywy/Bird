<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Databaseconn2G;
use App\Models\B_S_GSM;
use App\Http\Controllers\Common\DataBaseConnection;
use PDO;
class SGsmExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SGsm:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'B_S_GSM 数据备份';

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

        $dsn = new DataBaseConnection();
        $cddDB = $dsn->getCddDB();
        $cddcon = $dsn->getDB("CDD",$cddDB);
        $startTime = date("Y-m-d",strtotime("-1 day"));
        $endTime   = date("Y-m-d");
        foreach ($cityList as $key => $value) {
            $B_S_GSM = new B_S_GSM;
            $B_S_GSM->day_id = $endTime;
            $B_S_GSM->location=$value['cityChinese'];
            if($dsn->tableIfExists($cddDB,'GSM_SERIAL_INFO')){
                $sql = "SELECT city,count(DISTINCT cell) as cell from GSM_SERIAL_INFO where city='".$value['cityChinese']."'";
                $cdd_res = $cddcon->query($sql)->fetch(PDO::FETCH_ASSOC);
                $B_S_GSM->cell    = $cdd_res['cell'];
                if($dsn->tableIfExists($cddDB,'RXMOP_RXOTRX')){
                    $sql="SELECT count(DISTINCT mo) as mo FROM `RXMOP_RXOTRX` where bsc in (SELECT distinct(bsc) from GSM_SERIAL_INFO where city='".$value['cityChinese']."');";
                    $carrier = $cddcon->query($sql)->fetch(PDO::FETCH_ASSOC);
                    $B_S_GSM->carrier    = $carrier['mo'];
                }
                
            }
            $host     = $value['host'];
            $port     = $value['port'];
            $dbName   = $value['dbName'];
            $userName = $value['userName'];
            $password = $value['password'];
            $pmDbDSN  = "dblib:host=".$host.":".$port.";dbname=".$dbName;

            try {
                
                $pmDB     = new PDO($pmDbDSN, $userName, $password);
            } catch (\Exception $e) {
                $B_S_GSM->save();
                return;
            }
           
            if($pmDB){

                $sql="SELECT AGG_TABLE0.day,round(SUM(cltch_tnuchcnt),2) as kpi0,round(SUM(CELLGPRS_ALLPDCHACTACC/(CELLGPRS_ALLPDCHSCAN)),2) as kpi1 FROM (SELECT CONVERT(char(10),date_id) as day, sum(cltch_tnuchcnt) as 'cltch_tnuchcnt' from dc.DC_E_BSS_CELL_CS_RAW where date_id>='$startTime' and date_id<='$startTime' AND TIMELEVEL='HOUR' GROUP BY day)as AGG_TABLE0 left join(SELECT CONVERT(char(10),date_id) as day, sum(CELLGPRS_ALLPDCHACTACC) as 'CELLGPRS_ALLPDCHACTACC', max(CELLGPRS_ALLPDCHSCAN) as 'CELLGPRS_ALLPDCHSCAN' from dc.DC_E_BSS_CELL_PS_RAW where date_id>='$startTime' and date_id<='$startTime' AND TIMELEVEL='HOUR' GROUP BY day)as AGG_TABLE1 on AGG_TABLE0.day = AGG_TABLE1.day GROUP BY AGG_TABLE0.day ORDER BY AGG_TABLE0.day";


                $res = $pmDB->query($sql)->fetch(PDO::FETCH_ASSOC);  
                  if($res){
                    $B_S_GSM->tch     = $res['kpi0'];
                    $B_S_GSM->pdch    = $res['kpi1'];
                    $B_S_GSM->save();


                }
                
            }

        }

    }
}
