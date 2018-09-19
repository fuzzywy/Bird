<template>
	<div>
		<!-- <span v-show="cityLoadStatus == 1">Loading</span> -->
		<div v-show="cityLoadStatus == 1"><img src="/public/img/loading.gif">&nbsp;loading...</div>
		<b-button-group>
			<b-button v-show="cityLoadStatus == 2"
				v-for='post in posts'
				:key='post.id'
				@click='cityClick' variant="outline-primary">{{ post.name }}
			</b-button>
		</b-button-group>
		<div v-for='post in posts' v-show="cityLoadStatus == 3">{{ post }}</div>
		<!-- <span v-for='post in posts' v-show="cityLoadStatus == 3" style="text-align: center;">{{ post }}City loaded unsuccessfully!</span> -->
	</div>
	<!-- <b-nav>
		<span v-show="cityLoadStatus == 1">Loading</span>
		<b-nav-item v-show="cityLoadStatus == 2"
			v-for='post in posts'
			:key='post.id'
			@click='cityClick'
		>{{ post.name }}</b-nav-item>
        <span v-show="cityLoadStatus == 3">City loaded unsuccessfully!</span>
	</b-nav> -->
</template>

<style>
	.btn-outline-primary {
		border-color: aliceblue;
	}
</style>

<script>
	export default {
		data() {
			return {
				overview: 'indexoverview',
				posts: [
					/*{ id: 0, name: '全省' },
					{ id: 1, name: '常州' },
					{ id: 2, name: '苏州' },
					{ id: 3, name: '无锡' },
					{ id: 4, name: '南通' },
					{ id: 5, name: '镇江' }*/
				],
			}
		},
	  	created() {
            this.$store.dispatch( 'loadCitys' )
        },
        /**
         * 定义组件的计算属性
         */
        computed: {
            // 获取 city 加载状态
            cityLoadStatus(){
            	// console.log(this.$store.getters.citys)
            	// console.log(this.$store.getters.cityLoadStatus)
                this.posts = this.$store.getters.citys;
                return this.$store.getters.cityLoadStatus;
            }
        },
		methods: {
			cityClick: function(event) {
				// this.bus.$emit('cityClick', event.target.innerHTML)
				this.bus.$emit('cityClickData', event.target.innerHTML)
			}
		}
	}
</script>