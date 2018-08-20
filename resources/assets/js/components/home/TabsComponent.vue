<template>
	<div>
		<ul class="nav nav-tabs">
		  	<li class="nav-item">
		    	<a class="nav-link active" @click='test1'>Active</a>
		  	</li>
		  	<li class="nav-item">
		    	<a class="nav-link" @click='test1'>Link</a>
		  	</li>
		  	<li class="nav-item">
		    	<a class="nav-link" @click='test1'>Link</a>
		  	</li>
		  	<li class="nav-item">
		    	<a class="nav-link disabled" @click='test1'>Disabled</a>
		  	</li>
		</ul>
		<div> {{ tabs }} </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				tabs: 'fddsgdgdf'
			}
		},
		methods: {
			test1: function(event) {
				// console.log(event)
				this.tabs = 'motified'
			}
		}
	}
</script>