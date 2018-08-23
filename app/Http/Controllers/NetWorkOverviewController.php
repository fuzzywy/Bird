<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

class NetWorkOverviewController extends Controller
{
    public function getTabs() {
    	$data = input::get('data');
    	$city = input::get('city');
    	$overview = input::get('overview');
    	$arr[0]['id'] = 0;
    	$arr[0]['type'] = $overview.$data.$overview.'无线接通率';
    	$arr[0]['data'] = '93%';
    	$arr[0]['class'] = 'icon-ali-jiantou_xiangxia';
    	$arr[1]['id'] = 1;
    	$arr[1]['type'] = '无线掉线率';
    	$arr[1]['data'] = '96%';
    	$arr[1]['class'] = 'icon-ali-jiantou_xiangshang';
    	$arr[2]['id'] = 2;
    	$arr[2]['type'] = '切换成功率';
    	$arr[2]['data'] = '91%';
    	$arr[2]['class'] = 'icon-ali-jianhao';
    	
    	/*$arr['id'] = 1;
    	$arr['type'] = '无线掉线率';
    	$arr['data'] = '95%';
    	$arr['class'] = 'icon-ali-jiantou_xiangshang';
    	array_push($arrs, $arr);*/
        return json_encode($arr);
    }
}
