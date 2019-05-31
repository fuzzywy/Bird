<?php

namespace App\Http\Controllers\TopCell;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Configuration;

use App\Models\B_K_LTE_TDD_ACCESS_D_TOP;
use App\Models\B_K_LTE_TDD_LOST_D_TOP;
use App\Models\B_K_LTE_TDD_HANDOVER_D_TOP;


class TopCellController extends Controller
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

    public function getTopCells()
    {
        $tables = [];
        $tconn = $this->getTopTableByTypeAndCard();
        $topConn = $tconn['topConn'];
        $column = $tconn['column'];
        if (!$topConn) {
            return [];
        }

        $day_id = substr($this->clickTime, 0, 4)."-".substr($this->clickTime, 4, 2)."-".substr($this->clickTime, 6, 2);
        if ($this->timeDim == "hour") {
            $hour_id = substr($this->clickTime, 8, 2);
            $tables = $topConn->where('day_id', $day_id)
                            ->where("hour_id", $hour_id)
                            ->where("city", $this->clickLineName)
                            ->get(['day_id','hour_id','city','cell',$column.' as failTimes'])
                            ->toArray();
            

        } else {
            $tables = $topConn->where("day_id", $day_id)
                            ->where("city", $this->clickLineName)
                            ->groupBy("cell")
                            ->orderBy("failTimes","desc")
                            ->offset(0)
                            ->limit(30)
                            ->selectRaw('day_id, city, cell, sum(`'.$column.'`) as failTimes')
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