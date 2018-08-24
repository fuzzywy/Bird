<template>
	<b-nav>
		<b-nav-item
			v-for='post in posts'
			:key='post.id'
			@click='cityClick'
		>{{ post.name }}</b-nav-item>
	</b-nav>
</template>

<script>
	export default {
		data() {
			return {
				overview: 'indexoverview',
				posts: [
					{ id: 0, name: '全省' },
					{ id: 1, name: '常州' },
					{ id: 2, name: '苏州' },
					{ id: 3, name: '无锡' },
					{ id: 4, name: '南通' },
					{ id: 5, name: '镇江' }
				],
			}
		},
		/*mounted: function() {
	        this.bus.$on('click', function (leftClick) {
	            alert(leftClick)
	        })
    	},*/
	  	/*created() {
	  		// this.bus.$on('leftClick', overview => {
	  		// 	this.overview = overview
	  			
	  		// })
	  	},*/
		methods: {
			cityClick: function(event) {
				this.bus.$emit('cityClick', event.target.innerHTML)
				this.bus.$emit('cityClickData', event.target.innerHTML)
			}
		}
	}
</script>