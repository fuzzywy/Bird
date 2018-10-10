<?php

return [
	'test' => [
		'test' => '中文'
	],

	'language' => '语言',

	'province' => '全省',
	'overview'=>[
		'access'	=>	'无线接通率',
		'lost'		=>	'无线掉线率',
		'handover'	=>	'切换成功率',
		'srvcc'	=>	'SRVCC切换成功率',
		'upackagelost'	=>	'上行丢包率',
		'dpackagelost'	=>	'下行丢包率',
	],
	'city'=>[
		"常州"=>"常州",
		"苏州"=>"苏州",
		"无锡"=>"无锡",
		"镇江"=>"镇江",
		"南通"=>"南通",
	],
	"scale"=>[
		"carrier"=>"载波",
		"cell"=>"小区",
		"erbs"=>"基站",
		"high_peed"=>"开启高速移动基站",
		"co_enhance"=>"开启覆盖增强基站",
		"ca_agg"=>"开启载波聚合基站",
		"tch"=>"TCH信道数",
		"pdch"=>"PDCH信道数",
	],
	"loadSurvey"=>[
		"flow"=>"忙时总流量",
		"volte_traffic"=>"忙时VOLTE总话务量",
		"rrc_users"=>"忙时全网RRC平均用户",
		"rrc_cell_user_mean"=>"忙时每小区RRC平均用户",
		"rrc_cell_users_max"=>"忙时每小区RRC最大用户均值",
		"rrc_cell_band_users"=>"单位频段每小区RRC用户数",
		"flow_tti"=>"flow_tti",
		"cce"=>"忙时CCE利用率均值",
		"prb"=>"忙时PRB利用率均值",
		"tel_traffic"=>"忙时2G语音话务量",
		"data_traffic"=>"忙时2G数据流量",
		"tel_traffic_tch"=>"2G每TCH信道话务量",
		"data_traffic_pdch"=>"2G每PDCH数据流量",
		"wireless_rate"=>"无线利用率",
		"flow_tti" => "忙时单位带宽",
		"rrc_users_cell"=>"忙时每小区RRC最大用户数",
		"npdcch"=>"忙时NPDCCH利用率",
	]
];