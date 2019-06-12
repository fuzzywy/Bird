<?php

namespace App\Http\Controllers\BirdOperators;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BirdOperatorsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $arr = array(
      array("id" => 0, "operator" => "mobile", "name" => "中国移动" ),
      array("id" => 1, "operator" => "unicom", "name" => "中国联通" ),
      array("id" => 2, "operator" => "telecommunications", "name" => "中国电信" )
    );
    return $arr;
  }
}
