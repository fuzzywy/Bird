<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City;
use App\Console\Commands\Bird\GsmBackup;
use App\Http\Controllers\Common\DataBaseConnection;
class GsmDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsmday:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '指标概览 GSM天粒度备份';

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
        $dbc = new DataBaseConnection();
        $db = $dbc->getDB("Bird");
        $result = City::select("connName")->get();
        foreach ($result as $key => $value) {
           new GsmBackup("B_GSM_DAY","city","day",$value->connName,$db);
        }
    }
}
