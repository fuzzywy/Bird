@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-2">
      <b-card nobody header="<b>网络概览</b>">
        <b-list-group flush>
          <!-- list -->
          <!-- <left-component></left-component> -->
          <!-- default name="leftComponent" -->
          <router-view></router-view>
        </b-list-group>
      </b-card>
    </div>
    <div class="col-10">
      <!-- city -->
      <!-- <city-component></city-component> -->
      <router-view name="cityComponent"></router-view>
      <!-- tab -->
      <!-- <tab-component></tab-component>  -->
      <router-view name="tabComponent"></router-view>
      <br/>
      <!-- highcharts -->
	    <!-- <highchartsline-component></highchartsline-component> -->
      <router-view name="highchartslineComponent"></router-view>
      <br/>
      <!-- test highcharts -->
      <!-- <test></test> -->
    </div>
  </div>
  
</div>

@endsection
