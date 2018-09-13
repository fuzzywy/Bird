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

        $startTime = date("Y-m-d",strtotime("-1 day"));
        $endTime   = date("Y-m-d");
        foreach ($cityList as $key => $value) {
            $host     = $value['host'];
            $port     = $value['port'];
            $dbName   = $value['dbName'];
            $userName = $value['userName'];
            $password = $value['password'];
            $pmDbDSN  = "dblib:host=".$host.":".$port.";dbname=".$dbName;
            $pmDB     = new PDO($pmDbDSN, $userName, $password);

            if($pmDB){
                $sql = "select CONVERT (CHAR(10), date_id) AS date_id,count(distinct BSC) as BSC,count(distinct CELL_NAME) as cell from dc.DC_E_BSS_CELL_PS_RAW WHERE date_id >= '$startTime' AND date_id < '$endTime' group by date_id" ;
                $res = $pmDB->query($sql)->fetchall(PDO::FETCH_ASSOC);  
                  if($res){
                    foreach ($res as $k => $v) {
                        $B_S_GSM = new B_S_GSM;
                        $B_S_GSM->day_id = $startTime;
                        $B_S_GSM->location=$value['cityChinese'];
                        $B_S_GSM->erbs    = $v['BSC']?$v['BSC']:'0';
                        $B_S_GSM->cell    = $v['cell']?$v['cell']:'0';
                        $B_S_GSM->save();

                    }
                }
                
            }

        }
    }
}
