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
    
    protected $national;
    protected $drilldownData;
    protected $timeArr;
    protected $citys_series;

    public function __construct()
    {
        $this->provinces = array(
                            "jiangsu"=>"江苏省", 
                            "guangdong"=>"广东省", 
                            "hubei"=>"湖北省"
                        );
        $this->cities = array(
                        // 'nanjing' => '南京', 
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
                            // "nanjing" => "南京",
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
                            'volte'=>'VOLTE', 
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
        $this->drilldownData = [];
        $i = 0;
        foreach ($this->timeArr as $time) {
            $data[$i]['name'] = $time;
            $data[$i]['y'] = rand(90, 100); 
            $plots += $data[$i]['y'];
            $data[$i]['drilldown'] = $time."-national";

            $this->drilldownData[$i]['type'] = "column";
            $this->drilldownData[$i]['id'] = $time."-national";
            $this->drilldownData[$i]['name'] = $time."-national";
            //全国级plot line点击时，呈现‌所有省份的指标(指定时间段内均值)排名的bar plot. 从高到低排序
            $this->drilldownData[$i]['data'] = array(
                array("江苏省", rand(90, 100)),
                array("广东省", rand(90, 100)),
                array("湖北省", rand(90, 100))
            );
            $i++;
        }
        //标识线
        $plots /= 24;
        $this->national = array("name"=>"全国", "spellName"=>"national", "plots"=>round($plots, 2), "data"=>$data); 
    }


    /**
     * 获取各市图表数据
     */
    private function getCitiesData() 
    {
        $this->timeArr = [];
        $this->citys_series = [];
        $field = $this->fields[$this->card];
        if( $this->city == '' ) {
            if( $this->province !== 'national' ) {
                $region = $this->map[$this->province];
                foreach ($region as $city => $cityChinese) {
                    $cities = [];
                    $data = [];
                    $n = 0;
                    $conn = $this->switchTableByTypeAndTimedim();
                    $conn = $conn->where('city', $cityChinese)
                                    ->orderBy('day_id','desc');
                    if ($this->timeDim == true) {
                        $limit = 14;
                    } else {
                        $limit = 24;
                        $conn = $conn->orderBy('hour_id','desc');
                    }
                    $res = $conn->take($limit)
                                ->get()
                                ->toArray();
                    foreach ($res as $r) {
                        $time = str_replace("-", "", $r['day_id']);
                        if ($this->timeDim == false) {
                            $time .= $r['hour_id'] < 10 ? "0".$r['hour_id'] : $r['hour_id'];
                        }
                        array_push($this->timeArr, $time);
                        $data[$n]['name'] = $time;
                        $data[$n]['y'] = floatval($r[$field]); 
                        $data[$n]['drilldown'] = $time.'-'.$this->province;
                        $n++;
                    }
                    $cities['name'] = $cityChinese;
                    $cities['spellName'] = $city;
                    $cities['data'] = $data;
                    array_push($this->citys_series, $cities);
                }
                $this->timeArr = array_unique($this->timeArr);
            }
        } else {
            $cities = [];
            $data = [];
            $n = 0;
            $cityChinese = $this->cities[$this->city];
            $conn = $this->switchTableByTypeAndTimedim();
            $conn = $conn->where('city', $cityChinese)
                        ->orderBy('day_id','desc');
            if ($this->timeDim == true) {
                $limit = 14;
            } else {
                $limit = 24;
                $conn = $conn->orderBy('hour_id','desc');
            }
            $res = $conn->take($limit)
                        ->get()
                        ->toArray();
            foreach ($res as $r) {
                $time = str_replace("-", "", $r['day_id']);
                if ($this->timeDim == false) {
                    $time .= $r['hour_id'] < 10 ? "0".$r['hour_id'] : $r['hour_id'];
                }
                array_push($this->timeArr, $time);
                $data[$n]['name'] = $time;
                $data[$n]['y'] = floatval($r[$field]); 
                $data[$n]['drilldown'] = $time.'-'.$this->province;
                $n++;
            }
            $cities['name'] = $this->cities[$this->city];
            $cities['spellName'] = $this->city;
            $cities['data'] = $data;
            // print_r($cities);
            $this->citys_series = array($cities);
        }
        sort($this->timeArr);
    }


    /**
    * 获取各省市图表数据
    */
    private function getProvincesData()
    {
        $provinceArr = [];
        foreach ($this->provinces as $key => $province) {
            $data = [];
            $drilldownData = [];
            // $test = [];
            $plots = 0;
            $i = 0;
            foreach ($this->timeArr as $time) {
                $data[$i]['name'] = $time;
                $data[$i]['y'] = rand(90, 100); 
                $plots += $data[$i]['y'];
                $data[$i]['drilldown'] = $time.'-'.$key;

                $drilldownData[$i]['type'] = "column";
                $drilldownData[$i]['id'] = $time.'-'.$key;
                $drilldownData[$i]['name'] = $time.'-'.$key;
                $drilldownData[$i]['data'] = [];

                foreach ($this->map[$key] as $k => $v) {
                    array_push($drilldownData[$i]['data'], array($v, rand(90, 100)));
                }
                $this->arr['drilldown'][] = $drilldownData[$i];
                $i++;
            }
            $provinceArr['name'] = $province;
            $provinceArr['spellName'] = array_flip($this->provinces)[$province];
            $provinceArr['data'] = $data;
            //标识线
            $plots /= 24;
            $provinceArr['plots'] = round($plots, 2);
            // array_push($this->arr['series'], $provinceArr);
        }
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

        $this->getCitiesData();

        $this->getNationalData();

        $this->arr = array(
            "title" => strtoupper($this->type).'-'.$this->card, 
            "subtitle" => $this->province, 
            "type" => "line",
            "series" => array(
                $this->national
            ),
            "city-series"=>$this->citys_series,
            "drilldown" => $this->drilldownData
        );

        $this->getProvincesData();

        if( $this->city !== null ) {
            $this->arr['subtitle'] = $this->city;
        }

        $this->arr['assessmentPlots'] = $this->getAssessmentPlots();
        return json_encode($this->arr);
    }

    public function switchTableByTypeAndTimedim()
    {
        $conn = null;
        if ($this->timeDim == false) {
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
                case 'volte':
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
                case 'volte':
                    $conn = new B_K_VOLTE_TDD_DAY;
                    break;
                case 'gsm':
                    $conn = new B_K_GSM_DAY;
                    break;
            }
        }
        return $conn;
    }
}