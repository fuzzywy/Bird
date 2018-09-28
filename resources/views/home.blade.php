@extends('layouts.app')

@section('cog')

  <router-view name="BCogForm"></router-view>

@stop

@section('content')
<!-- Home Page -->
<!-- <router-view name="Layout"></router-view> -->
<div class="container">
  <router-view name="BCogFormTable"></router-view>
  <div class="row">
    <div class="col-2">
      <!-- <router-view name="a"></router-view> -->
      <!-- <b-card nobody header="<b>网络概览</b>">
        <b-list-group flush> -->
          <!-- list -->
          <!-- <left-component></left-component> -->
          <!-- default name="BirdSideBar" -->
          <router-view></router-view>
        <!-- </b-list-group>
      </b-card> -->
    </div>
    <div class="col-10">
      <!-- city -->
      <!-- <city-component></city-component> -->
      <router-view name="BLocationNav"></router-view>
      <!-- tab -->
      <!-- <tab-component></tab-component>  -->
      <router-view name="BKpiCard"></router-view>
      <br/>
      <!-- highcharts -->
	    <!-- <highchartsline-component></highchartsline-component> -->
      <router-view name="BLineChart"></router-view>
      <br/>
      <!-- test highcharts -->
      <!-- <test></test> -->
    </div>
  </div>
</div>
<!-- End Home Page -->

<!-- Cog Page -->
<!-- <div class="container">
	<div class="row">
		<div class="col-12">
			<router-view name="BCogFormTable"></router-view>
		</div>
	</div>
</div> -->
<!-- End Cog Page -->

@endsection
