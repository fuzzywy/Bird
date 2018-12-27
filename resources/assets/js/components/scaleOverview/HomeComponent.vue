<template>
	<div class='container'>
	  <div class="row top" :id="equipment">
	    <div :class="device">
	          <BirdSideBarComponent></BirdSideBarComponent>
	    </div>
	    <div :class="device1">
	      <BLocationNavComponent></BLocationNavComponent>
	      <BKpiCardComponent></BKpiCardComponent>
	      <br/>
	      <BLineChartComponent></BLineChartComponent>
	      <br/>
	    </div>
	  </div>
	</div>
</template>

<style>
	.top {
		margin-top: 50px;
	}
	#Android > .col-xs-12 > .siderbar {
		position: static;
		left: 15px;
		right: 20px;
		width: 450px;
	}
	#Android > .col-xs-12 > div > div > div > div > div > div > div > .floatleft {
		position: relative;
		padding: 3.8px 3.8px;
		vertical-align: middle;
		/*background-color: #20a8d8;*/
		width: 38px;
		height: 100px;
		float: left;
		margin: 3.8px 1.9px 1.9px 3.8px;
	}
	#Android > .col-xs-12 > div > div > div > div > div > div > div > .postdata {
		font-size: 20px;
		padding-top: 7.6px !important;
	}
	#Android > .col-xs-12 > div > div > div > div > div > div > div > .posttype {
		font-size: 20px
	}
	#Android > .col-xs-12 > div > div > div > div > div > div > .scalecard {
		background-color: #20a8d8;
		/*width:169.2px; 
		height: 123.4px;*/
		border-radius: 1px;
		vertical-align: baseline !important;
	}
	#Android > .col-xs-12 > div > div > div > div > div > .col-3 {
		padding-bottom: 0px !important;
	}
	#Pad > .col-12 > .siderbar {
		position: static;
		left: 15px;
		right: 20px;
	}
	#Pad > .col-12 > div > div > div > div > div > div > div > .floatleft {
		position: relative;
		padding: 7.7px 7.7px;
		vertical-align: middle;
		/*background-color: #20a8d8;*/
		width: 60px;
		height: 100px;
		float: left;
		margin: 3.8px 1.9px 1.9px 3.8px;
	}
	#Pad > .col-12 > div > div > div > div > div > div > div > .postdata {
		font-size: 19.25px;
		padding-top: 15.4px !important;
	}
	#Pad > .col-12 > div > div > div > div > div > div > div > .posttype {
		font-size: 11.55px
	}
	#Pad > .col-12 > div > div > div > div > div > div > .scalecard {
		background-color: #20a8d8; 
		/*height: 92.4px;*/
		border-radius: 1.14px;
		vertical-align: baseline !important;
	}
	#Pad > .col-12 > div > div > div > div > div > .col-4 {
		padding-bottom: 0px !important;
	}
</style>

<script>
	import BirdSideBarComponent from './BirdSideBarComponent.vue';
	import BLocationNavComponent from './BLocationNavComponent.vue';
	import BKpiCardComponent from './BKpiCardComponent.vue';
	import BLineChartComponent from './BLineChartComponent.vue';
	var userAgent = navigator.userAgent; 
    var isAndroid = userAgent.indexOf('Android') > -1 || userAgent.indexOf('Adr') > -1;
    var isIphone = userAgent.indexOf('iPhone') > -1 || userAgent.indexOf('Adr') > -1;
    var isPC = userAgent.indexOf('Windows') > -1 || userAgent.indexOf('Adr') > -1;
    var isPad = userAgent.indexOf('iPad') > -1 || userAgent.indexOf('Adr') > -1;
    var deviceClass,deviceClass1,equipment;
    if (isAndroid || isIphone) {
    	deviceClass = "col-xs-12";
    	deviceClass1 = "col-xs-12";
    	equipment = "Android";
    } else if (isPC) {
    	deviceClass = "col-2";
    	deviceClass1 = "col-10";
    	equipment = "PC";
    } else if (isPad) {
    	deviceClass = "col-12";
    	deviceClass1 = "col-12";
    	equipment = "Pad";
    }
	export default {
		data() {
			return {
				device: deviceClass,
				device1: deviceClass1,
				equipment: equipment,
			}
		},
		components: {
            BirdSideBarComponent,
            BLocationNavComponent,
            BKpiCardComponent,
            BLineChartComponent
        },
	}
</script>