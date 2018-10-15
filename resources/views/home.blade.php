@extends('layouts.app')

@section('cog')

  <router-view name="BCogForm"></router-view>
  <router-view name="BCogFormBack"></router-view>

@stop

@section('content')
<router-view name="HomeComponent"></router-view>
<router-view name="BCogFormTable"></router-view>

<router-view name="BPieChart"></router-view>
<!-- Home Page -->
<!-- <div class="container">
  <router-view name="BCogFormTable"></router-view>
  <div class="row">
    <div class="col-2">
          <router-view></router-view>
    </div>
    <div class="col-10">
      <router-view name="BLocationNav"></router-view>
      <router-view name="BKpiCard"></router-view>
      <br/>
      <router-view name="BLineChart"></router-view>
      <br/>
    </div>
  </div>
</div> -->
<!-- End Home Page -->

@endsection
