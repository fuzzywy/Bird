<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use App\Models\Network;
use Illuminate\Support\Facades\DB;

class NetworkOverviewController extends Controller
{
    public function getBirdSideBar() {
        sleep(1);
        $arr = array(
                array("id" => 0, "Content" => "指标概览", "routertag" => "indexoverview" ),
                array("id" => 1, "Content" => "规模概览", "routertag" => "scaleoverview" ),
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

    public function getScaleTabs() {
        sleep(1);
        $city = input::get('city');
        $overview = input::get('overview');
        $arr = [];
        $arr['GSMs'][0]['id'] = 0;
        $arr['GSMs'][0]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][0]['data'] = rand(1,100000).'个';
        $arr['GSMs'][1]['id'] = 1;
        $arr['GSMs'][1]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][1]['data'] = rand(1,100000).'个';
        $arr['GSMs'][2]['id'] = 2;
        $arr['GSMs'][2]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][2]['data'] = rand(1,100000).'个';
        $arr['GSMs'][3]['id'] = 3;
        $arr['GSMs'][3]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][3]['data'] = rand(1,100000).'个';
        $arr['GSMs'][4]['id'] = 4;
        $arr['GSMs'][4]['name'] = 'test'.rand(0,100);
        $arr['GSMs'][4]['data'] = rand(1,100000).'个';
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
        $arr['TDDLTEs'][0]['data'] = rand(1,100000).'个';
        $arr['TDDLTEs'][1]['id'] = 1;
        $arr['TDDLTEs'][1]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][1]['data'] = rand(1,100000).'个';
        $arr['TDDLTEs'][2]['id'] = 2;
        $arr['TDDLTEs'][2]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][2]['data'] = rand(1,100000).'个';
        $arr['TDDLTEs'][3]['id'] = 3;
        $arr['TDDLTEs'][3]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][3]['data'] = rand(1,100000).'个';
        $arr['TDDLTEs'][4]['id'] = 4;
        $arr['TDDLTEs'][4]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][4]['data'] = rand(1,100000).'个';
        $arr['TDDLTEs'][5]['id'] = 5;
        $arr['TDDLTEs'][5]['name'] = 'test'.rand(0,100);
        $arr['TDDLTEs'][5]['data'] = rand(1,100000).'个';

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
        $arr['FDDLTEs'][0]['data'] = rand(1,100000).'个';
        $arr['FDDLTEs'][1]['id'] = 1;
        $arr['FDDLTEs'][1]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][1]['data'] = rand(1,100000).'个';
        $arr['FDDLTEs'][2]['id'] = 2;
        $arr['FDDLTEs'][2]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][2]['data'] = rand(1,100000).'个';
        $arr['FDDLTEs'][3]['id'] = 3;
        $arr['FDDLTEs'][3]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][3]['data'] = rand(1,100000).'个';
        $arr['FDDLTEs'][4]['id'] = 4;
        $arr['FDDLTEs'][4]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][4]['data'] = rand(1,100000).'个';
        $arr['FDDLTEs'][5]['id'] = 5;
        $arr['FDDLTEs'][5]['name'] = 'test'.rand(0,100);
        $arr['FDDLTEs'][5]['data'] = rand(1,100000).'个';

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
        $arr['NBIOTs'][0]['data'] = rand(1,100000).'个';
        $arr['NBIOTs'][1]['id'] = 1;
        $arr['NBIOTs'][1]['name'] = 'test'.rand(0,100);
        $arr['NBIOTs'][1]['data'] = rand(1,100000).'个';
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
        // var_dump(Network::all());
        // var_dump(DB::select("select * from networks"));
        // return "test";
        sleep(1);
    	$data = input::get('data');
    	$city = input::get('city');
    	$overview = input::get('overview');
        $a = array('icon-ali-jiantou_xiangxia', 'icon-ali-jiantou_xiangshang', 'icon-ali-jianhao');
        $arr = [];
        $arr[0]['id'] = 0;
        $arr[0]['type'] = $city.'-'.$data.'-'.$overview;
        $arr[0]['data'] = rand(90,100)."%";
        $arr[0]['class'] = $a[array_rand($a, 1)];
        $arr[1]['id'] = 1;
        $arr[1]['type'] = '无线掉线率';
        $arr[1]['data'] = rand(90,100)."%";
        $arr[1]['class'] = $a[array_rand($a, 1)];
        $arr[2]['id'] = 2;
        $arr[2]['type'] = $city.'-'.$data.'-'.$overview;
        $arr[2]['data'] = rand(90,100)."%";
        $arr[2]['class'] = $a[array_rand($a, 1)];
        return json_encode($arr);
    }

    public function getcharts() {
        sleep(1);
        $data = input::get('data');
        $city = input::get('city');
        $overview = input::get('overview');
        $datas = [];
        //主标题/副标题/y轴标题/x轴标题
        $title = array('text' => $data."-".$city."-".$overview , 'style' =>array('color' =>"#ff0000" ,"fontWeight"=>"bold" ));
        $subtitle = array('text' => $data."-".$city."-".$overview, 'style' =>array('color' =>"#ff0000" ));
        $ytitle = array('text' => "Y".$data."-".$city."-".$overview , 'style' =>array('color' =>"#ff0000" ,"fontWeight"=>"bold" ));
        $xtitle = array('text' => "X".$data."-".$city."-".$overview , 'style' =>array('color' =>"#ff0000" ,"fontWeight"=>"bold" ));
        //x轴/Y轴/data
        $xcategories = array('1月'.rand(-3,10), '2月', '3月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');
        $ycategories = array();
        $ydata[] = array('name'=>'测试0', 'data'=>array(rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10)));
        $ydata[] = array('name'=>'测试1', 'data'=>array(rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10)));
        $ydata[] = array('name'=>'测试2', 'data'=>array(rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10), rand(-3,10)));

        $datas['title'] = $title;
        $datas['subtitle'] = $subtitle;
        $datas['ytitle'] = $ytitle;
        $datas['xtitle'] = $xtitle;
        $datas['xcategories'] = $xcategories;
        $datas['ycategories'] = $ycategories;
        $datas['ydata'] = $ydata;
        return json_encode($datas);
    }
}
