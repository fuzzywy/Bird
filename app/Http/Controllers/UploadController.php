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
}
