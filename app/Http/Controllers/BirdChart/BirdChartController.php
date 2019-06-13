<?php

namespace App\Http\Controllers\BirdChart;

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

class BirdChartController extends Controller
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
    
    protected $allData;
    protected $national;
    protected $citys_series;
    protected $province_series;
    protected $categories;

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

    private function getAssessmentPlots() 
    {
        if ( $this->province === "national" ) {
            return "禁用";
        }
        $status = Configuration::where('location', $this->provinces[$this->province])
                                ->where('operator', $this->mapOperator[$this->operator])
                                ->where('system', $this->mapSystem[$this->type])
                                ->where('index', $this->card)
                                ->get()
                                ->toArray();
        if ( count($status) === 0 ) {
            return "禁用";
        } else {
            return array('status'=> $status[0]['status'], 'assessment'=> $status[0]['assessment']);
        }
    }

    /**
     * 获取全国图表数据
     */
    private function getNationalData() 
    {
        $data = [];
        $plots = 0;

        $n = 0;
        foreach ($this->allData as $time => $provinces) {
            $data[$n]['name'] = $time;
            $temp = [];
            foreach ($provinces as $province => $value) {
                if ($province == "全国") {
                    $data[$n]['y'] = $value['value'];
                    $plots += $data[$n]['y'];
                } else {
                    if (array_key_exists('value', $value)) {
                        $temp[] = array($province, $value['value']);
                    }
                }
            }

            $n++;
        }

        //标识线
        // $plots /= 24;
        // $plots /= count($data);
        $plots = count($data) > 0? $plots/count($data) : 0;
        $this->national = array("name"=>"全国", "spellName"=>"national", "plots"=>round($plots, 2), "data"=>$data); 
    }


    /**
     * 获取各市图表数据
     */
    private function getCitiesData() 
    {
        $this->citys_series = [];
        $cityDataArr = [];
        if( $this->city == '' ) {
            if( $this->province !== 'national' ) {
                foreach ($this->allData as $time => $provinces) {
                    if (array_key_exists($this->provinces[$this->province], $provinces)) {
                        $citys = $provinces[$this->provinces[$this->province]]['citys'];
                        foreach ($citys as $city => $value) {
                            $cityDataArr[$city][] = array('time'=> $time, 'value'=> $value['value']);
                        }
                    }
                }
            }
        } else {
            foreach ($this->allData as $time => $provinces) {
                if (array_key_exists($this->provinces[$this->province], $provinces)) {
                    $citys = $provinces[$this->provinces[$this->province]]['citys'];
                    foreach ($citys as $city => $value) {
                        if ($this->cities[$this->city] == $city) {
                            $cityDataArr[$city][] = array('time'=> $time, 'value'=> $value['value']);
                        }

                    }
                }
            }
        }
        foreach ($cityDataArr as $city => $datas) {
            $cities = [];
            $cities['name'] = $city;
            $cities['spellName'] = array_search($city, $this->cities);

            $data = [];
            $n = 0;
            foreach ($datas as $value) {
                $data[$n]['name'] = $value['time'];
                $data[$n]['y'] = floatval($value['value']); 
                $n++;
            }
            $cities['data'] = $data;
            array_push($this->citys_series, $cities);
        }
    }


    /**
    * 获取各省市图表数据
    */
    private function getProvincesData()
    {
        $this->province_series = [];
        foreach ($this->provinces as $proEN => $proCH) {
            if ($this->province != 'national' && $this->province != $proEN) {
                continue;
            }
            $n = 0;
            $provinceArr = [];
            $data = [];
            $plots = 0;
            foreach ($this->allData as $time => $provinces) {
                $temp = [];
                foreach ($provinces as $province => $value) {
                    if ($province == $proCH && array_key_exists('value', $value) ) {
                        $data[$n]['name'] = $time;
                        $data[$n]['y'] = floatval($value['value']);
                        $plots += $data[$n]['y'];

                        $temp = [];
                        foreach ($value['citys'] as $city => $cityValue) {
                            array_push($temp, array($city, $cityValue['value']));
                        }
                        // 根据value值降序排列
                        array_multisort(array_column($temp, 1), SORT_DESC, $temp);
                        continue;
                    }
                }
    
                $n++;
            }
            $provinceArr['name'] = $proCH;
            $provinceArr['spellName'] = $proEN;
            $provinceArr['data'] = $data;

            //标识线
            // $plots /= 24;
            // if (count($data) > 0) {
            //     $plots /= count($data);
            // }
            $plots = count($data) > 0? $plots/count($data) : 0;
            $provinceArr['plots'] = round($plots, 2);
            array_push($this->province_series, $provinceArr);
        }
    }

    /**
     * 获取一段时间内所有数据
     * 
     * 
     */ 
    public function getAllData()
    {
        date_default_timezone_set('PRC'); 
        $allData = [];
        $field = $this->fields[$this->card];
        $conn = $this->switchTableByTypeAndTimedim();
        // $conn = $conn->orderBy('day_id','desc');
        if ($this->timeDim == "day") {
            // 两周
            $d = strtotime("-14 days");
            $day = date("Y-m-d", $d);
            $conn = $conn->where('day_id', '>=', $day)
                        ->orderBy('day_id');
        } else {
            // 24小时
            $d = strtotime("-1 day");
            $day = date("Y-m-d", $d);
            $hour = date("H", $d);
            $today = date("Y-m-d");
            $conn = $conn->where(function($query) use ($day,$hour){
                    $query->where('day_id', '>=', $day)
                    ->where('hour_id', '>=', $hour);
                })->orWhere('day_id', $today)
                ->orderBy('day_id')
                ->orderBy('hour_id');
        }
        $res = $conn->get()
                    ->toArray();
        $this->categories = [];
        foreach ($res as $r) {
            $time = str_replace("-", "", $r['day_id']);
            if ($this->timeDim == "hour") {
                $time .= $r['hour_id'] < 10 ? "0".$r['hour_id'] : $r['hour_id'];
            }
            if (!in_array($time, $this->categories)) {
                $this->categories[] = $time;
            }
            // 按照time=>省=>市 的结构进行整理
            $province = $r['province'];
            $city = $r['city'];
            $value = floatval($r[$field]);

            if ($province == $city) {
                // 全国或者省级
                $allData[$time][$province]['value'] = $value;
            } else {
                $allData[$time][$province]['citys'][$city]['value'] = $value;
            }
        }
        $this->allData = $allData;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $this->type = Input::get('type');
        $this->city = Input::get('city');
        $this->card = Input::get('card');
        $this->province = Input::get('province');
        $this->timeDim = Input::get('timeDim');
        $this->operator = Input::get('operator');

        $this->getAllData();

        $this->getNationalData();

        $this->getCitiesData();

        $this->getProvincesData();

        $series = $this->province != 'national'? $this->province_series:array_merge( array($this->national),$this->province_series );
        $this->arr = array(
            "title" => strtoupper($this->type).'-'.$this->card, 
            "subtitle" => $this->province, 
            "type" => "line",
            "series" => $series,
            "city-series"=>$this->citys_series,
            "categories"=>$this->categories
        );

        if( $this->city !== null ) {
            $this->arr['subtitle'] = $this->city;
        }

        $this->arr['assessmentPlots'] = $this->getAssessmentPlots();
        return json_encode($this->arr);
    }

    public function switchTableByTypeAndTimedim()
    {
        $conn = null;
        if ($this->timeDim == "hour") {
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