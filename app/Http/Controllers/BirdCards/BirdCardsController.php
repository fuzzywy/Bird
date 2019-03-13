<?php

namespace App\Http\Controllers\BirdCards;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BirdCardsController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $arr = array(
      array("id" => 0, "class" => "icon-ali-jiantoushangsheng-blue", "color" => "green", "data" => "98.4%", "tend" => "0.03%", "type" => "无线接通率", "flex" => 3, "time" => "2019/03/13" ),
      array("id" => 1, "class" => "icon-ali-jiantouxiajiang-red", "color" => "red", "data" => "8.4%", "tend" => "0.01%", "type" => "无线掉线率", "flex" => 3, "time" => "2019/03/13"),
      array("id" => 2, "class" => "icon-ali-jiantoushangsheng-blue", "color" => "green", "data" => "99.4%", "tend" => "0.02%", "type" => "切换成功率", "flex" => 3, "time" => "2019/03/13"),
      array("id" => 3, "class" => "icon-ali-jiantoushangsheng-blue", "color" => "green", "data" => "97.4%", "tend" => "0.04%", "type" => "Volte无线接通率", "flex" => 3, "time" => "2019/03/13")
    );
    return $arr;
  }
}
