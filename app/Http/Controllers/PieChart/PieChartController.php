<?php

namespace App\Http\Controllers\PieChart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Configuration;

use App\Models\B_K_GSM_HOUR;
use App\Models\B_K_LTE_FDD_HOUR;
use App\Models\B_K_LTE_TDD_HOUR;
use App\Models\B_K_NBIOT_HOUR;
use App\Models\B_K_VOLTE_FDD_HOUR;
use App\Models\B_K_VOLTE_TDD_HOUR;

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


class PieChartController extends Controller
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
        $this->fields = array(
            '无线接通率'=>array(
                'cell'=>'c_d_access',
                'failTimes'=>'c_f_access'
            ),
            '无线掉线率'=>array(
                'cell'=>'c_d_lost',
                'failTimes'=>'c_f_lost'
            ),
            '切换成功率'=>array(
                'cell'=>'c_d_handover',
                'failTimes'=>'c_f_handover'
            ),
            // '高干扰小区比例'=>'r_highInterfere',
            'SRVCC切换成功率'=>array(
                'cell'=>'c_d_srvcc',
                'failTimes'=>'c_f_srvcc'
            ),
            '上行丢包率'=>array(
                'cell'=>'c_d_upacketlost',
                'failTimes'=>'c_f_upacketlost'
            ),
            '下行丢包率'=>array(
                'cell'=>'c_d_dpacketlost',
                'failTimes'=>'c_f_dpacketlost'
            ),
            // 'NBIOT上行底躁（>-110比率）'=>'r_u_floor'
        );
    }

    public function getProvinceCitysData()
    {
        $series = [];
        $conn = $this->switchTableByType();
        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        $conn = $conn->where('day_id', $day_id);
        $column = $this->fields[$this->card]['cell'];
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);
            // 如果是hour，就取时间点的小区恶化分布
            $res = $conn->where('hour_id', $hour_id)
                        ->where('province', $this->clickLineName)
                        ->where('city', '!=', $this->clickLineName)
                        ->get(['city',$column])
                        ->toArray();
            foreach ($res as $value) {
                $series[] = array(
                    "name" => $value['city'],
                    "y" => intval($value[$column])
                );
            }

        } else {
            // 是day就sum
            $res = $conn->where('province', $this->clickLineName)
                        ->where('city', '!=', $this->clickLineName)
                        ->groupBy('city')
                        ->selectRaw('city, sum(`'.$column.'`) as '.$column)
                        ->get()
                        ->toArray();
            foreach ($res as $value) {
                $series[] = array(
                    "name" => $value['city'],
                    "y" => intval($value[$column])
                );
            }
        }
        return $series;
    }

    public function getCityData()
    {
        $series = [];
        $dConn = $this->getDetailTableByTypeAndCard();
        $detailConn = $dConn['detailConn'];
        $detailColumn = $dConn['column'];
        $column = $this->fields[$this->card]['failTimes'];
        if (!$detailConn) {
            return [];
        }
        $conn = $this->switchTableByType();

        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);

            $topRes = $detailConn->where('day', $day_id)
                                ->where("hour", $hour_id)
                                ->where("city", $this->clickLineName)
                                ->orderByRaw('(`'.$detailColumn.'`+0 ) desc')
                                ->selectRaw('`'.$detailColumn.'` as failTimes')
                                ->offset(0)
                                ->limit(30)
                                ->get()
                                ->toArray();

            // $topFailTimes = $topRes[0]['failTimes'];
            $topFailTimes = 0;
            foreach ($topRes as $value) {
                $topFailTimes += $value['failTimes'];
            }
            $series[] = array(
                "name" => "top30小区",
                "y" => intval($topFailTimes)
            );

            $totalRes = $conn->where('day_id', $day_id)
                                ->where("hour_id", $hour_id)
                                ->where("city", $this->clickLineName)
                                ->get([$column])
                                ->toArray();
            $totalFailTimes = $totalRes[0][$column];
            $series[] = array(
                "name" => "其余小区",
                "y" => intval($totalFailTimes-$topFailTimes)
            );

        } else {
            $topFailTimes = 0;
            $res = $detailConn->where('day', $day_id)
                                ->where("city", $this->clickLineName)
                                ->groupBy("location")
                                ->orderBy('failTimes','desc')
                                ->selectRaw('location, sum(`'.$detailColumn.'`) as failTimes')
                                ->offset(0)
                                ->limit(30)
                                ->get()
                                ->toArray();

            foreach ($res as $value) {
                $topFailTimes += $value['failTimes'];
            }
            $series[] = array(
                "name" => "top30小区",
                "y" => intval($topFailTimes)
            );

            $totalRes = $conn->where('day_id', $day_id)
                                ->where("city", $this->clickLineName)
                                ->groupBy("city")
                                ->selectRaw('sum(`'.$column.'`) as failTimes')
                                ->get()
                                ->toArray();
            $totalFailTimes = $totalRes[0]['failTimes'];
            $series[] = array(
                "name" => "其余小区",
                "y" => intval($totalFailTimes-$topFailTimes)
            );

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



        if ($this->province != "national") {
            if ($this->city == "") {
                // 2）省级页面
                if ($this->provinces[$this->province] == $this->clickLineName) {
                    // 2.1 点击省级趋势线，显示指标排名bar图，恶化小区分布饼图和失败次数气泡图
                    $result['series'] = $this->getProvinceCitysData();
                    // $result['title'] = $this->clickLineName."-".$this->card;
                    $result['subtitle'] = $this->clickTime;
                    $result['clickLineName'] = $this->clickLineName;
                    // $result['xAxis'] = [$this->clickTime];
                }

            } else {
                // 3) 地市级页面
                // 3.1 点击地市指标趋势线
                // 左侧显示该时间点该地市的TOP30小区列表(小区名，失败次数)。
                // 右侧显示TOP30和其余小区失败次数在全网失败次数比例的饼图分布。
                $result['series'] = $this->getCityData();
                // $result['title'] = $this->clickLineName."-".$this->card;
                $result['subtitle'] = $this->clickTime;
                $result['clickLineName'] = $this->clickLineName;
                // $result['xAxis'] = [$this->clickTime];
            }
        }



        return json_encode($result);
    }

    public function switchTableByType()
    {
        $conn = null;
        switch ($this->type) {
            case 'lte':
                $conn = new B_K_LTE_TDD_HOUR;
                break;
            case 'fdd':
                $conn = new B_K_LTE_FDD_HOUR;
                break;
            case 'nbiot':
                $conn = new B_K_NBIOT_HOUR;
                break;
            case 'volteFdd':
                $conn = new B_K_VOLTE_FDD_HOUR;
                break;
            case 'volteTdd':
                $conn = new B_K_VOLTE_FDD_HOUR;
                break;
            case 'gsm':
                $conn = new B_K_GSM_HOUR;
                break;
        }
        return $conn;
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