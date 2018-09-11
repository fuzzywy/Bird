<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\Network;
use App\Models\GSM;
use App\Models\NBIOT;
use App\Models\LTE_FDD;
use App\Models\LTE_TDD;
use App\Models\VOLTE_FDD;
use App\Models\VOLTE_TDD;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use PDO;
use App;
class NetworkOverviewController extends Controller
{
    public function getBirdSideBar() {
        sleep(1);
        $arr = array(
                array("id" => 0, "Content" => "指标概览", "routertag" => "indexoverview" ),
                array("id" => 1, "Content" => "规模概览", "routertag" => "scaleoverview" ),
                array("id" => 2, "Content" => "负荷概览", "routertag" => "loadoverview")
            );
        return $arr;
    }

    public function getCity() {
        sleep(1);
        $arr = array(
                    array("id" => 0, "name" => "全省"), 
                    array("id" => 1, "name" => "常州"),
                    array("id" => 2, "name" => "苏州"),
                    array("id" => 3, "name" => "无锡"),
                    array("id" => 4, "name" => "南通"),
                    array("id" => 5, "name" => "镇江")
                );
        return $arr;
    }

    public function getLoadTabs() {
        sleep(1);
        $city = input::get('city');
        $overview = input::get('overview');
        $arr = [];
        $arr['TDDLTEs'][0]['id'] = 0;
        $arr['TDDLTEs'][0]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][0]['data'] = rand(1,100000);
        $arr['TDDLTEs'][0]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][1]['id'] = 1;
        $arr['TDDLTEs'][1]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][1]['data'] = rand(1,100000);
        $arr['TDDLTEs'][1]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][2]['id'] = 2;
        $arr['TDDLTEs'][2]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][2]['data'] = rand(1,100000);
        $arr['TDDLTEs'][2]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][3]['id'] = 3;
        $arr['TDDLTEs'][3]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][3]['data'] = rand(1,100000);
        $arr['TDDLTEs'][3]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][4]['id'] = 4;
        $arr['TDDLTEs'][4]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][4]['data'] = rand(1,100000);
        $arr['TDDLTEs'][4]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][5]['id'] = 5;
        $arr['TDDLTEs'][5]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][5]['data'] = rand(1,100000);
        $arr['TDDLTEs'][5]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][6]['id'] = 6;
        $arr['TDDLTEs'][6]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][6]['data'] = rand(1,100000);
        $arr['TDDLTEs'][6]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][7]['id'] = 7;
        $arr['TDDLTEs'][7]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][7]['data'] = rand(1,100000);
        $arr['TDDLTEs'][7]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][8]['id'] = 8;
        $arr['TDDLTEs'][8]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][8]['data'] = rand(1,100000);
        $arr['TDDLTEs'][8]['img'] = '/public/img/huihua.png';
        //每行显示个数TDDLTEs
        $num = 12/count($arr['TDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['TDDLTEs']); $i++) { 
            $arr['TDDLTEs'][$i]['col'] = 'col-'. $num;
        }

        $arr['FDDLTEs'][0]['id'] = 0;
        $arr['FDDLTEs'][0]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][0]['data'] = rand(1,100000);
        $arr['FDDLTEs'][0]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][1]['id'] = 1;
        $arr['FDDLTEs'][1]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][1]['data'] = rand(1,100000);
        $arr['FDDLTEs'][1]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][2]['id'] = 2;
        $arr['FDDLTEs'][2]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][2]['data'] = rand(1,100000);
        $arr['FDDLTEs'][2]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][3]['id'] = 3;
        $arr['FDDLTEs'][3]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][3]['data'] = rand(1,100000);
        $arr['FDDLTEs'][3]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][4]['id'] = 4;
        $arr['FDDLTEs'][4]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][4]['data'] = rand(1,100000);
        $arr['FDDLTEs'][4]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][5]['id'] = 5;
        $arr['FDDLTEs'][5]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][5]['data'] = rand(1,100000);
        $arr['FDDLTEs'][5]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][6]['id'] = 6;
        $arr['FDDLTEs'][6]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][6]['data'] = rand(1,100000);
        $arr['FDDLTEs'][6]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][7]['id'] = 7;
        $arr['FDDLTEs'][7]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][7]['data'] = rand(1,100000);
        $arr['FDDLTEs'][7]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][8]['id'] = 8;
        $arr['FDDLTEs'][8]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][8]['data'] = rand(1,100000);
        $arr['FDDLTEs'][8]['img'] = '/public/img/huihua.png';
        //每行显示个数FDDLTEs
        $num = 12/count($arr['FDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['FDDLTEs']); $i++) { 
            $arr['FDDLTEs'][$i]['col'] = 'col-'. $num;
        }

        $arr['NBIOTs'][0]['id'] = 0;
        $arr['NBIOTs'][0]['name'] = 'test'.rand(0,100);
        $arr['NBIOTs'][0]['data'] = rand(1,100000);
        $arr['NBIOTs'][0]['img'] = '/public/img/huihua.png';
        $arr['NBIOTs'][1]['id'] = 1;
        $arr['NBIOTs'][1]['name'] = 'test'.rand(0,100);
        $arr['NBIOTs'][1]['data'] = rand(1,100000);
        $arr['NBIOTs'][1]['img'] = '/public/img/huihua.png';
        //每行显示个数GSMs
        $num = 12/count($arr['NBIOTs']);
        if ( $num < 8 ) {
            $num = 6;
        }
        for ($i=0; $i < count($arr['NBIOTs']); $i++) { 
            $arr['NBIOTs'][$i]['col'] = 'col-'. $num;
        }

        $arr['GSMs'][0]['id'] = 0;
        $arr['GSMs'][0]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][0]['data'] = rand(1,100000);
        $arr['GSMs'][0]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][1]['id'] = 1;
        $arr['GSMs'][1]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][1]['data'] = rand(1,100000);
        $arr['GSMs'][1]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][2]['id'] = 2;
        $arr['GSMs'][2]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][2]['data'] = rand(1,100000);
        $arr['GSMs'][2]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][3]['id'] = 3;
        $arr['GSMs'][3]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][3]['data'] = rand(1,100000);
        $arr['GSMs'][3]['img'] = '/public/img/huihua.png';
        //每行显示个数GSMs
        $num = 12/count($arr['GSMs']);
        if ( $num < 3 ) {
            $num = 3;
        }
        for ($i=0; $i < count($arr['GSMs']); $i++) { 
            $arr['GSMs'][$i]['col'] = 'col-'. $num;
        }
        return $arr;
    }

    public function getScaleTabs() {
        sleep(1);
        $city = input::get('city');
        $overview = input::get('overview');
        $arr = [];
        $arr['GSMs'][0]['id'] = 0;
        $arr['GSMs'][0]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][0]['data'] = rand(1,100000);
        $arr['GSMs'][0]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][1]['id'] = 1;
        $arr['GSMs'][1]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][1]['data'] = rand(1,100000);
        $arr['GSMs'][1]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][2]['id'] = 2;
        $arr['GSMs'][2]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][2]['data'] = rand(1,100000);
        $arr['GSMs'][2]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][3]['id'] = 3;
        $arr['GSMs'][3]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][3]['data'] = rand(1,100000);
        $arr['GSMs'][3]['img'] = '/public/img/huihua.png';
        $arr['GSMs'][4]['id'] = 4;
        $arr['GSMs'][4]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][4]['data'] = rand(1,100000);
        $arr['GSMs'][4]['img'] = '/public/img/huihua.png';
        //每行显示个数GSMs
        $num = 12/count($arr['GSMs']);
        if ( $num < 3 ) {
            $num = 3;
        }
        for ($i=0; $i < count($arr['GSMs']); $i++) { 
            $arr['GSMs'][$i]['col'] = 'col-'. $num;
        }

        $arr['TDDLTEs'][0]['id'] = 0;
        $arr['TDDLTEs'][0]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][0]['data'] = rand(1,100000);
        $arr['TDDLTEs'][0]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][1]['id'] = 1;
        $arr['TDDLTEs'][1]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][1]['data'] = rand(1,100000);
        $arr['TDDLTEs'][1]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][2]['id'] = 2;
        $arr['TDDLTEs'][2]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][2]['data'] = rand(1,100000);
        $arr['TDDLTEs'][2]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][3]['id'] = 3;
        $arr['TDDLTEs'][3]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][3]['data'] = rand(1,100000);
        $arr['TDDLTEs'][3]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][4]['id'] = 4;
        $arr['TDDLTEs'][4]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][4]['data'] = rand(1,100000);
        $arr['TDDLTEs'][4]['img'] = '/public/img/huihua.png';
        $arr['TDDLTEs'][5]['id'] = 5;
        $arr['TDDLTEs'][5]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][5]['data'] = rand(1,100000);
        $arr['TDDLTEs'][5]['img'] = '/public/img/huihua.png';

        //每行显示个数GSMs
        $num = 12/count($arr['TDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['TDDLTEs']); $i++) { 
            $arr['TDDLTEs'][$i]['col'] = 'col-'. $num;
        }

        $arr['FDDLTEs'][0]['id'] = 0;
        $arr['FDDLTEs'][0]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][0]['data'] = rand(1,100000);
        $arr['FDDLTEs'][0]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][1]['id'] = 1;
        $arr['FDDLTEs'][1]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][1]['data'] = rand(1,100000);
        $arr['FDDLTEs'][1]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][2]['id'] = 2;
        $arr['FDDLTEs'][2]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][2]['data'] = rand(1,100000);
        $arr['FDDLTEs'][2]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][3]['id'] = 3;
        $arr['FDDLTEs'][3]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][3]['data'] = rand(1,100000);
        $arr['FDDLTEs'][3]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][4]['id'] = 4;
        $arr['FDDLTEs'][4]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][4]['data'] = rand(1,100000);
        $arr['FDDLTEs'][4]['img'] = '/public/img/huihua.png';
        $arr['FDDLTEs'][5]['id'] = 5;
        $arr['FDDLTEs'][5]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][5]['data'] = rand(1,100000);
        $arr['FDDLTEs'][5]['img'] = '/public/img/huihua.png';

        //每行显示个数GSMs
        $num = 12/count($arr['FDDLTEs']);
        if ( $num < 6 ) {
            $num = 4;
        }
        for ($i=0; $i < count($arr['FDDLTEs']); $i++) { 
            $arr['FDDLTEs'][$i]['col'] = 'col-'. $num;
        }

        $arr['NBIOTs'][0]['id'] = 0;
        $arr['NBIOTs'][0]['name'] = 'test'.rand(0,100);
        $arr['NBIOTs'][0]['data'] = rand(1,100000);
        $arr['NBIOTs'][0]['img'] = '/public/img/huihua.png';
        $arr['NBIOTs'][1]['id'] = 1;
        $arr['NBIOTs'][1]['name'] = 'test'.rand(0,100);
        $arr['NBIOTs'][1]['data'] = rand(1,100000);
        $arr['NBIOTs'][1]['img'] = '/public/img/huihua.png';
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
        if($city!="全省"){
          $dbc = new DataBaseConnection();
          $cityConnmame= $dbc->getConnName($db,$city);
        }
        // $data ="GSM";
        $overview = input::get('overview');
        // $db = new PDO("mysql:host=10.39.148.186;port=13306;dbname=Bird", 'root', 'mongs');

        if($overview=="indexoverview"){
            if($data=="GSM"){
                    if($city=="全省"){
                        $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_GSM` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                        $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                        $arr = $this->getCompare($result,$city,$overview);
                    }else{
                        $result= GSM::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                        $arr = $this->getCompare($result,$city,$overview);  
                    }
            }elseif($data=="LTE"){
                 if($city=="全省"){
                        $sql1 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_LTE_FDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                        $sql2 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_LTE_TDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                        $result1 = $db->query($sql1)->fetchall(PDO::FETCH_ASSOC);
                        $result2 = $db->query($sql2)->fetchall(PDO::FETCH_ASSOC);
                        $result = $this->getAvg($result1,$result2);
                        $arr = $this->getCompare($result,$city,$overview);
                    }else{
                        $result1= LTE_TDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                        $result2= LTE_FDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                        $result = $this->getAvg($result1,$result2);
                        $arr = $this->getCompare($result,$city,$overview);  
                    }
            }elseif($data=="VOLTE"){
                if($city=="全省"){
                        $sql1 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover,round(avg(srvcc),2) AS srvcc,round(avg(upackagelost),2) AS upackagelost,round(avg(dpackagelost),2) AS dpackagelost FROM `B_VOLTE_FDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                        $sql2 ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover,round(avg(srvcc),2) AS srvcc,round(avg(upackagelost),2) AS upackagelost,round(avg(dpackagelost),2) AS dpackagelost  FROM `B_VOLTE_TDD` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                        $result1 = $db->query($sql1)->fetchall(PDO::FETCH_ASSOC);
                        $result2 = $db->query($sql2)->fetchall(PDO::FETCH_ASSOC);
                        $result = $this->getAvg($result1,$result2);
                        $arr = $this->getCompare($result,$city,$overview);
                    }else{
                        $result1= VOLTE_TDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                        $result2= VOLTE_FDD::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                        $result = $this->getAvg($result1,$result2);
                        $arr = $this->getCompare($result,$city,$overview);  
                    }
            }elseif($data=="NBIOT"){
                 if($city=="全省"){
                        $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access FROM `B_NBIOT` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 2";
                        $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                        $arr = $this->getCompare($result,$city,$overview);
                    }else{
                        $result= NBIOT::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(2)->get()->toArray();
                        $arr = $this->getCompare($result,$city,$overview);  
                    }
            }

        }else{
            
        }

        return json_encode($arr);
        
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
    public function getCompare($result,$city,$overview){

        $data = array();
        $len = count($result[0]);
        $key = array_keys($result[0]);
        // print_r($key);exit;
        $a = array('icon-ali-jiantou_xiangxia', 'icon-ali-jiantou_xiangshang', 'icon-ali-jianhao');
        $arr =array();
        $id=0;
        for($i=4;$i<$len;$i++){
            if($result[1][$key[$i]]>$result[0][$key[$i]]){
                $arr[$id]['class']='icon-ali-jiantoushangsheng-blue';
                $arr[$id]['tend'] = round((($result[1][$key[$i]]-$result[0][$key[$i]])),2)."%";
            }elseif($result[1][$key[$i]]<$result[0][$key[$i]]){
                $arr[$id]['class']='icon-ali-jiantouxiajiang-red';
                $arr[$id]['tend'] = round((($result[0][$key[$i]]-$result[1][$key[$i]])),2)."%";
            }else{
                $arr[$id]['class']='icon-ali-jianhao';
                $arr[$id]['tend'] = '0%';
            }
            $arr[$id]["type"] = $key[$i];
            // $arr[$id]["type"] = $city."-".$key[$i]."-".$overview;
            $arr[$id]["data"] = round($result[0][$key[$i]],2)."%";
            $arr[$id]['id']=$id;

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
        if($city!="全省"){
            $dbc = new DataBaseConnection();
            $cityConnmame= $dbc->getConnName($db,$city);
        }
        if($overview=="indexoverview"){
            if($data=="GSM"){
                if($city=="全省"){
                     $sql ="SELECT id,day_id,hour_id,'全省',round(avg(access),2) AS access,round(avg(lost),2) AS lost,round(avg(handover),2) AS handover FROM `B_GSM` GROUP BY hour_id,day_id ORDER BY id DESC,day_id desc,hour_id desc limit 12";
                        $result = $db->query($sql)->fetchall(PDO::FETCH_ASSOC);
                }else{
                    $result= GSM::select()->where("location",$cityConnmame)->orderBy('id','desc')->limit(12)->get()->toArray();
                }
            }elseif($data=="LTE"){
                 if($city=="全省"){
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
                if($city=="全省"){
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
                 if($city=="全省"){
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
        $title = array('text' => $type."-".$city , 'style' =>array('color' =>"#ff0000" ,"fontWeight"=>"bold" ));
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
            $ydata[]=array('name'=>$value,'data'=>$data[$key]);
        }
        $ycategories = array();

        $datas['title'] = $title;
        $datas['subtitle'] = '';
        $datas['ytitle'] = $ytitle;
        $datas['xtitle'] = $xtitle;
        $datas['xcategories'] = $xcategories;
        $datas['ycategories'] = $ycategories;
        $datas['ydata'] = $ydata;
        return $datas;
    }

}
