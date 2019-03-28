<?php

namespace App\Http\Controllers\BirdChart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class BirdChartController extends Controller
{
  protected $provinces;
  protected $map;
  protected $arr;

  protected $type;
  protected $city;
  protected $card;
  protected $province;
  protected $timeDim;

  protected $national;
  protected $drilldownData;

  public function __construct()
  {
    $this->provinces = array("jiangsu"=>"江苏省", "guangdong"=>"广东省", "hubei"=>"湖北省");
    $this->map = array(
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
  }

  /**
   * 获取全国图表数据
   */
  private function getNationalData() 
  {
    $data = [];
    $this->drilldownData = [];
    $today = date("Ymd");
    for( $i=0; $i<24; $i++ ) {
      $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
      $data[$i]['y'] = rand(90, 100); 
      $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i)."-national";

      $this->drilldownData[$i]['type'] = "column";
      $this->drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i)."-national";
      $this->drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i)."-national";
      //全国级plot line点击时，呈现‌所有省份的指标(指定时间段内均值)排名的bar plot. 从高到低排序
      $this->drilldownData[$i]['data'] = array(
        array("江苏省", rand(90, 100)),
        array("广东省", rand(90, 100)),
        array("湖北省", rand(90, 100))
      );
    }
    $this->national = array("name"=>"全国", "data"=>$data); 
  }

  /**
   * 获取各省市图表数据
   */
  private function getProvincesData()
  {
    $provinceArr = [];
    foreach ($this->provinces as $key => $province) {
      $data = [];
      $drilldownData = [];
      // $test = [];
      for ($i=0; $i < 24; $i++) { 
        $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
        $data[$i]['y'] = rand(90, 100); 
        $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;

        $drilldownData[$i]['type'] = "column";
        $drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
        $drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
        $drilldownData[$i]['data'] = [];

        // $test[$i]['type'] = "pie";
        // $test[$i]['id'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
        // $test[$i]['name'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
        // $test[$i]['data'] = [];

        foreach ($this->map[$key] as $k => $v) {
          array_push($drilldownData[$i]['data'], array($v, rand(90, 100)));
          // array_push($test[$i]['data'], array($v, rand(90, 100)));
        }
        $this->arr['drilldown'][] = $drilldownData[$i];
        // $this->arr['drilldown'][] = $test[$i];
      }
      $provinceArr['name'] = $province;
      $provinceArr['data'] = $data;
      array_push($this->arr['series'], $provinceArr);
    }
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $this->type = Input::get('type');
    $this->city = Input::get('city');
    $this->card = Input::get('card');
    $this->province = Input::get('province');
    $this->timeDim = Input::get('timeDim');

    $this->getNationalData();
    // $data = [];
    // $drilldownData = [];
    // $today = date("Ymd"); 
    //全国
    // for( $i=0; $i<24; $i++ ) {
    //   $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
    //   $data[$i]['y'] = rand(90, 100); 
    //   $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i)."-national";

    //   $drilldownData[$i]['type'] = "column";
    //   $drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i)."-national";
    //   $drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i)."-national";
    //   $drilldownData[$i]['data'] = array(
    //     array("江苏省", rand(90, 100)),
    //     array("广东省", rand(90, 100)),
    //     array("湖北省", rand(90, 100))
    //   );
    // }
    // $national = array("name"=>"全国", "data"=>$data);

    $this->arr = array(
      "title" => strtoupper($this->type).'-'.$this->card, 
      "subtitle" => $this->province, 
      "type" => "line",
      "series" => array(
        $this->national
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
      "drilldown" => $this->drilldownData/*array(
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

    $this->getProvincesData();
    // $provinceArr = [];
    // foreach ($this->provinces as $key => $province) {
    //   $data = [];
    //   $drilldownData = [];
    //   for ($i=0; $i < 24; $i++) { 
    //     $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
    //     $data[$i]['y'] = rand(90, 100); 
    //     $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;

    //     $drilldownData[$i]['type'] = "column";
    //     $drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
    //     $drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i).'-'.$key;
    //     $drilldownData[$i]['data'] = [];
    //     foreach ($this->map[$key] as $k => $v) {
    //       array_push($drilldownData[$i]['data'], array($v, rand(90, 100)));
    //     }
    //     $this->arr['drilldown'][] = $drilldownData[$i];
    //   }
    //   $provinceArr['name'] = $this->province;
    //   $provinceArr['data'] = $data;
    //   array_push($this->arr['series'], $provinceArr);
    // }
    return json_encode($this->arr);
  }
}