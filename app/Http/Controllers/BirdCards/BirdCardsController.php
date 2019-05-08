<?php

namespace App\Http\Controllers\BirdCards;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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

class BirdCardsController extends Controller
{
    protected $bSideBar;
    protected $operator;
    protected $province;
    protected $city;
    protected $type;

    public function __construct()
    {
        $this->provinces = array(
            "jiangsu"=>"江苏", 
            "guangdong"=>"广东", 
            "hubei"=>"湖北"
        );
        $this->cities = array(
            'wuxi' => '无锡', 
            'suzhou'=> '苏州', 
            'changzhou'=>'常州', 
            'zhenjiang'=>'镇江', 
            'nantong'=>'南通',
            'nanjing'=> '南京'
        );
        $this->fields = array(
            'r_access'=>array(
                'name'=> '无线接通率',
                'key'=> '%'
            ),
            'r_lost'=>array(
                'name'=> '无线掉线率',
                'key'=> '%'
            ),
            'r_handover'=>array(
                'name'=> '切换成功率',
                'key'=> '%'
            ),
            'r_highInterfere'=>array(
                'name'=> '高干扰小区比例',
                'key'=> 'dBm'
            ),
            'r_srvcc'=>array(
                'name'=> 'SRVCC切换成功率',
                'key'=> '%'
            ),
            'r_u_packetlost'=>array(
                'name'=> '上行丢包率',
                'key'=> '%'
            ),
            'r_d_packetlost'=>array(
                'name'=> '下行丢包率',
                'key'=> '%'
            ),
            'r_u_floor'=>array(
                'name'=> 'NBIOT上行底躁（>-110比率）',
                'key'=> 'dBm'
            )
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $this->bSideBar = Input::get('bSideBar');
        $this->operator = Input::get('operator');
        $this->province = Input::get('province');
        $this->city = Input::get('city');
        $this->type = Input::get('type');

        $conn = $this->switchTable();
        if ($this->province == "national") {
            $result =  $this->getNationalData($conn);
        } else {
            if ($this->city == "") {
                // 省级
                $result =  $this->getDataByProvince($conn);
            } else {
                // 地市级别
                $result =  $this->getDataByCity($conn);
            }
        }

        return $result;
    }

    public function switchTable()
    {
        $conn = null;
        if ($this->province != "national") {
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

    public function getColor($value)
    {
        $color = "green";
        if ($value > 10) {
            // 粗略筛选那些应该是很大的值
            if ($value <= 98) {
                $color = "red";
            } else if ($value <=99) {
                $color = "yellow";
            } else if ($value <=100) {
                $color = "green";
            }
        } else {
            // 粗略筛选那些应该是很小的值
            if ($value >= 2) {
                $color = "red";
            } else if ($value >= 1) {
                $color = "yellow";
            } else if ($value >= 0) {
                $color = "green";
            }
        }
        return $color;
    }

    public function getDataByCity($conn)
    {
        $cityChinese = $this->cities[$this->city];
        $res = $conn->where('city', $cityChinese)
                    ->orderBy('day_id','desc')
                    ->orderBy('hour_id','desc')
                    ->get()
                    ->toArray();
        $result = [];
        if (count($res) > 0) {
            $time = $res[0]['day_id']." ".$res[0]['hour_id'];
            foreach ($res[0] as $key => $value) {
                if (array_key_exists($key, $this->fields)) {
                    $class = null;
                    $tend = null;
                    if (count($res) > 1) {
                        $oldValue = $res[1][$key];
                        $tend = abs(round($value - $res[1][$key],2));
                        $class = $value - $res[1][$key] >=0 ?"arrow_upward":"arrow_downward";
                    }
                    $result[] = array(
                                    "class" => $class,
                                    "tend" => $tend,
                                    "color" => $this->getColor($value),
                                    "data" => $value.$this->fields[$key]['key'],
                                    "type" => $this->fields[$key]['name'],
                                    "flex" => 3,
                                    "time" => $time
                                );
                }
            }
        }
        return $result;
    }

    public function getDataByProvince($conn)
    {
        $province = $this->provinces[$this->province];
        $res = $conn->where('city', $province)
                    ->orderBy('day_id','desc')
                    ->orderBy('hour_id','desc')
                    ->get()
                    ->toArray();
        $result = [];
        if (count($res) > 0) {
            $time = $res[0]['day_id']." ".$res[0]['hour_id'];
            foreach ($res[0] as $key => $value) {
                if (array_key_exists($key, $this->fields)) {
                    $class = null;
                    $tend = null;
                    if (count($res) > 1) {
                        $oldValue = $res[1][$key];
                        $tend = abs(round($value - $res[1][$key],2));
                        $class = $value - $res[1][$key] >=0 ?"arrow_upward":"arrow_downward";
                    }
                    $result[] = array(
                                    "class" => $class,
                                    "tend" => $tend,
                                    "color" => $this->getColor($value),
                                    "data" => $value.$this->fields[$key]['key'],
                                    "type" => $this->fields[$key]['name'],
                                    "flex" => 3,
                                    "time" => $time
                                );
                }
            }
        }
        return $result;
    }

    public function getNationalData($conn)
    {
        // $province = $this->provinces[$this->province];
        $res = $conn->where('city', '全国')
                    ->orderBy('day_id','desc')
                    // ->orderBy('hour_id','desc')
                    ->get()
                    ->toArray();
        $result = [];
        if (count($res) > 0) {
            // $time = $res[0]['day_id']." ".$res[0]['hour_id'];
            $time = $res[0]['day_id'];
            foreach ($res[0] as $key => $value) {
                if (array_key_exists($key, $this->fields)) {
                    $class = null;
                    $tend = null;
                    if (count($res) > 1) {
                        $oldValue = $res[1][$key];
                        $tend = abs(round($value - $res[1][$key],2));
                        $class = $value - $res[1][$key] >=0 ?"arrow_upward":"arrow_downward";
                    }
                    $result[] = array(
                                    "class" => $class,
                                    "tend" => $tend,
                                    "color" => $this->getColor($value),
                                    "data" => $value.$this->fields[$key]['key'],
                                    "type" => $this->fields[$key]['name'],
                                    "flex" => 3,
                                    "time" => $time
                                );
                }
            }
        }
        return $result;
    }

}
