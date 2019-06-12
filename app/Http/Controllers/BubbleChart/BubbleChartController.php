<?php

namespace App\Http\Controllers\BubbleChart;

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




class BubbleChartController extends Controller
{
    protected $provinces;
    
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
        $this->provinces = array(
            "jiangsu"=>"江苏", 
            "guangdong"=>"广东", 
            "hubei"=>"湖北"
        );
    }

    public function getData()
    {
        $conn = $this->getDetailTableByTypeAndCard();
        $detailConn = $conn['detailConn'];
        $column = $conn['column'];
        if (!$detailConn) {
            return [];
        }
        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        // 先获取城市
        $cityArr = $detailConn->distinct("city")
                            ->where("province", $this->clickLineName)
                            ->where("day", $day_id)
                            ->get(['city'])
                            ->toArray();
        $series = [];
        foreach ($cityArr as $city) {
            $temp = [];
            $city = $city['city'];
            $temp['name'] = $city;
            $temp['data'] = [];
            $res = $detailConn->where("day", $day_id)
                            ->where("city", $city)
                            ->groupBy("location")
                            ->orderBy("failTimes","desc")
                            ->offset(0)
                            ->limit(30)
                            ->selectRaw('location, sum(`'.$column.'`) as failTimes')
                            ->get()
                            ->toArray();
            $top10cell = [];
            $top10FaliTimes = 0;
            $top10FaliFreq = 0;
            $top20cell = [];
            $top20FaliTimes = 0;
            $top20FaliFreq = 0;
            $top30cell = [];
            $top30FaliTimes = 0;
            $top30FaliFreq = 0;
            for ($i=0; $i < 30; $i++) { 
                if ($i < 10) {
                    $top10cell[] = $res[$i]['location'];
                    $top10FaliTimes += $res[$i]['failTimes'];
                } else if ($i < 20) {
                    $top20cell[] = $res[$i]['location'];
                    $top20FaliTimes += $res[$i]['failTimes'];
                } else {
                    $top30cell[] = $res[$i]['location'];
                    $top30FaliTimes += $res[$i]['failTimes'];
                }
            }
            $threeDayEarlier = date('Y-m-d', strtotime("-2 day", strtotime($day_id)));
            $top10FaliFreq = $detailConn->where("day", ">=", $threeDayEarlier)
                                        ->where("day", "<=", $day_id)
                                        ->whereIn("location", $top10cell)
                                        ->count();
            $top20FaliFreq = $detailConn->where("day", ">=", $threeDayEarlier)
                                        ->where("day", "<=", $day_id)
                                        ->whereIn("location", $top20cell)
                                        ->count();
            $top30FaliFreq = $detailConn->where("day", ">=", $threeDayEarlier)
                                        ->where("day", "<=", $day_id)
                                        ->whereIn("location", $top30cell)
                                        ->count();
            $temp['data'][] = array(10,intval($top10FaliFreq),intval($top10FaliTimes));
            $temp['data'][] = array(20,intval($top20FaliFreq),intval($top20FaliTimes));
            $temp['data'][] = array(30,intval($top30FaliFreq),intval($top30FaliTimes));
            $series[] = $temp;
        }
        return $series;
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



        if ($this->timeDim == "day" && $this->province != "national") {
            if ($this->city == "") {
                // 2）省级页面
                if ($this->provinces[$this->province] == $this->clickLineName) {
                    // 2.1 点击省级趋势线，显示指标排名bar图，恶化小区分布饼图和失败次数气泡图
                    $result['series'] = $this->getData();
                    // $result['title'] = $this->clickLineName."-".$this->card;
                    $result['subtitle'] = $this->clickTime;
                    // $result['xAxis'] = [$this->clickTime];
                }
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