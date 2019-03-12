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
    $data = [];
    $drilldownData = [];
    $today = date("Ymd"); 
    for( $i=0; $i<24; $i++ ) {
      $data[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
      $data[$i]['y'] = rand(90, 100); 
      $data[$i]['drilldown'] = date("Ymd").($i<10?"0".$i:$i);

      $drilldownData[$i]['type'] = "column";
      $drilldownData[$i]['id'] = date("Ymd").($i<10?"0".$i:$i);
      $drilldownData[$i]['name'] = date("Ymd").($i<10?"0".$i:$i);
      $drilldownData[$i]['data'] = array(
        array("江苏省", rand(90, 100)),
        array("广东省", rand(90, 100)),
        array("湖北省", rand(90, 100))
      );
    }

    $arr = array(
      "title" => $type, 
      "subtitle" => "eniq", 
      "series" => array( 
        "name"=>"test", 
        "data"=> $data/*array( 
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
        ),
        array(
          "type" => "column",
          "id" => "2019031201",
          "name" => "2019031201",
          "data" => array(
            array("ddd", 10),
            array("fff", 15),
            array("ggg", 5)
          )
        )
      )*/
    );
    return json_encode($arr);
  }
}
