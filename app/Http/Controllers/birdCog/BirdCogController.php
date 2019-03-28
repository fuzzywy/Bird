<?php

namespace App\Http\Controllers\birdCog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Configuration;
use Illuminate\Support\Facades\Input;
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
    // $phone = Configuration::with('configuration')->get()->toArray();
    // $phone = User::find(1)::with('cog')->get()->toArray();
    // print_r($phone);return;
    
    // print_r($arr);return;
    // $arr = array(
    //   array(
    //     'location'=>'江苏省',
    //     'operator'=>'中国移动',
    //     'system'=>'LTE',
    //     'index'=>'无线接通率',
    //     'assessment'=>98,
    //     'status'=>'禁用'
    //   ),
    //   array(
    //     'location'=>'全国',
    //     'operator'=>'中国移动',
    //     'system'=>'LTE',
    //     'index'=>'无线接通率',
    //     'assessment'=>98,
    //     'status'=>'禁用'
    //   ),
      // array(
      //   'location'=>array( 'value'=>'jiangsu', 'text'=>'江苏省' ),
      //   'operator'=>array( 'value'=> 'mobile', 'text'=> '中国移动' ),
      //   'system'=>array( 'value'=> 'lte', 'text'=> 'LTE' ),
      //   'index'=>array( 'value'=> 'lowaccess', 'text'=> '无线接通率' ),
      //   'assessment'=>98,
      //   'status'=>array( 'value'=> 'off', 'text'=> '禁用' )
      // ),
      // array(
      //   'location'=>array( 'value'=>'national', 'text'=>'全国' ),
      //   'operator'=>array( 'value'=> 'mobile', 'text'=> '中国移动' ),
      //   'system'=>array( 'value'=> 'lte', 'text'=> 'LTE' ),
      //   'index'=>array( 'value'=> 'lowaccess', 'text'=> '无线接通率' ),
      //   'assessment'=>98,
      //   'status'=>array( 'value'=> 'off', 'text'=> '禁用' )
      // ),
      // array(
      //   'location'=>array( 'value'=>'national', 'text'=>'全国' ),
      //   'operator'=>array( 'value'=> 'mobile', 'text'=> '中国移动' ),
      //   'system'=>array( 'value'=> 'lte', 'text'=> 'LTE' ),
      //   'index'=>array( 'value'=> 'lowaccess', 'text'=> '无线接通率' ),
      //   'assessment'=>98,
      //   'status'=>array( 'value'=> 'off', 'text'=> '禁用' )
      // ),array(
      //   'location'=>array( 'value'=>'national', 'text'=>'全国' ),
      //   'operator'=>array( 'value'=> 'mobile', 'text'=> '中国移动' ),
      //   'system'=>array( 'value'=> 'lte', 'text'=> 'LTE' ),
      //   'index'=>array( 'value'=> 'lowaccess', 'text'=> '无线接通率' ),
      //   'assessment'=>98,
      //   'status'=>array( 'value'=> 'off', 'text'=> '禁用' )
      // ),array(
      //   'location'=>array( 'value'=>'national', 'text'=>'全国' ),
      //   'operator'=>array( 'value'=> 'mobile', 'text'=> '中国移动' ),
      //   'system'=>array( 'value'=> 'lte', 'text'=> 'LTE' ),
      //   'index'=>array( 'value'=> 'lowaccess', 'text'=> '无线接通率' ),
      //   'assessment'=>98,
      //   'status'=>array( 'value'=> 'off', 'text'=> '禁用' )
      // ),array(
      //   'location'=>array( 'value'=>'national', 'text'=>'全国' ),
      //   'operator'=>array( 'value'=> 'mobile', 'text'=> '中国移动' ),
      //   'system'=>array( 'value'=> 'lte', 'text'=> 'LTE' ),
      //   'index'=>array( 'value'=> 'lowaccess', 'text'=> '无线接通率' ),
      //   'assessment'=>98,
      //   'status'=>array( 'value'=> 'off', 'text'=> '禁用' )
      // ),
    // );
    return Configuration::all()->toArray();
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
