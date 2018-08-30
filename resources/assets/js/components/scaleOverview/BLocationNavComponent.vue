<template>
	<b-nav>
		<span v-show="cityLoadStatus == 1">Loading</span>
		<b-nav-item v-show="cityLoadStatus == 2"
			v-for='post in posts'
			:key='post.id'
			@click='cityClick'
		>{{ post.name }}</b-nav-item>
        <span v-show="cityLoadStatus == 3">City loaded unsuccessfully!</span>
	</b-nav>
</template>

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
                this.posts = this.$store.getters.citys;
                return this.$store.getters.cityLoadStatus;
            }
        },
		methods: {
			cityClick: function(event) {
				this.bus.$emit('cityClick', event.target.innerHTML)
				this.bus.$emit('cityClickData', event.target.innerHTML)
			}
		}
	}
</script>