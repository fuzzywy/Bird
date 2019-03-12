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
        array("id" => 0, "title" => "指标概览", "icon" => "dashboard", "routertag" => "indexoverview" ),
        array("id" => 1, "title" => "规模概览", "icon" => "question_answer", "routertag" => "scaleoverview" ),
        array("id" => 2, "title" => "负荷概览", "icon" => "zoom_out_map", "routertag" => "loadoverview")
       );
      return $arr;
    }
}
