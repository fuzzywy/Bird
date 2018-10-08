<template>
	<div class='container'>
	  <div class="row">
	    <div class="col-2">
	          <BirdSideBarComponent></BirdSideBarComponent>
	    </div>
	    <div class="col-10">
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
	
</style>

<script>
	import BirdSideBarComponent from './BirdSideBarComponent.vue';
	import BLocationNavComponent from './BLocationNavComponent.vue';
	import BKpiCardComponent from './BKpiCardComponent.vue';
	import BLineChartComponent from './BLineChartComponent.vue';
	export default {
		components: {
            BirdSideBarComponent,
            BLocationNavComponent,
            BKpiCardComponent,
            BLineChartComponent
        },
	}
</script>