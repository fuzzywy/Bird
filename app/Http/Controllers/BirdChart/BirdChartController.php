<?php

namespace App\Http\Controllers\BirdChart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class BirdChartController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $type = Input::get('type');
    $city = Input::get('city');
    $card = Input::get('card');
    $province = Input::get('province');

    //各省市
    $provinces = array("jiangsu"=>"江苏省", "guangdong"=>"广东省", "hubei"=>"湖北省");
    $map = array(
      "jiangsu"=>array(
        "nanjing" => "南京",
        "chagnzhou" => "常州",
        "wuxi" => "无锡"
      ),
      "guangdong"=>array(
        "guangzhou" => "广州",
        "qingyuan" => "清远"
      ),
      "hubei"=>array(
        "wuhan" => "武汉",
        "jingzhou" => "荆州"
      )
    );

    $data = [];
    $drilldownData = [];
    $today = date("Ymd"); 
    //全国
    for( $i=0; $i<24; $i++ ) {
      $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
      $data[$i]['y'] = rand(90, 100); 
      $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i)."-national";

      $drilldownData[$i]['type'] = "column";
      $drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i)."-national";
      $drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i)."-national";
      $drilldownData[$i]['data'] = array(
        array("江苏省", rand(90, 100)),
        array("广东省", rand(90, 100)),
        array("湖北省", rand(90, 100))
      );
    }
    $national = array("name"=>"全国", "data"=>$data);

    $arr = array(
      "title" => strtoupper($type).'-'.$card, 
      "subtitle" => $province, 
      "type" => "line",
      "series" => array(
        $national
        /*array( 
          "name"=>"全国", 
          "data"=> $dataarray( 
            array(
              "name"=>"2019030500", 
              "y"=> rand(90, 100), 
              "drilldown"=>"2019030500"
            ),
            array(
              "name"=>"2019030501", 
              "y"=> rand(90, 100), 
              "drilldown"=>"2019030501"
            ) 
          ) 
        )*/
      ),
      "drilldown" => $drilldownData/*array(
        array(
          "type" => "column",
          "id" => "2019031200",
          "name" => "2019031200",
          "data" => array(
            array("ddd", 10),
            array("fff", 15),
            array("ggg", 5)
          )
        )
      )*/
    );


    $provinceArr = [];
    foreach ($provinces as $key => $province) {
      $data = [];
      $drilldownData = [];
      for ($i=0; $i < 24; $i++) { 
        $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
        $data[$i]['y'] = rand(90, 100); 
        $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;

        $drilldownData[$i]['type'] = "column";
        $drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
        $drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
        $drilldownData[$i]['data'] = [];
        foreach ($map[$key] as $k => $v) {
          array_push($drilldownData[$i]['data'], array($v, rand(90, 100)));
        }
        $arr['drilldown'][] = $drilldownData[$i];
      }
      $provinceArr['name'] = $province;
      $provinceArr['data'] = $data;
      array_push($arr['series'], $provinceArr);
    }
    return json_encode($arr);
  }
}
