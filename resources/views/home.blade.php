@extends('layouts.app')

@section('content')
<div class="container">
<!-- <router-view></router-view> -->
  <div class="row">
    <div class="col-2">
      <b-card nobody header="<b>网络概览</b>">
        <b-list-group flush>
          <!-- list -->
          <left-component></left-component>
        </b-list-group>
      </b-card>
    </div>
    <div class="col-10">
      <!-- city -->
      <city-component></city-component>
      <!-- tab -->
      <tab-component></tab-component> 
      <br/>
      <!-- highcharts -->
	    <highchartsline-component></highchartsline-component>
      <br/>
      <!-- test highcharts -->
      <!-- <test></test> -->
    </div>
</div>
     
</div>
@endsection
