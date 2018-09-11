<template>
	<div>
		<b-card nobody header="<b>网络概览</b>">
			<b-list-group flush>
				<span v-show="birdSideBarLoadStatus == 1">Loading</span>
				<b-list-group-item button v-show="birdSideBarLoadStatus == 2"
					v-for='(item, index) in posts'
					:key='item.id'
					:index='item.id'
					:name='item.routertag'
					@click='leftClick'
				>{{ item.Content }}</b-list-group-item>
				<span v-show="birdSideBarLoadStatus == 3">SideBar loaded unsuccessfully!</span>
			</b-list-group>
		</b-card>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				posts: [
					/*{ id: 1, Content: '指标概览', routertag: 'indexoverview' },
					{ id: 2, Content: '规模概览', routertag: 'scaleoverview' }*/
				]
			}
		},
		created() {
            this.$store.dispatch( 'loadBirdSideBarStatus' )
        },
        /**
         * 定义组件的计算属性
         */
        computed: {
            // 获取 city 加载状态
            birdSideBarLoadStatus(){
                this.posts = this.$store.getters.birdSideBar;
                // console.log(this.$store.getters.birdSideBar)
                return this.$store.getters.birdSideBarStatus;
            }
        },
		methods: {
			leftClick: function (event) {
				// this.bus.$emit('leftClick',event.target.name)
				this.bus.$emit('leftClickData', event.target.name)
			}
		}
	}
</script>