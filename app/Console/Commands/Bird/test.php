<?php 

namespace App\Console\Commands\Bird;

class test{
	public function __construct()
	{
		file_put_contents("test.log", date("Y-m-d")."\n",FILE_APPEND);
	}
}