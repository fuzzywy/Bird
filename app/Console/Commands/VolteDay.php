<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Databaseconns;
use App\Models\City;
use App\Console\Commands\Bird\LteBackup;
use App\Http\Controllers\Common\DataBaseConnection;
use PDO;
class VolteDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'volteday:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '指标概览 Volte 天级别备份';

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
            new LteBackup("B_VOLTE_TDD_DAY","city","day",$value->connName,"TDD",$db);
            new LteBackup("B_VOLTE_FDD_DAY","city","day",$value->connName,"FDD",$db);

        }
    }
}
