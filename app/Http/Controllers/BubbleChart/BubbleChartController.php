<?php

namespace App\Http\Controllers\BubbleChart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Configuration;

use App\Models\B_K_LTE_TDD_ACCESS_D_TOP;
use App\Models\B_K_LTE_TDD_LOST_D_TOP;
use App\Models\B_K_LTE_TDD_HANDOVER_D_TOP;
use App\Models\B_K_LTE_TDD_HOUR_ACCESS;
use App\Models\B_K_LTE_TDD_HOUR_LOST;
use App\Models\B_K_LTE_TDD_HOUR_HANDOVER;


class BubbleChartController extends Controller
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
            '无线接通率'=>'r_access',
            '无线掉线率'=>'r_lost',
            '切换成功率'=>'r_handover',
            '高干扰小区比例'=>'r_highInterfere',
            'SRVCC切换成功率'=>'r_srvcc',
            '上行丢包率'=>'r_u_packetlost',
            '下行丢包率'=>'r_d_packetlost',
            'NBIOT上行底躁（>-110比率）'=>'r_u_floor'
        );
    }

    public function getData()
    {
        $conn = $this->getTableByTypeAndCard();
        $topConn = $conn['topConn'];
        $hourConn = $conn['hourConn'];
        $column = $conn['column'];
        if (!$topConn) {
            return [];
        }
        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        // 先获取城市
        $cityArr = $topConn->distinct("city")
                            ->where("province", $this->clickLineName)
                            ->where("day_id", $day_id)
                            ->get(['city'])
                            ->toArray();
        $series = [];
        foreach ($cityArr as $city) {
            $temp = [];
            $city = $city['city'];
            $temp['name'] = $city;
            $temp['data'] = [];
            $res = $topConn->where("day_id", $day_id)
                            ->where("city", $city)
                            ->groupBy("cell")
                            ->orderBy("failTimes","desc")
                            ->offset(0)
                            ->limit(30)
                            ->selectRaw('cell, sum(`'.$column.'`) as failTimes')
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
                    $top10cell[] = $res[$i]['cell'];
                    $top10FaliTimes += $res[$i]['failTimes'];
                } else if ($i < 20) {
                    $top20cell[] = $res[$i]['cell'];
                    $top20FaliTimes += $res[$i]['failTimes'];
                } else {
                    $top30cell[] = $res[$i]['cell'];
                    $top30FaliTimes += $res[$i]['failTimes'];
                }
            }
            $threeDayEarlier = date('Y-m-d', strtotime("-2 day", strtotime($day_id)));
            $top10FaliFreq = $hourConn->where("day_id", ">=", $threeDayEarlier)
                                        ->where("day_id", "<=", $day_id)
                                        ->whereIn("location", $top10cell)
                                        ->count();
            $top20FaliFreq = $hourConn->where("day_id", ">=", $threeDayEarlier)
                                        ->where("day_id", "<=", $day_id)
                                        ->whereIn("location", $top20cell)
                                        ->count();
            $top30FaliFreq = $hourConn->where("day_id", ">=", $threeDayEarlier)
                                        ->where("day_id", "<=", $day_id)
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

    public function getTableByTypeAndCard()
    {
        $topConn = null;
        $hourConn = null;
        $column = "";
        if ($this->type == 'lte') {
            switch ($this->card) {
                case '无线接通率':
                    $topConn = new B_K_LTE_TDD_ACCESS_D_TOP;
                    $hourConn = new B_K_LTE_TDD_HOUR_ACCESS;
                    $column = "c_f_access";
                    break;
                case '无线掉线率':
                    $topConn = new B_K_LTE_TDD_LOST_D_TOP;
                    $hourConn = new B_K_LTE_TDD_HOUR_LOST;
                    $column = "c_f_lost";
                    break;
                case '切换成功率':
                    $topConn = new B_K_LTE_TDD_HANDOVER_D_TOP;
                    $hourConn = new B_K_LTE_TDD_HOUR_HANDOVER;
                    $column = "c_f_handover";
                    break;
            }
        }
        return array(
            "topConn"=>$topConn,
            "hourConn"=>$hourConn,
            "column"=>$column
        );
    }
}