<?php

namespace App\Http\Controllers\birdCog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Configuration;
use App\Region;
use Illuminate\Support\Facades\Input;
use App\Kpi;
// use App\User;

class BirdCogController extends Controller
{
   /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $arr = [];
    $arr['data'] = Configuration::all()->toArray();
    $region = Region::groupBy('spell-province')->get(['province'])->toArray();
    $arr['region'] = array_reverse($region);
    $kpi = Kpi::groupBy(['kpi'])->get(['kpi'])->toArray();
    $arr['kpi'] = array_reverse($kpi);
    return $arr;//Configuration::all()->toArray();
  }

  public function edit() {
    $editedItem = Input::get('editedItem');
    if(array_key_exists('id', $editedItem)) {
      //编辑
      Configuration::where('id', $editedItem['id'])
        ->update(
          [
            'location'=> $editedItem['location'],
            'operator'=> $editedItem['operator'], 
            'system'=> $editedItem['system'],
            'index'=> $editedItem['index'],
            'assessment'=> $editedItem['assessment'],
            'status'=> $editedItem['status']
          ]);
    } else {
      //新增
      $configuration = new Configuration;
      $configuration->location = $editedItem['location'];
      $configuration->operator = $editedItem['operator'];
      $configuration->system = $editedItem['system'];
      $configuration->index = $editedItem['index'];
      $configuration->assessment = $editedItem['assessment'];
      $configuration->status = $editedItem['status'];
      $configuration->save();
    }
  }

  public function delete() {
    $item = Input::get('item');
    Configuration::where('id', $item['id'])
      ->delete();
  }
}
