<?php

namespace App\Http\Controllers\BirdSideBar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BirdSideBarController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
      $arr = array(
        array("id" => 0, "title" => "网络性能", "icon" => "dashboard", "routertag" => "indexoverview" ),
        array("id" => 1, "title" => "网络规模", "icon" => "question_answer", "routertag" => "scaleoverview" ),
        array("id" => 2, "title" => "网络负荷", "icon" => "zoom_out_map", "routertag" => "loadoverview"),
        array("id" => 3, "title" => "系统配置", "icon" => "tune", "routertag" => "systemconfiguration"),
       );
      return $arr;
    }
}
