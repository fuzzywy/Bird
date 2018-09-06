<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Databaseconns;
use App\Models\City;
use App\Console\Commands\Bird\LteBackup;
use PDO;
class LteExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Lte:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lte 数据备份';

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
        // print_r($result);exit;
        foreach ($result as $key => $value) {
         $Volte_TDD = new LteBackup("B_LTE_TDD","city","hour",$value->connName,"TDD",$db);
         $Volte_FDD = new LteBackup("B_LTE_FDD","city","hour",$value->connName,"FDD",$db);

        }
    }
}
