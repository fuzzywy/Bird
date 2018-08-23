@extends('layouts.app')

@section('content')
<div class="container">
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
     

  <!-- <div class="row">
    <div class="col-12">
        <h1>Hello App!</h1>
        <p>
          <router-link to="/foo">Go to Foo</router-link>
          <router-link to="/bar">Go to Bar</router-link>
        </p>
    </div>  
    <router-view></router-view>
  </div> -->

</div>

@endsection
