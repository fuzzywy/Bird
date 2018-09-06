<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Databaseconn2g;
use App\Models\City;
use App\Console\Commands\Bird\GsmBackup;
use PDO;
class GsmExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Gsm:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GSM 数据备份';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $db = new PDO("mysql:host=10.39.148.186;port=13306;dbname=Bird", 'root', 'mongs');
        $result = City::select("connName")->get();
        foreach ($result as $key => $value) {
            // print_r($value->connName);
            $GSM = new GsmBackup("B_GSM","city","hour",$value->connName,$db);
        }
    }
}
