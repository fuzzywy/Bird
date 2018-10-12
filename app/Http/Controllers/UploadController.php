<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Databaseconns;
use App\Models\Databaseconn2G;

class UploadController extends Controller 
{
	public function uploadCog() {
		$data = Input::get('data'); //注意post传值格式
		$arr = explode('&', $data);
		$ip = '';
		$connname = '';
		$city = '';
		$type = 'LTE';
		$port = '';
		$user = '';
		$pwd = '';
		$database = '';
		$subnetwork = '';
		$subnetworkfdd = '';
		$subnetworknbiot = '';
		foreach ($arr as $value) {
			$d = explode('=', $value);
			switch ( $d[0] ) {
				case 'ip':
					$ip = $d[1];
					break;
				case 'connname':
					$connname = $d[1];
					break;
				case 'city':
					$city = urldecode($d[1]);
					break;		
				case 'type':
					$type = $d[1];
					break;
				case 'port':
					$port = $d[1];
					break;
				case 'database':
					$database = $d[1];
					break;
				case 'user':
					$user = $d[1];
					break;
				case 'pwd':
					$pwd = $d[1];
					break;
				case 'subnetwork':
					$subnetwork = urldecode($d[1]);
					break;
				case 'subnetworkfdd':
					$subnetworkfdd = urldecode($d[1]);
					break;
				case 'subnetworknbiot':
					$subnetworknbiot = urldecode($d[1]);
					break;	
				default:
					break;
			}
		}
		if ( strtoupper( $type ) === 'LTE' ) {
			Databaseconns::firstOrCreate(['host' => $ip]);
			Databaseconns::where('host', $ip)
				->update(['connName' => $connname, 'cityChinese' => $city, 'port' => $port, 'dbName' => $database, 'userName' => $user, 'password' => $pwd, 'subNetwork' => $subnetwork, 'subNetworkFdd' => $subnetworkfdd, 'subNetworkNbiot' => $subnetworknbiot ]);

			/*Databaseconns::updateOrCreate(
				[ 'host' => $ip ],
				[ 'connName' => $connname ]
			);*/
		} elseif ( strtoupper( $type ) === 'GSM' ) {
			Databaseconn2G::firstOrCreate(['host' => $ip]);
			Databaseconn2G::where('host', $ip)
				->update(['connName' => $connname, 'cityChinese' => $city, 'port' => $port, 'dbName' => $database, 'userName' => $user, 'password' => $pwd ]);
		} else {
			// return false;
		}
	}

	public function showCog() {
		// echo 'showCog';
		// print_r(Databaseconns::get()->toArray());
		$arr = array();
		$i = 0;
		foreach (Databaseconns::get()->toArray() as $key => $value) {
			$arr[$i]['id'] = $i;
			$arr[$i]['ip'] = $value['host'];
			$arr[$i]['connname'] = $value['connName'];
			$arr[$i]['city'] = $value['cityChinese'];
			$arr[$i]['port'] = $value['port'];
			$arr[$i]['database'] = $value['dbName'];
			$arr[$i]['user'] = $value['userName'];
			$arr[$i]['pwd'] = $value['password'];
			$arr[$i]['subnetwork'] = $value['subNetwork'];
			$arr[$i]['subnetworkfdd'] = $value['subNetworkFdd'];
			$arr[$i]['subnetworknbiot'] = $value['subNetworkNbiot'];
			$arr[$i++]['type'] = 'LTE';
		}
		foreach (Databaseconn2G::get()->toArray() as $key => $value) {
			$arr[$i]['id'] = $i;
			$arr[$i]['ip'] = $value['host'];
			$arr[$i]['connname'] = $value['connName'];
			$arr[$i]['city'] = $value['cityChinese'];
			$arr[$i]['port'] = $value['port'];
			$arr[$i]['database'] = $value['dbName'];
			$arr[$i]['user'] = $value['userName'];
			$arr[$i]['pwd'] = $value['password'];
			$arr[$i]['subnetwork'] = '-';
			$arr[$i]['subnetworkfdd'] = '-';
			$arr[$i]['subnetworknbiot'] = '-';
			$arr[$i++]['type'] = 'GSM';
		}
		/*$arr  = array(
					array('id' => 0, 'ip' => '192.168.0.1', 'port' => intval('3306'), 'database' => 't1', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE', 'subnetwork' => 'Changzhou1_LTE,Changzhou2_LTE,Changzhou3_LTE,Changzhou4_LTE,Changzhou5_LTE,Changzhou6_LTE,Changzhou7_LTE,Changzhou8_LTE,Changzhou9_LTE,Changzhou10_LTE,Changzhou11_LTE,Changzhou12_LTE,Changzhou13_LTE,Changzhou14_LTE,Changzhou1_TDG2,ChangZhou,Changzhou_NBFdd,Tmp_cz1td_g1,Tmp_cz1td_g2' ),
					array('id' => 1, 'ip' => '192.168.0.2', 'port' => intval('33061'), 'database' => 't2', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 2, 'ip' => '192.168.0.3', 'port' => intval('33062'), 'database' => 't3', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'GSM' ),
					array('id' => 3, 'ip' => '192.168.0.4', 'port' => intval('33063'), 'database' => 't4', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 4, 'ip' => '192.168.0.5', 'port' => intval('33064'), 'database' => 't5', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'LTE' ),
					array('id' => 5, 'ip' => '192.168.0.6', 'port' => intval('33065'), 'database' => 't6', 'user' => 'root', 'pwd' => 'mongs', 'type' => 'GSM' )
				);*/
		return $arr;
	}

	public function deleteCog() {
		$data = Input::get('data');
		$arr = explode('&', $data);
		$ip = '';
		$type = 'LTE';
		foreach ($arr as $value) {
			$d = explode('=', $value);
			switch ( $d[0] ) {
				case 'ip':
					$ip = $d[1];
					break;
				case 'type':
					$type = $d[1];
					break;
				default:
					break;
			}
		}
		if ( strtoupper( $type ) === 'LTE' ) {
			Databaseconns::where('host', $ip)->delete();
			// print_r(Databaseconns::where('host', $ip)->delete()->toSql());
			// return true;
		} elseif ( strtoupper( $type ) === 'GSM' ) {
			Databaseconn2G::where('host', $ip)->delete();
			// return true;
		} else {
			// return false;
		}
	}
}
