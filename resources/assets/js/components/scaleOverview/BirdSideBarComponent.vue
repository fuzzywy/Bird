<template>
	<div>
		<b-card header="<b>网络概览</b>" @click='switches' :class='state'>
			<b-list-group flush>
				<span v-show="birdSideBarLoadStatus == 1">Loading</span>
				<b-list-group-item button v-show="birdSideBarLoadStatus == 2"
					v-for='(item, index) in posts'
					:key='item.id'
					:index='item.id'
					:name='item.routertag'
					@click='leftClick'
				>{{ item.Content }}
				</b-list-group-item>
				<span v-show="birdSideBarLoadStatus == 3">SideBar loaded unsuccessfully!</span>
			</b-list-group>
		</b-card>
	</div>
</template>

<style>
	/*.card-body > .list-group {
		transition:height 2s;
		-moz-transition:height 2s;*/ /* Firefox 4 */
		/*-webkit-transition:height 2s;*/ /* Safari and Chrome */
		/*-o-transition:height 2s;*/ /* Opera */
	/*}*/

	.card-body-disappear > .card-body {
		/*height: 0px;*/
		display: none;
	}

	.card-body-appear > .card-body {
		display: block;
		/*height: 200px;*/
		
	}
</style>

<script>
	export default {
		data() {
			return {
				posts: [
					/*{ id: 1, Content: '指标概览', routertag: 'indexoverview' },
					{ id: 2, Content: '规模概览', routertag: 'scaleoverview' }*/
				],
				state: 'card-body-appear'
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
				this.state = 'card-body-disappear'
			},
			switches: function() {
				if ( this.state == 'card-body-disappear' ) {
					this.state = 'card-body-appear'
				} else {
					this.state = 'card-body-disappear'
				}
			}
		}
	}
</script>