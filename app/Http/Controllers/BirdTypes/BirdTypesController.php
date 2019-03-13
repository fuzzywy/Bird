<?php

namespace App\Http\Controllers\BirdTypes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BirdTypesController extends Controller
{
   /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $arr = array(
      array("id" => 0, "type" => "lte", "name" => "LTE" ),
      array("id" => 1, "type" => "nbiot", "name" => "NBIOT" ),
      array("id" => 2, "type" => "volte", "name" => "VOLTE" ),
      array("id" => 3, "type" => "gsm", "name" => "GSM" )
    );
    return $arr;
  }
}
