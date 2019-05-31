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

use App\Models\B_K_LTE_TDD_ACCESS_D_TOP;
use App\Models\B_K_LTE_TDD_LOST_D_TOP;
use App\Models\B_K_LTE_TDD_HANDOVER_D_TOP;


class PieChartController extends Controller
{
    protected $provinces;
    protected $map;
    protected $mapOperator;
    protected $mapSystem;
    protected $arr;
    
    protected $type;
    protected $city;
    protected $card;
    protected $province;
    protected $timeDim;
    protected $operator;
    protected $clickTime;
    protected $clickLineName;
    
    protected $allData;
    protected $national;
    protected $citys_series;
    protected $province_series;
    // protected $drilldownData;

    public function __construct()
    {
        $this->provinces = array(
            "jiangsu"=>"江苏", 
            "guangdong"=>"广东", 
            "hubei"=>"湖北"
        );
        $this->cities = array(
            'nanjing' => '南京', 
            'wuxi' => '无锡', 
            'suzhou'=> '苏州', 
            'changzhou'=>'常州', 
            'zhenjiang'=>'镇江', 
            'nantong'=>'南通', 
            'jingzhou'=>'荆州', 
            'wuhan'=>'武汉', 
            'guangzhou'=>'广州', 
            'qingyuan'=>'清远'
        );
        $this->map = array(
            "jiangsu"=>array(
                "nanjing" => "南京",
                "chagnzhou" => "常州",
                "wuxi" => "无锡",
                "suzhou" => "苏州",
                "nantong"=> "南通",
                "zhenjiang"=>"镇江"
            ),
            "guangdong"=>array(
                "guangzhou" => "广州",
                "qingyuan" => "清远"
            ),
            "hubei"=>array(
                "wuhan" => "武汉",
                "jingzhou" => "荆州"
            )
        );
        $this->mapOperator = array(
            "mobile"=>"中国移动", 
            "unicom"=>"中国联通",
            "telecommunications"=>"中国电信"
        );
        $this->mapSystem = array(
            'lte' => 'LTE-TDD', 
            'fdd' => 'LTE-FDD',
            'nbiot'=> 'NBIOT', 
            'volteTdd'=>'VOLTE-TDD', 
            'volteFdd'=>'VOLTE-FDD', 
            'gsm'=>'GSM'
        );
        $this->fields = array(
            '无线接通率'=>'c_d_access',
            '无线掉线率'=>'c_d_lost',
            '切换成功率'=>'c_d_handover',
            // '高干扰小区比例'=>'r_highInterfere',
            'SRVCC切换成功率'=>'c_d_srvcc',
            '上行丢包率'=>'c_d_upacketlost',
            '下行丢包率'=>'c_d_dpacketlost'
            // 'NBIOT上行底躁（>-110比率）'=>'r_u_floor'
        );
    }

    public function getProvinceCitysData()
    {
        $series = [];
        $conn = $this->switchTableByType();
        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        $conn = $conn->where('day_id', $day_id);
        $column = $this->fields[$this->card];
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
            // 是day就平均一下当天
            $res = $conn->where('province', $this->clickLineName)
                        ->where('city', '!=', $this->clickLineName)
                        ->groupBy('city')
                        ->selectRaw('city, avg(`'.$column.'`) as '.$column)
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
        $tconn = $this->getTopTableByTypeAndCard();
        $topConn = $tconn['topConn'];
        $column = $tconn['column'];
        if (!$topConn) {
            return [];
        }
        $conn = $this->switchTableByType();

        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);
            $topRes = $topConn->where('day_id', $day_id)
                                ->where("hour_id", $hour_id)
                                ->where("city", $this->clickLineName)
                                ->groupBy("city")
                                ->selectRaw('sum(`'.$column.'`) as failTimes')
                                ->get()
                                ->toArray();
            $topFailTimes = $topRes[0]['failTimes'];
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
            $res = $topConn->where("day_id", $day_id)
                            ->where("city", $this->clickLineName)
                            ->groupBy("cell")
                            ->orderBy("failTimes","desc")
                            ->offset(0)
                            ->limit(30)
                            ->selectRaw('cell, sum(`'.$column.'`) as failTimes')
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
                    $result['subtitle'] = $this->clickLineName."-".$this->clickTime;
                    // $result['xAxis'] = [$this->clickTime];
                }

            } else {
                // 3) 地市级页面
                // 3.1 点击地市指标趋势线
                // 左侧显示该时间点该地市的TOP30小区列表(小区名，失败次数)。
                // 右侧显示TOP30和其余小区失败次数在全网失败次数比例的饼图分布。
                $result['series'] = $this->getCityData();
                // $result['title'] = $this->clickLineName."-".$this->card;
                $result['subtitle'] = $this->clickLineName."-".$this->clickTime;
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

    public function getTopTableByTypeAndCard()
    {
        $topConn = null;
        $column = "";
        if ($this->type == 'lte') {
            switch ($this->card) {
                case '无线接通率':
                    $topConn = new B_K_LTE_TDD_ACCESS_D_TOP;
                    $column = "c_f_access";
                    break;
                case '无线掉线率':
                    $topConn = new B_K_LTE_TDD_LOST_D_TOP;
                    $column = "c_f_lost";
                    break;
                case '切换成功率':
                    $topConn = new B_K_LTE_TDD_HANDOVER_D_TOP;
                    $column = "c_f_handover";
                    break;
            }
        }
        return array(
            "topConn"=>$topConn,
            "column"=>$column
        );
    }
}