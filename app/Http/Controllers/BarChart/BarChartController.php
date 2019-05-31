<?php

namespace App\Http\Controllers\BarChart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Configuration;

use App\Models\B_K_GSM_DAY;
use App\Models\B_K_GSM_HOUR;
use App\Models\B_K_LTE_FDD_DAY;
use App\Models\B_K_LTE_FDD_HOUR;
use App\Models\B_K_LTE_TDD_DAY;
use App\Models\B_K_LTE_TDD_HOUR;
use App\Models\B_K_NBIOT_DAY;
use App\Models\B_K_NBIOT_HOUR;
use App\Models\B_K_VOLTE_FDD_HOUR;
use App\Models\B_K_VOLTE_TDD_HOUR;
use App\Models\B_K_VOLTE_FDD_DAY;
use App\Models\B_K_VOLTE_TDD_DAY;

class BarChartController extends Controller
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

    public function getAllCitysData()
    {
        $conn = $this->switchTableByTypeAndTimedim();
        // 拆分clickTime，获取day_id和hour_id
        
        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);
        }
        $conn = $conn->where('day_id', $day_id);
        if ($this->timeDim == "hour") {
            $conn = $conn->where('hour_id', $hour_id);
        }
        $res = $conn->whereRaw("`city` != `province`")
                    ->orderBy($this->fields[$this->card], "desc")
                    ->get(['city',$this->fields[$this->card]])
                    ->toArray();
        $series = [];
        foreach ($res as $value) {
            $series[] = array(
                "name" => $value['city'],
                "data" => [floatval($value[$this->fields[$this->card]])]
            );
        }
        return $series;
    }

    public function getProvinceCitysData()
    {
        $conn = $this->switchTableByTypeAndTimedim();
        // 拆分clickTime，获取day_id和hour_id
        
        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);
        }
        $conn = $conn->where('day_id', $day_id);
        if ($this->timeDim == "hour") {
            $conn = $conn->where('hour_id', $hour_id);
        }
        $res = $conn->where('province', $this->clickLineName)
                    ->where('city', '!=', $this->clickLineName)
                    ->orderBy($this->fields[$this->card], "desc")
                    ->get(['city',$this->fields[$this->card]])
                    ->toArray();
        $series = [];
        foreach ($res as $value) {
            $series[] = array(
                "name" => $value['city'],
                "data" => [floatval($value[$this->fields[$this->card]])]
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
        if($this->province == "national") {
        //1) 全国页面
            if ($this->clickLineName == "全国") {
                // 1.1 点击全国趋势线，显示所有地市的指标排名bar图（在点击时间点）
                $result['series'] = $this->getAllCitysData();
                $result['title'] = "所有地市-".$this->card;
                $result['subtitle'] = $this->clickTime;
                $result['xAxis'] = [$this->clickTime];

            } else {
                // 1.2 点击省级趋势线，显示该省所有地市指标排名bar图（在点击事件点）
                $result['series'] = $this->getProvinceCitysData();
                $result['title'] = $this->clickLineName."-".$this->card;
                $result['subtitle'] = $this->clickTime;
                $result['xAxis'] = [$this->clickTime];
            }
        } else {
            if ($this->city == "") {
                // 2）省级页面
                if ($this->provinces[$this->province] == $this->clickLineName) {
                    // 2.1 点击省级趋势线，显示指标排名bar图，恶化小区分布饼图和失败次数气泡图
                    $result['series'] = $this->getProvinceCitysData();
                    $result['title'] = $this->clickLineName."-".$this->card;
                    $result['subtitle'] = $this->clickTime;
                    $result['xAxis'] = [$this->clickTime];
                } else {
                    // 2.2 点击地市趋势线，跳转到地市级视图(注意指标卡片以及地市导航需要同步刷新)

                }

            } else {
                // 3) 地市级页面
                // 3.1 点击地市指标趋势线
                // 左侧显示该时间点该地市的TOP30小区列表(小区名，失败次数)。
                // 右侧显示TOP30和其余小区失败次数在全网失败次数比例的饼图分布。

            }
        }



        return json_encode($result);
    }

    public function switchTableByTypeAndTimedim()
    {
        $conn = null;
        if ($this->timeDim == 'hour') {
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
        } else {
            switch ($this->type) {
                case 'lte':
                    $conn = new B_K_LTE_TDD_DAY;
                    break;
                case 'fdd':
                    $conn = new B_K_LTE_FDD_DAY;
                    break;
                case 'nbiot':
                    $conn = new B_K_NBIOT_DAY;
                    break;
                case 'volteTdd':
                    $conn = new B_K_VOLTE_TDD_DAY;
                    break;
                case 'volteFdd':
                    $conn = new B_K_VOLTE_FDD_DAY;
                    break;
                case 'gsm':
                    $conn = new B_K_GSM_DAY;
                    break;
            }
        }
        return $conn;
    }
}