<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

class NetWorkChartsLTEController extends Controller
{
    public function getcharts() {
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
        $ycategories = array(-10,0,10);
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
