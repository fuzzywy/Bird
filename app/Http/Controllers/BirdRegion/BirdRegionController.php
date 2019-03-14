<?php

namespace App\Http\Controllers\BirdRegion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BirdRegionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $arr = array(
      array("id" => 0, "label" => "全国", "value" => "national", "cities" => array() ),
      array("id" => 1, "label" => "江苏省", "value" => "jiangsu", "cities" => array( array("id" => 0, "label" => "南京", "value" => "nanjing" ), array("id" => 0, "label" => "常州", "value" => "changzhou" ) ) ),
      array("id" => 2, "label" => "湖北省", "value" => "hubei", "cities" => array( array("id" => 0, "label" => "武汉", "value" => "wuhan" ), array("id" => 0, "label" => "荆州", "value" => "jingzhou" ) ) ),
      array("id" => 3, "label" => "广东省", "value" => "guangdong", "cities" => array( array("id" => 0, "label" => "广州", "value" => "guangzhou" ), array("id" => 0, "label" => "清远", "value" => "qingyuan" ) ) )

    );
    return $arr;
  }
}
