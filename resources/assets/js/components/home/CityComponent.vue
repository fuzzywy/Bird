<template>
	<nav id='city' class='nav'>
		<a class="nav-link active" @click='province'>全省</a>
		<a class="nav-link" @click='changzhou'>常州</a>
		<!-- <a class="nav-link" @click='suzhou'>苏州</a>
		<a class="nav-link" @click='nantong'>南通</a>
		<a class="nav-link" @click='wuxi'>无锡</a>
		<a class="nav-link" @click='zhenjiang'>镇江</a>	 -->
	</nav>
</template>

<script>
	export default {
		methods: {
			province: function() {
				alert('province')
			},
			changzhou: function() {
				alert('changzhou')
			}
		}
	}
</script>