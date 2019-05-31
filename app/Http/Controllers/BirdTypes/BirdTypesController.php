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
            array("id" => 0, "type" => "lte", "name" => "LTE-TDD" ),
            array("id" => 1, "type" => "fdd", "name" => "LTE-FDD" ),
            array("id" => 2, "type" => "nbiot", "name" => "NBIOT" ),
            array("id" => 3, "type" => "volteTdd", "name" => "VOLTE-TDD" ),
            array("id" => 4, "type" => "volteFdd", "name" => "VOLTE-FDD" ),
            array("id" => 5, "type" => "gsm", "name" => "GSM" )
        );
        return $arr;
    }
}
