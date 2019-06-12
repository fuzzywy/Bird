<?php

namespace App\Http\Controllers\TopCell;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Configuration;

use App\Models\B_K_LTE_TDD_HOUR_ACCESS;
use App\Models\B_K_LTE_TDD_HOUR_LOST;
use App\Models\B_K_LTE_TDD_HOUR_HANDOVER;

use App\Models\B_K_LTE_FDD_HOUR_ACCESS;
use App\Models\B_K_LTE_FDD_HOUR_LOST;
use App\Models\B_K_LTE_FDD_HOUR_HANDOVER;

use App\Models\B_K_NBIOT_HOUR_ACCESS;
use App\Models\B_K_NBIOT_HOUR_LOST;

use App\Models\B_K_GSM_HOUR_ACCESS;
use App\Models\B_K_GSM_HOUR_LOST;
use App\Models\B_K_GSM_HOUR_HANDOVER;

use App\Models\B_K_VOLTE_TDD_HOUR_ACCESS;
use App\Models\B_K_VOLTE_TDD_HOUR_LOST;
use App\Models\B_K_VOLTE_TDD_HOUR_HANDOVER;
use App\Models\B_K_VOLTE_TDD_HOUR_SRVCC;
use App\Models\B_K_VOLTE_TDD_HOUR_UPACKETLOST;
use App\Models\B_K_VOLTE_TDD_HOUR_DPACKETLOST;

use App\Models\B_K_VOLTE_FDD_HOUR_ACCESS;
use App\Models\B_K_VOLTE_FDD_HOUR_LOST;
use App\Models\B_K_VOLTE_FDD_HOUR_HANDOVER;
use App\Models\B_K_VOLTE_FDD_HOUR_SRVCC;
use App\Models\B_K_VOLTE_FDD_HOUR_UPACKETLOST;
use App\Models\B_K_VOLTE_FDD_HOUR_DPACKETLOST;


class TopCellController extends Controller
{
    protected $type;
    protected $city;
    protected $card;
    protected $province;
    protected $timeDim;
    protected $operator;
    protected $clickTime;
    protected $clickLineName;
    
    public function __construct()
    {
    }

    public function getTopCells()
    {
        $tables = [];
        $dConn = $this->getDetailTableByTypeAndCard();
        $detailConn = $dConn['detailConn'];
        $column = $dConn['column'];
        if (!$detailConn) {
            return [];
        }

        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);
            $tables = $detailConn->where('day', $day_id)
                            ->where("hour", $hour_id)
                            ->where("city", $this->clickLineName)
                            ->orderByRaw('(`failTimes`+0 ) desc')
                            ->offset(0)
                            ->limit(30)
                            ->get(['day','hour','city','location',$column.' as failTimes'])
                            ->toArray();
            

        } else {
            $tables = $detailConn->where("day", $day_id)
                            ->where("city", $this->clickLineName)
                            ->groupBy("location")
                            ->orderBy("failTimes","desc")
                            ->offset(0)
                            ->limit(30)
                            ->selectRaw('day, city, location, sum(`'.$column.'`) as failTimes')
                            ->get()
                            ->toArray();
        }
        return $tables;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $this->type = Input::get('type');//"lte"
        $this->city = Input::get('city');
        $this->card = Input::get('card');//"无线接通率"
        $this->province = Input::get('province');
        $this->timeDim = Input::get('timeDim');//day or hour
        $this->operator = Input::get('operator');//"mobile"
        $this->clickTime = Input::get('clickTime');//20190525
        $this->clickLineName = Input::get('clickLineName');

        $result = [];

        if ($this->province != "national") {
            if ($this->city != "") {
                // 3) 地市级页面
                // 3.1 点击地市指标趋势线
                // 左侧显示该时间点该地市的TOP30小区列表(小区名，失败次数)。
                // 右侧显示TOP30和其余小区失败次数在全网失败次数比例的饼图分布。
                $result = $this->getTopCells();
            }
        }
        return json_encode($result);
    }


    public function getDetailTableByTypeAndCard()
    {
        $detailConn = null;
        $column = "";
        if ($this->type == 'lte') {
            switch ($this->card) {
                case '无线接通率':
                    $detailConn = new B_K_LTE_TDD_HOUR_ACCESS;
                    $column = "无线接入失败次数";
                    break;
                case '无线掉线率':
                    $detailConn = new B_K_LTE_TDD_HOUR_LOST;
                    $column = "无线掉线次数";
                    break;
                case '切换成功率':
                    $detailConn = new B_K_LTE_TDD_HOUR_HANDOVER;
                    $column = "切换失败次数";
                    break;
            }
        } else if ($this->type == 'fdd') {
            switch ($this->card) {
                case '无线接通率':
                    $detailConn = new B_K_LTE_FDD_HOUR_ACCESS;
                    $column = "无线接入失败次数";
                    break;
                case '无线掉线率':
                    $detailConn = new B_K_LTE_FDD_HOUR_LOST;
                    $column = "无线掉线次数";
                    break;
                case '切换成功率':
                    $detailConn = new B_K_LTE_FDD_HOUR_HANDOVER;
                    $column = "切换失败次数";
                    break;
            }
        } else if ($this->type == 'nbiot') {
            switch ($this->card) {
                case '无线接通率':
                    $detailConn = new B_K_NBIOT_HOUR_ACCESS;
                    $column = "NBIOT无线接入失败次数";
                    break;
                case '无线掉线率':
                    $detailConn = new B_K_NBIOT_HOUR_LOST;
                    $column = "NBIOT无线掉线次数";
                    break;
            }
        } else if ($this->type == 'gsm') {
            switch ($this->card) {
                case '无线接通率':
                    $detailConn = new B_K_GSM_HOUR_ACCESS;
                    $column = "2G无线接入失败次数";
                    break;
                case '无线掉线率':
                    $detailConn = new B_K_GSM_HOUR_LOST;
                    $column = "2G无线掉线次数";
                    break;
                case '切换成功率':
                    $detailConn = new B_K_GSM_HOUR_HANDOVER;
                    $column = "2G切换失败次数";
                    break;
            }
        } else if ($this->type == 'volteTdd') {
            switch ($this->card) {
                case '无线接通率':
                    $detailConn = new B_K_VOLTE_TDD_HOUR_ACCESS;
                    $column = "VOLTE无线接入失败次数";
                    break;
                case '无线掉线率':
                    $detailConn = new B_K_VOLTE_TDD_HOUR_LOST;
                    $column = "无线掉线次数QCI1";
                    break;
                case '切换成功率':
                    $detailConn = new B_K_VOLTE_TDD_HOUR_HANDOVER;
                    $column = "VOLTE切换失败次数";
                    break;
                case 'SRVCC切换成功率':
                    $detailConn = new B_K_VOLTE_TDD_HOUR_SRVCC;
                    $column = "SRVCC失败次数";
                    break;
                case '上行丢包率':
                    $detailConn = new B_K_VOLTE_TDD_HOUR_UPACKETLOST;
                    $column = "volte上行丢包数";
                    break;
                case '下行丢包率':
                    $detailConn = new B_K_VOLTE_TDD_HOUR_DPACKETLOST;
                    $column = "volte下行丢包数";
                    break;
            }
        } else if ($this->type == 'volteFdd') {
            switch ($this->card) {
                case '无线接通率':
                    $detailConn = new B_K_VOLTE_FDD_HOUR_ACCESS;
                    $column = "VOLTE无线接入失败次数";
                    break;
                case '无线掉线率':
                    $detailConn = new B_K_VOLTE_FDD_HOUR_LOST;
                    $column = "无线掉线次数QCI1";
                    break;
                case '切换成功率':
                    $detailConn = new B_K_VOLTE_FDD_HOUR_HANDOVER;
                    $column = "VOLTE切换失败次数";
                    break;
                case 'SRVCC切换成功率':
                    $detailConn = new B_K_VOLTE_FDD_HOUR_SRVCC;
                    $column = "SRVCC失败次数";
                    break;
                case '上行丢包率':
                    $detailConn = new B_K_VOLTE_FDD_HOUR_UPACKETLOST;
                    $column = "volte上行丢包数";
                    break;
                case '下行丢包率':
                    $detailConn = new B_K_VOLTE_FDD_HOUR_DPACKETLOST;
                    $column = "volte下行丢包数";
                    break;
            }
        }
        return array(
            "detailConn"=>$detailConn,
            "column"=>$column
        );
    }
}