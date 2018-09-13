<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\City;
use App\Models\B_S_LTE_NBIOT;
use App\Models\B_S_LTE_TDD;
use App\Models\B_S_LTE_FDD;
use App\Models\Kget\EUtranCellTDD;
use App\Models\Kget\EUtranCellFDD;
use App\Models\Kget\NbIotCell;
use App\Models\Kget\CoverageEnhancement;
use App\Models\Kget\TempParameterRRUAndSlaveCount;
use PDO;
class SLteExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SLte:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'S_LTE_TDD/FDD/NBIOT/GSM 备份';

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
        $kgetdb = $dbc->getKgetTime();
        // $kgetdb = "kget180909";
        $cityList = City::select("cityChinese")->get();

        $db = $dbc->getDB("kget",$kgetdb);
        // print_r($kgetdb);exit;
        //备份B_S_LTE_NBIOT
        foreach ($cityList as $key => $value) {

            $subnet = $dbc->getSubNetsArr("NBIOT",$value->cityChinese);

            $cell    = NbIotCell::whereIn('subNetWork',$subnet)->distinct('nbIotCellId')->count('nbIotCellId');
            $erbs    = NbIotCell::whereIn('subNetWork',$subnet)->distinct('meContext')->count('meContext');
   
            $NbIotCell = new B_S_LTE_NBIOT;
            $NbIotCell->day_id   = date("Y-m-d");
            $NbIotCell->location = $value->cityChinese;
            $NbIotCell->cell     = $cell;
            $NbIotCell->erbs     = $erbs;
            $NbIotCell->save();

        }

        //B_S_LTE_TDD

        foreach ($cityList as $key => $value) {
            $subnet = $dbc->getSubNetsArr("TDD",$value->cityChinese);
            $carrier = TempParameterRRUAndSlaveCount::whereIn('subNetWork',$subnet)->sum('sectorCarrierRef');
            $cell    = EUtranCellTDD::whereIn('subNetWork',$subnet)->distinct('eUtranCellTDDId')->count('eUtranCellTDDId');
            $erbs    = EUtranCellTDD::whereIn('subNetWork',$subnet)->distinct('meContext')->count('meContext');
            
            $high_peed=CoverageEnhancement::whereIn('subNetWork',$subnet)->where("functionName","高速移动性能")->distinct('meContext')->count('meContext');
            $co_enhance=CoverageEnhancement::whereIn('subNetWork',$subnet)->where("functionName","上下行覆盖增强功能")->distinct('meContext')->count('meContext');
            $ca_agg=CoverageEnhancement::whereIn('subNetWork',$subnet)->where("functionName","FDD+TDD载波聚合")->distinct('meContext')->count('meContext');
            // print_r($co_enhance)
            $EUtranCellTDD = new B_S_LTE_TDD;
            $EUtranCellTDD->day_id	   = date("Y-m-d");
            $EUtranCellTDD->location   = $value->cityChinese;
            $EUtranCellTDD->cell       = $cell;
            $EUtranCellTDD->erbs       = $erbs;
            $EUtranCellTDD->carrier    = $carrier;
            $EUtranCellTDD->high_peed  = $high_peed;
            $EUtranCellTDD->co_enhance = $co_enhance;
            $EUtranCellTDD->ca_agg     = $ca_agg;
            $EUtranCellTDD->save();        	
        }

        //B_S_LTE_FDD


       	foreach ($cityList as $key => $value) {
            $subnet = $dbc->getSubNetsArr("FDD",$value->cityChinese);
            $carrier = TempParameterRRUAndSlaveCount::whereIn('subNetWork',$subnet)->sum('sectorCarrierRef');
            $cell    = EUtranCellFDD::whereIn('subNetWork',$subnet)->distinct('eUtranCellFDDId')->count('eUtranCellFDDId');
            $erbs    = EUtranCellFDD::whereIn('subNetWork',$subnet)->distinct('meContext')->count('meContext');
            
            $high_peed=CoverageEnhancement::whereIn('subNetWork',$subnet)->where("functionName","高速移动性能")->distinct('meContext')->count('meContext');
            $co_enhance=CoverageEnhancement::whereIn('subNetWork',$subnet)->where("functionName","上下行覆盖增强功能")->distinct('meContext')->count('meContext');
            $ca_agg=CoverageEnhancement::whereIn('subNetWork',$subnet)->where("functionName","FDD+TDD载波聚合")->distinct('meContext')->count('meContext');
            // print_r($co_enhance)
            $EUtranCellFDD = new B_S_LTE_FDD;
            $EUtranCellFDD->day_id	   = date("Y-m-d");
            $EUtranCellFDD->location   = $value->cityChinese;
            $EUtranCellFDD->cell       = $cell;
            $EUtranCellFDD->erbs       = $erbs;
            $EUtranCellFDD->carrier    = $carrier;
            $EUtranCellFDD->high_peed  = $high_peed;
            $EUtranCellFDD->co_enhance = $co_enhance;
            $EUtranCellFDD->ca_agg     = $ca_agg;
            $EUtranCellFDD->save();        	
        }

    }
}
