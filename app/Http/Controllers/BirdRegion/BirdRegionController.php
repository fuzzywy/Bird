<?php

namespace App\Http\Controllers\BirdRegion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Region;

class BirdRegionController extends Controller
{

  private $region;
  private $arr = [];

  private function getArr() {
    $region = Region::groupBy('spell-province')->get()->toArray();
    $province = [];
    foreach ($region as $v) {
      $province['label'] = $v['province'];
      $province['value'] = $v['spell-province'];
      $this->arr[$v['spell-province']] = $province;
    }
    $cities = [];
    $i = 0;
    foreach ($this->region as $v) {
      $cities['id'] = $i;
      $cities['label'] = $v['city'];
      $cities['value'] = $v['spell-city'];
      $this->arr[$v['spell-province']]['cities'][] = $cities;
      $i++;
    }
    $arr = array("id" => 0, "label" => "全国", "value" => "national", "cities" => array());
    array_push( $this->arr, $arr );
    return array_reverse(array_values($this->arr));
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $this->region = Region::all()->toArray();
    $arr = $this->getArr();
    // $arr = array(
    //   array("id" => 0, "label" => "全国", "value" => "national", "cities" => array() ),
    //   array("id" => 1, "label" => "江苏省", "value" => "jiangsu", "cities" => array( array("id" => 0, "label" => "南京", "value" => "nanjing" ), array("id" => 0, "label" => "常州", "value" => "changzhou" ) ) ),
    //   array("id" => 2, "label" => "湖北省", "value" => "hubei", "cities" => array( array("id" => 0, "label" => "武汉", "value" => "wuhan" ), array("id" => 0, "label" => "荆州", "value" => "jingzhou" ) ) ),
    //   array("id" => 3, "label" => "广东省", "value" => "guangdong", "cities" => array( array("id" => 0, "label" => "广州", "value" => "guangzhou" ), array("id" => 0, "label" => "清远", "value" => "qingyuan" ) ) )

    // );
    return $arr;
  }
}
