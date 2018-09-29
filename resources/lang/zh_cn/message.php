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
	],
	"loadSurvey"=>[
		"flow"=>"上下行流量之和",
		"volte_traffic"=>"Volte总话务量",
		"rrc_users"=>"RRC平均用户",
		"rrc_cell_user_mean"=>"每小区RRC平均用户",
		"rrc_cell_users_max"=>"rrc最大用户/总小区数",
		"rrc_cell_band_users"=>"rrc平均用户/全网小区频宽之和",
		"flow_tti"=>"flow_tti",
		"cce"=>"CCE利用率均值",
		"prb"=>"PRB利用率",
		"tel_traffic"=>"语音话务量",
		"data_traffic"=>"下行流量+上行流量",
		"tel_traffic_tch"=>"tel_traffic/TCH信道数",
		"data_traffic_pdch"=>"data_traffic/PDCH信道数",
		"wireless_rate"=>"无线利用率",
		"rrc_users_cell"=>"RRC最大用户",
		"npdcch"=>"NPDCCH利用率",
	]
];