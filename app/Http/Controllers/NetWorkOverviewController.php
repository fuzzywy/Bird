<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\Network;
use App\Models\GSM;
use App\Models\NBIOT;
use App\Models\LTE_FDD;
use App\Models\LTE_TDD;
use App\Models\VOLTE_FDD;
use App\Models\VOLTE_TDD;
use App\Models\B_S_GSM;
use App\Models\B_S_LTE_NBIOT;
use App\Models\B_S_LTE_FDD;
use App\Models\B_S_LTE_TDD;
use App\Models\B_L_GSM_Day;
use App\Models\B_L_NBIOT;
use App\Models\B_L_FDD;
use App\Models\B_L_TDD;
use App\Models\City;
use App\Models\Limits;
use Illuminate\Support\Facades\DB;
use PDO;
use App;
class NetworkOverviewController extends Controller
{
    public function getBirdSideBar() {
        $arr = array(
                array("id" => 0, "Content" => "指标概览", "routertag" => "indexoverview" ),
                array("id" => 1, "Content" => "规模概览", "routertag" => "scaleoverview" ),
                array("id" => 2, "Content" => "负荷概览", "routertag" => "loadoverview")
            );
        return $arr;
    }

    public function getCity() {
        $cityList = City::select()->get()->toArray();
        $arr=array(array("id"=>0,"name"=>trans("message.province"), "state"=>true));
        foreach ($cityList as $key => $value) {
            array_push($arr, array("id"=>$key+1,"name"=>trans("message.city.".$value['cityChinese']), "state"=>false));
        }
        return $arr;
    }

    public function getLoadTabs() {
 		 $city = input::get('city');
        $overview = input::get('overview');
        $arr = [];
      
        if($city!="全省"&&$city!="province"){
            if (App::isLocale('en')) {
             $city = City::select()->where("connName",$city)->get()->toArray()[0]['cityChinese'];
            }
            $day_id =B_S_GSM::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();

            // $res = B_L_GSM_Day::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();

            $res = B_L_GSM_Day::where("location",$city)
                            ->where("day_id",function($query){
                                $query->select('day_id')->from("B_L_GSM_Day")->orderBy("day_id","desc")->limit(1);
                            })->get()->toArray();
                            // })
                            // ->toSql();
            // print_r($res);exit;
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len-3;$i++){
                $arr['GSMs'][$i]['id']=$i;
                $arr['GSMs'][$i]['name']=trans("message.loadSurvey.".$key[$i+3]);
                $arr['GSMs'][$i]['data']=round($res[0][$key[$i+3]],2);
                $arr['GSMs'][$i]['img'] = '/public/img/huihua.png';


            }
            // print_r($day_id[0]['day_id']);

             $res = B_L_TDD::where("location",$city)
                            ->where("day_id",function($query){
                                $query->select('day_id')->from("B_L_TDD")->orderBy("day_id","desc")->limit(1);
                            })->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);

            for($i=0;$i<$len-3;$i++){
                $arr['TDDLTEs'][$i]['id']=$i;
                $arr['TDDLTEs'][$i]['name']=trans("message.loadSurvey.".$key[$i+3]);
                $arr['TDDLTEs'][$i]['data']=round($res[0][$key[$i+3]],2);
                // $arr['TDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['TDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['TDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }

            }
             // $res = B_S_LTE_FDD::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();
            $res = B_L_FDD::where("location",$city)
                            ->where("day_id",function($query){
                                $query->select('day_id')->from("B_L_FDD")->orderBy("day_id","desc")->limit(1);
                            })->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);

            for($i=0;$i<$len-3;$i++){
                $arr['FDDLTEs'][$i]['id']=$i;
                $arr['FDDLTEs'][$i]['name']=trans("message.loadSurvey.".$key[$i+3]);
                $arr['FDDLTEs'][$i]['data']=round($res[0][$key[$i+3]],2);
                $arr['FDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['FDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }

            }
             $res = B_L_NBIOT::select()->where("location",$city)->where("day_id","2018-9-18")->limit(1)->get()->toArray();
             // print_r($res);exit;
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len-3;$i++){
                $arr['NBIOTs'][$i]['id']=$i;
                $arr['NBIOTs'][$i]['name']=trans("message.loadSurvey.".$key[$i+3]);
                $arr['NBIOTs'][$i]['data']=round($res[0][$key[$i+3]],2);
                $arr['NBIOTs'][$i]['img'] = '/public/img/huihua.png';


            }

        }else{
            $day_id =B_S_GSM::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();

            // $res = B_S_GSM::select()->where("day_id",$day_id[0]['day_id'])->sum('cell','erbs')->get()->toArray();
            // $res = B_S_GSM::select()
            //                 ->where("day_id",$day_id[0]['day_id'])
            //                 ->select(array(\DB::raw("sum(cell) as cell"),
            //                             \DB::raw("sum(erbs) as erbs")))
            //                 ->get()->toArray();
              $res = B_L_GSM_Day::where("day_id",function($query){
                                $query->select('day_id')->from("B_L_GSM_Day")->orderBy("day_id","desc")->limit(1);
                            })->select(array(\DB::raw("avg(tel_traffic) as tel_traffic"),
                                            \DB::raw("avg(data_traffic) as data_traffic"),
                                            \DB::raw("avg(tel_traffic_tch) as tel_traffic_tch"),
                                            \DB::raw("avg(data_traffic_pdch) as data_traffic_pdch"),
                                            \DB::raw("avg(wireless_rate) as wireless_rate")))
                            ->get()->toArray();
                            // print_r($res);exit;
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['GSMs'][$i]['id']=$i;
                $arr['GSMs'][$i]['name']=trans("message.loadSurvey.".$key[$i]);
                $arr['GSMs'][$i]['data']=round($res[0][$key[$i]],2);
                $arr['GSMs'][$i]['img'] = '/public/img/huihua.png';


            }

            $day_id_TFN =B_S_LTE_TDD::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();
            // $res = B_S_LTE_TDD::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();
            // $res = B_S_LTE_TDD::select()
            //                 ->where("day_id",$day_id[0]['day_id'])
            //                 ->select(array(\DB::raw("sum(carrier) as carrier"),
            //                             \DB::raw("sum(cell) as cell"),
            //                             \DB::raw("sum(erbs) as erbs"),
            //                             \DB::raw("sum(high_peed) as high_peed"),
            //                             \DB::raw("sum(co_enhance) as co_enhance"),
            //                             \DB::raw("sum(ca_agg) as ca_agg")))
            //                 ->get()->toArray();

             $res = B_L_TDD::where("day_id",function($query){
                                $query->select('day_id')->from("B_L_TDD")->orderBy("day_id","desc")->limit(1);
                            })->select(array(\DB::raw("avg(flow) as flow"),
                                            \DB::raw("avg(volte_traffic) as volte_traffic"),
                                            \DB::raw("avg(rrc_users) as rrc_users"),
                                            \DB::raw("avg(rrc_cell_user_mean) as rrc_cell_user_mean"),
                                            \DB::raw("avg(rrc_cell_users_max) as rrc_cell_users_max"),
                                            \DB::raw("avg(rrc_cell_band_users) as rrc_cell_band_users"),
                                            \DB::raw("avg(cce) as cce"),
                                            \DB::raw("avg(prb) as prb")
                                        ))
                            ->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['TDDLTEs'][$i]['id']=$i;
                $arr['TDDLTEs'][$i]['name']=trans("message.loadSurvey.".$key[$i]);
                $arr['TDDLTEs'][$i]['data']=round($res[0][$key[$i]],2);
                // $arr['TDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['TDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['TDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }

            }
            // $res = B_S_LTE_FDD::select()
            //                 ->where("day_id",$day_id[0]['day_id'])
            //                 ->select(array(\DB::raw("sum(carrier) as carrier"),
            //                             \DB::raw("sum(cell) as cell"),
            //                             \DB::raw("sum(erbs) as erbs"),
            //                             \DB::raw("sum(high_peed) as high_peed"),
            //                             \DB::raw("sum(co_enhance) as co_enhance"),
            //                             \DB::raw("sum(ca_agg) as ca_agg")))
            //                 ->get()->toArray();
             $res = B_L_FDD::where("day_id",function($query){
                                $query->select('day_id')->from("B_L_FDD")->orderBy("day_id","desc")->limit(1);
                            })->select(array(\DB::raw("avg(flow) as flow"),
                                            \DB::raw("avg(volte_traffic) as volte_traffic"),
                                            \DB::raw("avg(rrc_users) as rrc_users"),
                                            \DB::raw("avg(rrc_cell_user_mean) as rrc_cell_user_mean"),
                                            \DB::raw("avg(rrc_cell_users_max) as rrc_cell_users_max"),
                                            \DB::raw("avg(rrc_cell_band_users) as rrc_cell_band_users"),
                                            \DB::raw("avg(cce) as cce"),
                                            \DB::raw("avg(prb) as prb")
                                        ))
                            ->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['FDDLTEs'][$i]['id']=$i;
                $arr['FDDLTEs'][$i]['name']=trans("message.loadSurvey.".$key[$i]);
                $arr['FDDLTEs'][$i]['data']=round($res[0][$key[$i]],2);
                // $arr['FDDLTEs'][$i]['data']=3312;//intval($res[0][$key[$i]]);
                // $arr['FDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['FDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['FDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }


            }
  
            // $res = B_L_NBIOT::select()->where("location",$city)->where("day_id","2018-9-18")->limit(1)->get()->toArray();
            // $res = B_L_NBIOT::select()
             $res = B_L_NBIOT::where("day_id",function($query){
                                $query->select('day_id')->from("B_L_NBIOT")->orderBy("day_id","desc")->limit(1);
                            })->select(array(
                                        \DB::raw("max(rrc_users_cell) as rrc_users_cell"),
                                        \DB::raw("max(npdcch) as npdcch")))
                            ->get()->toArray();
            // print_r($res);exit;
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['NBIOTs'][$i]['id']=$i;
                $arr['NBIOTs'][$i]['name']=trans("message.loadSurvey.".$key[$i]);
                $arr['NBIOTs'][$i]['data']=round($res[0][$key[$i]],2);
                $arr['NBIOTs'][$i]['img'] = '/public/img/huihua.png';


            }
        }

        //每行显示个数GSMs
        $num = 12/count($arr['GSMs']);
        if ( $num < 3 ) {
            $num = 4;
        }
        // print_r($num);exit;
        for ($i=0; $i < count($arr['GSMs']); $i++) { 
            $arr['GSMs'][$i]['col'] = 'col-'. $num;
        }

        //每行显示个数GSMs
        $num = 12/count($arr['TDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['TDDLTEs']); $i++) { 
            $arr['TDDLTEs'][$i]['col'] = 'col-'. $num;
        }

        //每行显示个数GSMs
        $num = 12/count($arr['FDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['FDDLTEs']); $i++) { 
            $arr['FDDLTEs'][$i]['col'] = 'col-'. $num;
        }
        //每行显示个数GSMs
        $num = 12/count($arr['NBIOTs']);
        if ( $num < 8 ) {
            $num = 6;
        }
        for ($i=0; $i < count($arr['NBIOTs']); $i++) { 
            $arr['NBIOTs'][$i]['col'] = 'col-'. $num;
        }

        return $arr;
    }

    public function getScaleTabs() {

        $city = input::get('city');
        $overview = input::get('overview');
        $arr = [];
      
        if($city!="全省"&&$city!="province"){
            if (App::isLocale('en')) {
             $city = City::select()->where("connName",$city)->get()->toArray()[0]['cityChinese'];
            }
            $day_id =B_S_GSM::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();

            $res = B_S_GSM::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();

            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len-3;$i++){
                $arr['GSMs'][$i]['id']=$i;
                $arr['GSMs'][$i]['name']=trans("message.scale.".$key[$i+3]);
                $arr['GSMs'][$i]['data']=$res[0][$key[$i+3]];
                $arr['GSMs'][$i]['img'] = '/public/img/huihua.png';


            }
            // print_r($day_id[0]['day_id']);
            $day_id_TFN =B_S_LTE_TDD::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();
            $res = B_S_LTE_TDD::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();

            $key = array_keys($res[0]);
            $len = count($key);
            // for($i=0;$i<$len-3;$i++){
            //     $arr['TDDLTEs'][$i]['id']=$i;
            //     $arr['TDDLTEs'][$i]['name']=trans("message.scale.".$key[$i+3]);
            //     $arr['TDDLTEs'][$i]['data']=$res[0][$key[$i+3]];
            //     $arr['TDDLTEs'][$i]['img'] = '/public/img/huihua.png';


            // }
            for($i=0;$i<$len-3;$i++){
                $arr['TDDLTEs'][$i]['id']=$i;
                $arr['TDDLTEs'][$i]['name']=trans("message.scale.".$key[$i+3]);
                $arr['TDDLTEs'][$i]['data']=intval($res[0][$key[$i+3]]);
                $arr['TDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['TDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['TDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }

            }
             $res = B_S_LTE_FDD::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();

            $key = array_keys($res[0]);
            $len = count($key);
            // for($i=0;$i<$len-3;$i++){
            //     $arr['FDDLTEs'][$i]['id']=$i;
            //     $arr['FDDLTEs'][$i]['name']=trans("message.scale.".$key[$i+3]);
            //     $arr['FDDLTEs'][$i]['data']=$res[0][$key[$i+3]];
            //     $arr['FDDLTEs'][$i]['img'] = '/public/img/huihua.png';


            // }
            for($i=0;$i<$len-3;$i++){
                $arr['FDDLTEs'][$i]['id']=$i;
                $arr['FDDLTEs'][$i]['name']=trans("message.scale.".$key[$i+3]);
                $arr['FDDLTEs'][$i]['data']=intval($res[0][$key[$i+3]]);
                $arr['FDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['FDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['FDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }

            }
             $res = B_S_LTE_NBIOT::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();

            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len-3;$i++){
                $arr['NBIOTs'][$i]['id']=$i;
                $arr['NBIOTs'][$i]['name']=trans("message.scale.".$key[$i+3]);
                $arr['NBIOTs'][$i]['data']=$res[0][$key[$i+3]];
                $arr['NBIOTs'][$i]['img'] = '/public/img/huihua.png';


            }

        }else{
            $day_id =B_S_GSM::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();

            // $res = B_S_GSM::select()->where("day_id",$day_id[0]['day_id'])->sum('cell','erbs')->get()->toArray();
            $res = B_S_GSM::select()
                            ->where("day_id",$day_id[0]['day_id'])
                            ->select(array(\DB::raw("sum(cell) as cell"),
                                        \DB::raw("sum(erbs) as erbs")))
                            ->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['GSMs'][$i]['id']=$i;
                $arr['GSMs'][$i]['name']=trans("message.scale.".$key[$i]);
                $arr['GSMs'][$i]['data']=$res[0][$key[$i]];
                $arr['GSMs'][$i]['img'] = '/public/img/huihua.png';


            }

            $day_id_TFN =B_S_LTE_TDD::select("day_id")->orderBy("id","desc")->limit(1)->get()->toArray();
            $res = B_S_LTE_TDD::select()->where("location",$city)->where("day_id",$day_id[0]['day_id'])->limit(1)->get()->toArray();
            $res = B_S_LTE_TDD::select()
                            ->where("day_id",$day_id[0]['day_id'])
                            ->select(array(\DB::raw("sum(carrier) as carrier"),
                                        \DB::raw("sum(cell) as cell"),
                                        \DB::raw("sum(erbs) as erbs"),
                                        \DB::raw("sum(high_peed) as high_peed"),
                                        \DB::raw("sum(co_enhance) as co_enhance"),
                                        \DB::raw("sum(ca_agg) as ca_agg")))
                            ->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['TDDLTEs'][$i]['id']=$i;
                $arr['TDDLTEs'][$i]['name']=trans("message.scale.".$key[$i]);
                $arr['TDDLTEs'][$i]['data']=intval($res[0][$key[$i]]);
                $arr['TDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['TDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['TDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }

            }
            $res = B_S_LTE_FDD::select()
                            ->where("day_id",$day_id[0]['day_id'])
                            ->select(array(\DB::raw("sum(carrier) as carrier"),
                                        \DB::raw("sum(cell) as cell"),
                                        \DB::raw("sum(erbs) as erbs"),
                                        \DB::raw("sum(high_peed) as high_peed"),
                                        \DB::raw("sum(co_enhance) as co_enhance"),
                                        \DB::raw("sum(ca_agg) as ca_agg")))
                            ->get()->toArray();

            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['FDDLTEs'][$i]['id']=$i;
                $arr['FDDLTEs'][$i]['name']=trans("message.scale.".$key[$i]);
                $arr['FDDLTEs'][$i]['data']=intval($res[0][$key[$i]]);
                // $arr['FDDLTEs'][$i]['data']=3312;//intval($res[0][$key[$i]]);
                $arr['FDDLTEs'][$i]['max']=intval($res[0]['erbs']);
                $arr['FDDLTEs'][$i]['img'] = '/public/img/huihua.png';
                if ( $i > 2 ) {
                    $arr['FDDLTEs'][$i]['img'] = '/public/img/xinhao.png';
                }


            }
             $res = B_S_LTE_NBIOT::select()
                            ->where("day_id",$day_id[0]['day_id'])
                            ->select(array(
                                        \DB::raw("sum(cell) as cell"),
                                        \DB::raw("sum(erbs) as erbs")))
                            ->get()->toArray();
            $key = array_keys($res[0]);
            $len = count($key);
            for($i=0;$i<$len;$i++){
                $arr['NBIOTs'][$i]['id']=$i;
                $arr['NBIOTs'][$i]['name']=trans("message.scale.".$key[$i]);
                $arr['NBIOTs'][$i]['data']=$res[0][$key[$i]];
                $arr['NBIOTs'][$i]['img'] = '/public/img/huihua.png';


            }
        }

        //每行显示个数GSMs
        $num = 12/count($arr['GSMs']);
        if ( $num < 3 ) {
            $num = 3;
        }
        for ($i=0; $i < count($arr['GSMs']); $i++) { 
            $arr['GSMs'][$i]['col'] = 'col-'. $num;
        }

        //每行显示个数GSMs
        $num = 12/count($arr['TDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['TDDLTEs']); $i++) { 
            $arr['TDDLTEs'][$i]['col'] = 'col-'. $num;
        }

        //每行显示个数GSMs
        $num = 12/count($arr['FDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['FDDLTEs']); $i++) { 
            $arr['FDDLTEs'][$i]['col'] = 'col-'. $num;
        }
        //每行显示个数GSMs
        $num = 12/count($arr['NBIOTs']);
        if ( $num < 8 ) {
            $num = 6;
        }
        for ($i=0; $i < count($arr['NBIOTs']); $i++) { 
            $arr['NBIOTs'][$i]['col'] = 'col-'. $num;
        }

        return $arr;

    }
    
    public function getTabs() {
    	
        
        $data = input::get('data',"LTE"); //type
        $city = input::get('city',"全省"); //city
        $dbc = new DataBaseConnection();
        $db = $dbc->getDB("Bird");
        if($city!="全省"&&$city!="province"){
          $dbc = new DataBaseConnection();
          $cityConnmame= $dbc->getConnName($db,$city);
        }
        // $data ="GSM";
        $overview = input::get('overview');
        // $db = new PDO("mysql:host=10.39.148.186;port=13306;dbname=Bird", 'root', 'mongs');

        if($data=="GSM"){
                if($city=="全省"||$city=="province"){
                    $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_GSM` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                    $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                    $arr = $this->getCompare($result,$data,$overview);
                }else{
                    $result= GSM::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                    $arr = $this->getCompare($result,$data,$overview);  
                }
        }elseif($data=="LTE"){
                if($city=="全省"||$city=="province"){
                    $sql1 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_LTE_FDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                    $sql2 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_LTE_TDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                    $result1 = $db->query($sql1)->fetchall(PDO::FETCH_ASSOC);
                    $result2 = $db->query($sql2)->fetchall(PDO::FETCH_ASSOC);
                    $result = $this->getAvg($result1,$result2);
                    $arr = $this->getCompare($result,$data,$overview);
                }else{
                    $result1= LTE_TDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                    $result2= LTE_FDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                    $result = $this->getAvg($result1,$result2);
                    $arr = $this->getCompare($result,$data,$overview);  
                }
        }elseif($data=="VOLTE"){
                if($city=="全省"||$city=="province"){
                    $sql1 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover,round(avg(srvcc),2) AS srvcc,round(avg(upackagelost),2) AS upackagelost,round(avg(dpackagelost),2) AS dpackagelost FROM `B_VOLTE_FDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                    $sql2 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover,round(avg(srvcc),2) AS srvcc,round(avg(upackagelost),2) AS upackagelost,round(avg(dpackagelost),2) AS dpackagelost  FROM `B_VOLTE_TDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                    $result1 = $db->query($sql1)->fetchall(PDO::FETCH_ASSOC);
                    $result2 = $db->query($sql2)->fetchall(PDO::FETCH_ASSOC);
                    $result = $this->getAvg($result1,$result2);
                    $arr = $this->getCompare($result,$data,$overview);
                }else{
                    $result1= VOLTE_TDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                    $result2= VOLTE_FDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                    $result = $this->getAvg($result1,$result2);
                    $arr = $this->getCompare($result,$data,$overview);  
                }
        }elseif($data=="NBIOT"){
             if($city=="全省"||$city=="province"){
                    $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access FROM `B_NBIOT` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                    $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                    $arr = $this->getCompare($result,$data,$overview);
                }else{
                    $result= NBIOT::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                    $arr = $this->getCompare($result,$data,$overview);  
                }
        }

      

        return json_encode(array_reverse($arr));
        
    }
    public function getAvg($result1,$result2){
        $res = array();
        if($result1&&$result2){
            $num = count($result1);
            $len = count($result1[0]);
            $key = array_keys($result1[0]);
            for ($j=0; $j <$num ; $j++) { 
                for($i=0;$i<$len;$i++){
                $res[$j][$key[$i]] = $result1[$j][$key[$i]];
                    if($i>=4){
                        $res[0][$key[$i]] = round(($result1[0][$key[$i]]+$result2[0][$key[$i]])/2,2);
                    }
                }
            }
            // print_r($res);exit;
        return $res;

        }elseif($result1){
            return $result1;
        }else{
            return $result2;
        }
    }
    public function getCompare($result,$type,$overview){
        $limit = array();
        $row = Limits::select()->where("type",$type)->get()->toArray();
        if($row){
            foreach ($row as $key => $value) {
                $limit[$value['name']]['value']=$value['value']?$value['value']:'0';
                $limit[$value['name']]['operate']=$value['operate'];
            }
        }
        // print_r($limit);exit;
        // print_r($type);
        // print_r($result);exit;
        $data = array();
        $len = count($result[0]);
        $key = array_keys($result[0]);
        // print_r($key);exit;
        $a = array('icon-ali-jiantou_xiangxia', 'icon-ali-jiantou_xiangshang', 'icon-ali-jianhao');
        $arr =array();
        $id=0;
        for($i=4;$i<$len;$i++){
            if($result[1][$key[$i]]>$result[0][$key[$i]]){
                $arr[$id]['class']='icon-ali-jiantouxiajiang-red';
                $arr[$id]['tend'] = round((($result[1][$key[$i]]-$result[0][$key[$i]])),2)."%";
            }elseif($result[1][$key[$i]]<$result[0][$key[$i]]){
                $arr[$id]['class']='icon-ali-jiantoushangsheng-blue';
                $arr[$id]['tend'] = round((($result[0][$key[$i]]-$result[1][$key[$i]])),2)."%";
            }else{
                $arr[$id]['class']='icon-ali-jianhao';
                $arr[$id]['tend'] = '0%';
            }
            $arr[$id]["type"] = trans("message.overview.".$key[$i]);
            // $arr[$id]["type"] = $city."-".$key[$i]."-".$overview;
            $arr[$id]["data"] = round($result[0][$key[$i]],2)."%";
            $arr[$id]['id']=$id;
            $arr[$id]['color']="red"; 
            if($limit){
                $str =$result[0][$key[$i]].$limit[$key[$i]]['operate'].$limit[$key[$i]]['value'];
                // var_dump(eval("return $str;"));
                // echo $str;
                if(eval("return $str;")){
                    $arr[$id]['color']="blue"; 
                }
                // var_dump($result[0][$key[$i]]."\"$limit[$key[$i]]['operate']\""."'".$limit[$key[$i]]['value']."'");
                // if($result[0][$key[$i]]."\"$limit[$key[$i]]['operate']\""."'".$limit[$key[$i]]['value']."'"){
                //     $arr[$id]['color']="red"; 
                // }
                
            }
            // if($result[0][$key[$i]] .$sss[$key[$i]]['operate'].$sss[$key[$i]]['value'])

            $id++;
        }
        return $arr;
        // foreach ($result as $key => $value) {
        //     $
        // }


         // for($i=)

    }

    public function getcharts() {
        $data = input::get('data',"LTE");
        $city = input::get('city',"全省");
        $overview = input::get('overview');
        // $db = new PDO("mysql:host=10.39.148.186;port=13306;dbname=Bird", 'root', 'mongs');
        $dbc = new DataBaseConnection();
        $db = $dbc->getDB("Bird");
        if($city!="全省"&&$city!="province"){
            $dbc = new DataBaseConnection();
            $cityConnmame= $dbc->getConnName($db,$city);
        }
        if($overview=="indexoverview"){
            if($data=="GSM"){
                if($city=="全省"||$city=="province"){
                     $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_GSM` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                }else{
                    $result= GSM::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                }
            }elseif($data=="LTE"){
                 if($city=="全省"||$city=="province"){
                        $sql1 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_LTE_FDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $sql2 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_LTE_TDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $result1 = $db->query($sql1)->fetchall(PDO::FETCH_ASSOC);
                        $result2 = $db->query($sql2)->fetchall(PDO::FETCH_ASSOC);
                        $result = $this->getAvg($result1,$result2);
                    }else{
                        $result1= LTE_TDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                        $result2= LTE_FDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                        $result = $this->getAvg($result1,$result2);
                    }
            }elseif($data=="VOLTE"){
                if($city=="全省"||$city=="province"){
                        $sql1 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover,round(avg(srvcc),2) AS srvcc,round(avg(upackagelost),2) AS upackagelost,round(avg(dpackagelost),2) AS dpackagelost FROM `B_VOLTE_FDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $sql2 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover,round(avg(srvcc),2) AS srvcc,round(avg(upackagelost),2) AS upackagelost,round(avg(dpackagelost),2) AS dpackagelost  FROM `B_VOLTE_TDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $result1 = $db->query($sql1)->fetchall(PDO::FETCH_ASSOC);
                        $result2 = $db->query($sql2)->fetchall(PDO::FETCH_ASSOC);
                        $result = $this->getAvg($result1,$result2);
                    }else{
                        $result1= VOLTE_TDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                        $result2= VOLTE_FDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                        $result = $this->getAvg($result1,$result2);
                    }
            }elseif($data=="NBIOT"){
                if($city=="全省"||$city=="province"){
                        $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access FROM `B_NBIOT` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                    }else{
                        $result= NBIOT::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                    }
            }
            $result = array_reverse($result);
            $data= $this->getChart($result,$city,$overview,$data);
            return json_encode($data);
        }
    }


    public function getChart($result,$city,$overview,$type)
    {



        $datas = [];
        $title = array('text' => $type."-".$city , 'style' =>array('color' =>"#000000" ,"fontWeight"=>"bold" ));
        $subtitle = array('text' => $type."-".$city, 'style' =>array('color' =>"#ff0000" ));
        $ytitle = array('text' =>$type."-".$city, 'style' =>array('color' =>"#ff0000" ,"fontWeight"=>"bold" ));
        $xtitle = array('text' =>$type."-".$city, 'style' =>array('color' =>"#ff0000" ,"fontWeight"=>"bold" ));
        //x轴/Y轴/data
        $num = count($result);
        $len = count($result[0]);
        $key = array_keys($result[0]);
        for($i=0;$i<$num;$i++){
            $xcategories[] = date("m-d",strtotime($result[$i]['day_id']))." ".$result[$i]['hour_id'];
            for($j=4;$j<$len;$j++){
                $data[$len-$j-1][] = intval($result[$i][$key[$j]]*100)/100; 
                $name[$len-$j-1] = $key[$j]; 
            }
        }
        $ydata=array();
        foreach ($name as $key => $value) {
            $ydata[]=array('name'=>trans("message.overview.".$value),'data'=>$data[$key]);
        }
        $ycategories = array();

        $datas['title'] = $title;
        $datas['subtitle'] = '';
        $datas['ytitle'] = ' ';
        $datas['xtitle'] = ' ';
        $datas['xcategories'] = $xcategories;
        $datas['ycategories'] = $ycategories;
        $datas['ydata'] = $ydata;
        return $datas;
    }

}
