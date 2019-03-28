@extends('layouts.app')

@section('content')
<div class="container">
	<v-app>
		<router-view name="HomeComponent"></router-view>
		<router-view name="CogComponent"></router-view>
	    <!-- <home-component></home-component> -->
	</v-app>
</div>
@endsection

<!-- @section('operator')
    <operator-component></operator-component>
@endsection -->


<!-- @section('contents')
    <login-component></login-component>
@endsection -->