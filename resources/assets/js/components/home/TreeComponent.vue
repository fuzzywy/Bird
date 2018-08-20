<template>
	<nav id='overview' class="navbar navbar-light bg-light">
        <nav class="nav nav-pills flex-column">
            <a id='network-overview' class="nav-link" @click="indexOverview">网络概览</a>
            <nav class="nav nav-pills flex-column">
                <a id='index-overview' class="nav-link ml-3 my-1" @click="indexOverview">指标概览</a>
	    		<a id='scale-overview' class="nav-link ml-3 my-1" @click="scaleOverview">规模概览</a>
            </nav>
        </nav>
    </nav>
</template>

<script>
	export default {
		//name: 'overview',
		data() {
			return {
				msg: 'yes'
			}
		},
		methods: {
			indexOverview: function() {
				alert('success')
			},
			scaleOverview: function() {
				alert('scale')
			}	
		}
	}
</script>