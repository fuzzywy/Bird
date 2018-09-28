<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UploadController extends Controller 
{
	public function uploadCog() {
		$data = Input::get('data'); //注意post传值格式
		return 'success';
	}

	public function showCog() {
		// echo 'showCog';
		$arr  = array(
					array('id' => 0, 'ip' => '192.168.0.1', 'port' => intval('3306'), 'database' => 't1', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 1, 'ip' => '192.168.0.2', 'port' => intval('33061'), 'database' => 't2', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 2, 'ip' => '192.168.0.3', 'port' => intval('33062'), 'database' => 't3', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'GSM' ),
					array('id' => 3, 'ip' => '192.168.0.4', 'port' => intval('33063'), 'database' => 't4', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 4, 'ip' => '192.168.0.5', 'port' => intval('33064'), 'database' => 't5', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 5, 'ip' => '192.168.0.6', 'port' => intval('33065'), 'database' => 't6', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'GSM' )
				);
		return $arr;
	}

	public function deleteCog() {
		echo 'delete';
	}
}
