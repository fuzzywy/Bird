<template>
	<div>
		<b-card no-body v-show="type == 'indexoverview'">
	        <b-tabs pills card>
				<b-tab class='cardclass' :active="isActiveLTE" title='LTE' @click='show("LTE")'>
					<div v-show="bKpiCardStatus == 1" style="text-align: center;"><img src="/public/img/loading.gif">&nbsp;loading...</div>
					<div v-for='post in LTEs' v-show="bKpiCardStatus == 3" style="text-align: center;">{{post}}</div>
					<div class="row" v-show="bKpiCardStatus == 2">
						<div
				            class="col-4 rowclass" 
				            style="text-align: center;" 
				            v-for="post in LTEs"
				            :key="post.id"
				        >
				        	<div class="postclass" @click="button(post.id)" :class="post.color">
					        	<span class="posttype">{{ post.type }}</span>
					            <br />
					            <span class="postdata"><b>{{ post.data }}</b></span>
					            <br />
					            <i :class="post.class"></i>
					            {{ post.tend }}
					        </div>
				        </div>
				    </div>
				</b-tab>
				<b-tab class='cardclass' title='VOLTE' @click='show("VOLTE")'>
					<div v-show="bKpiCardStatus == 1" style="text-align: center;"><!-- <i class="icon-ali-load">loading...</i> --><img src="/public/img/loading.gif">&nbsp;loading...</div>
					<div v-for='post in VOLTEs' v-show="bKpiCardStatus == 3" style="text-align: center;">{{post}}</div>
					<div class="row" v-show="bKpiCardStatus == 2">
						<div
				            class="col-4 rowclass" 
				            style="text-align: center;" 
				            v-for="post in VOLTEs"
				            :key="post.id"
				        >
				        	<div class="postclass" @click="button(post.id)" :class="post.color">
					        	<span class="posttype">{{ post.type }}</span>
					            <br />
					            <span class="postdata"><b>{{ post.data }}</b></span>
					            <br />
					            <i :class="post.class"></i>
					            {{ post.tend }}
					        </div>
				        </div>
					</div>
				</b-tab>
				<b-tab class='cardclass' title='NBIOT' @click='show("NBIOT")'>
					<div v-show="bKpiCardStatus == 1" style="text-align: center;"><img src="/public/img/loading.gif">&nbsp;loading...</div>
					<div v-for='post in NBIOTs' v-show="bKpiCardStatus == 3" style="text-align: center;">{{post}}</div>
					<div class="row" v-show="bKpiCardStatus == 2">
						<div
				            class="col-4 rowclass" 
				            style="text-align: center;" 
				            v-for="post in NBIOTs"
				            :key="post.id"
				        >
				        	<div class="postclass" @click="button(post.id)" :class="post.color">
					        	<span class="posttype">{{ post.type }}</span>
					            <br />
					            <span class="postdata"><b>{{ post.data }}</b></span>
					            <br />
					            <i :class="post.class"></i>
					            {{ post.tend }}
					        </div>
				        </div>
					</div>
				</b-tab>
				<b-tab class='cardclass' title='GSM' @click='show("GSM")'>
					<div v-show="bKpiCardStatus == 1" style="text-align: center;"><img src="/public/img/loading.gif">&nbsp;loading...</div>
					<div v-for='post in GSMs' v-show="bKpiCardStatus == 3" style="text-align: center;">{{post}}</div>
					<div class="row" v-show="bKpiCardStatus == 2">
						<div
				            class="col-4 rowclass" 
				            style="text-align: center;" 
				            v-for="post in GSMs"
				            :key="post.id"
				        >
				        	<div class="postclass" @click="button(post.id)" :class="post.color">
					        	<span class="posttype">{{ post.type }}</span>
					            <br />
					            <span class="postdata"><b>{{ post.data }}</b></span>
					            <br />
					            <i :class="post.class"></i>
					            {{ post.tend }}
					        </div>
				        </div>
					</div>
				</b-tab>
				<!-- <b-card-footer>{{ overviewCn }}-{{city}}</b-card-footer> -->
			</b-tabs>
	    </b-card>

	    <b-card-group deck v-show="type == 'scaleoverview'">
	    	
	    	<!-- <span v-show="bScaleKpiCard == 1">Loading ScaleCard</span>
			<span v-show="bScaleKpiCard == 3">ScaleCard loaded unsuccessfully!</span> -->
	        <b-card header="GSM"
	                header-tag="header"
	               	>
	            <div v-show="bScaleKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in scale' v-show="bScaleKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bScaleKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in scale.GSMs" 
			            :key="post.id"
			            :class="post.col" 
			        >
			        	<div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src= "post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        </div>
				</div>
	        </b-card>
    	</b-card-group>
    	<b-card-group deck v-show="type == 'scaleoverview'">
    		<!-- <div v-show="bScaleKpiCard == 1" style="margin: auto;"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    	<div v-show="bScaleKpiCard == 3" style="margin: auto;">ScaleCard loaded unsuccessfully!</div> -->
    		<b-card header="TDD_LTE"
	                header-tag="header"
	               	>
	            <div v-show="bScaleKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in scale' v-show="bScaleKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bScaleKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in scale.TDDLTEs" 
			            :key="post.id"
			            :class="post.col" 
			        >
				        <div class='scalecard' v-show="post.img == '/public/img/huihua.png'" style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        	<div v-show="post.img == '/public/img/xinhao.png'" style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="posttype" style="color: #000; padding-top: 20px;">{{post.name}}</div>
			        		<div class="postdata" style="color: #000;">{{post.data}}/{{post.max}}</div>
			        		<br />
		        			<b-progress style='width: 80%; margin: auto;' :value="post.data" :max="post.max" variant='warning' show-progress animated></b-progress>
			        	</div>
			        	<!-- <div class='scalecard'>
			        		<img class='floatleft' src="/public/img/huihua.png">
			        		<div style="padding-top:20px">
				        		<span class="postdata" style="color: #fff">{{post.data}}</span>
				        		<br />
				        		<span class="posttype" style="color: #fff">{{post.name}}</span>
				        	</div>
			        	</div> -->
			        </div>
				</div>
	        </b-card>
	    </b-card-group>
	   	<b-card-group deck v-show="type == 'scaleoverview'">
    		<b-card header="FDD_LTE"
	                header-tag="header"
	               	>
	            <div v-show="bScaleKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in scale' v-show="bScaleKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bScaleKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in scale.FDDLTEs" 
			            :key="post.id"
			            :class="post.col" 
			        >
			        	<div class='scalecard' v-show="post.img == '/public/img/huihua.png'" style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        	<div v-show="post.img == '/public/img/xinhao.png'" style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="posttype" style="color: #000; padding-top: 20px;">{{post.name}}</div>
			        		<div class="postdata" style="color: #000;">{{post.data}}/{{post.max}}</div>
			        		<br />
		        			<b-progress style='width: 80%; margin: auto;' :value="post.data" :max="post.max" variant='warning' show-progress animated></b-progress>
			        	</div>
			        	<!-- <div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div> -->
			        </div>
				</div>
	        </b-card>
    	</b-card-group>
    	<b-card-group deck v-show="type == 'scaleoverview'">
    		<b-card header="NBIOT"
	                header-tag="header"
	               	>
	               <!-- :footer="overviewCn + city"
	                footer-tag="footer" -->
	            <div v-show="bScaleKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in scale' v-show="bScaleKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bScaleKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in scale.NBIOTs" 
			            :key="post.id"
			            :class="post.col" 
			        >
			        	<div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        </div>
				</div>
	        </b-card>
    	</b-card-group>


    	<b-card-group deck v-show="type == 'loadoverview'">
    		<!-- <div v-show="bLoadKpiCard == 1" style="margin: auto;"><img src="/public/img/loading.gif">&nbsp;loading...</div>
    		<div v-show="bLoadKpiCard == 3" style="margin: auto;">LoadCard loaded unsuccessfully!</div> -->
    		<!-- <span v-show="bLoadKpiCard == 1">Loading LoadCard</span>
			<span v-show="bLoadKpiCard == 3">LoadCard loaded unsuccessfully!</span> -->
    		<b-card header="TDD_LTE"
	                header-tag="header"
	               	>
	            <div v-show="bLoadKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in load' v-show="bLoadKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bLoadKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in load.TDDLTEs" 
			            :key="post.id"
			            :class="post.col" 
			        >
				        <div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        </div>
				</div>
	        </b-card>
	    </b-card-group>

	    <b-card-group deck v-show="type == 'loadoverview'">
    		<b-card header="FDD_LTE"
	                header-tag="header"
	               	>
	            <div v-show="bLoadKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in load' v-show="bLoadKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bLoadKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in load.FDDLTEs" 
			            :key="post.id"
			            :class="post.col" 
			        >
				        <div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        </div>
				</div>
	        </b-card>
	    </b-card-group>

	    <b-card-group deck v-show="type == 'loadoverview'">
    		<b-card header="NBIOT"
	                header-tag="header"
	               	>
	            <div v-show="bLoadKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in load' v-show="bLoadKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bLoadKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in load.NBIOTs" 
			            :key="post.id"
			            :class="post.col" 
			        >
				        <div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        </div>
				</div>
	        </b-card>
	    </b-card-group>

	    <b-card-group deck v-show="type == 'loadoverview'">
    		<b-card header="GSM"
	                header-tag="header"
	               	>
	            <div v-show="bLoadKpiCard == 1" style="margin: auto; width: fit-content"><img src="/public/img/loading.gif">&nbsp;loading...</div>
	    		<div v-for='post in load' v-show="bLoadKpiCard == 3" style="margin: auto; width: fit-content">{{post}}</div>
	            <div class="row" v-show="bLoadKpiCard == 2">
					<div
			            style="text-align: center;padding-bottom: 15px"
			            v-for="post in load.GSMs" 
			            :key="post.id"
			            :class="post.col" 
			        >
				        <div class='scalecard' style="vertical-align: middle;">
			        		<img class='floatleft' :src="post.img">
			        		<div class="postdata" style="color: #fff; padding-top: 20px;">{{post.data}}</div>
			        		<div class="posttype" style="color: #fff">{{post.name}}</div>
			        	</div>
			        </div>
				</div>
	        </b-card>
	    </b-card-group>
	   <!--  <b-tabs  card v-show="type == 'scaleoverview'">
			<b-tab :active="isActiveLTE" title='LTE' @click='show("LTE")' v-show="bKpiCardStatus == 2">
				<div class="row">
					<div
			            class="col-4" 
			            style="text-align: center;" 
			            v-for="post in LTEs"
			            :key="post.id"
			        >{{ post.type }}
			            <br />
			            <i :class="post.class"></i>
			            {{ post.data }}
			        </div>
			    </div>
			</b-tab>
		</b-tabs> -->
	</div>
</template>
<style>
	/*门限背景色*/
	.red {
		background-color: #dd4b39;
		/*color: #d81e06;*/
		color: white;
	}
	.blue {
		background-color: #00A65A;
		/*color: #1296db;*/
		color: white;
	}
	.red:hover {
		background-color: #BC4031;
	}
	.blue:hover {
		background-color: #008D4D;
	}

	/*箭头背景色*/
	.icon-ali-jiantoushangsheng-blue {
		color: #1296db;
	}
	.icon-ali-jiantouxiajiang-red {
		color: #d81e06;
	}

	/*指标概览*/
	.cardclass {
		padding: 10px 25px 10px 10px 
	}
	.postclass {
		cursor:pointer;
		/*background-color: #DCDCDC;*/
		border-radius: 3px;
		margin-top: 5px;
		margin-left: 0px;
	}
	.postclass:hover {
		/*background-color: #CCCCCC;*/
	}
	.rowclass {
		padding: 0px 0px 0px 15px
	}
	.postdata {
		font-size: 25px
	}
	.posttype {
		font-size: 15px
	}


	/*规模概览*/
	.scalecard {
		background-color: #20a8d8;
		/*width:250px; */
		height: 120px;
		border-radius: 3px;
	}
	.floatleft {
		position: relative;
		padding: 10px 10px;
		vertical-align: middle;
		/*background-color: #20a8d8;*/
		width: 100px;
		height: 100px;
		float: left;
		margin: 10px 5px 5px 10px;
	}
	.scalepostclass {
		/*background-color: #20a8d8;*/
		border-radius: 3px;
		padding: 20px 10px 20px 10px;
		margin-top: 5px;
		margin-left: 0px;
	}
	
</style>
<script>
	// Vue.component('tabsdata-component', require('./TabsdataComponent.vue'));
	export default {
		name: 'tabs',
		data() {
			return {
				isActiveLTE: true,
				// isActiveVOLTE: true,
				//class: 'default',
				city: '全省',
				overview: 'indexoverview',
				overviewCn: '指标概览',
				types: "LTE",
				LTEs: [],
				VOLTEs: [],
				NBIOTs:[],
				GSMs:[],
				data:[],
				scale: [],
				load:[],
				/*counter:75,*/
				/*max:100*/
			}
		},
		computed: {
            // 获取加载状态
            bKpiCardStatus(){
				this.data = this.$store.getters.getbKpiCard
                return this.$store.getters.getbKpiCardStatus;
            },
            type() {
				return this.overview
			},
			bScaleKpiCard() {
				this.scale = this.$store.getters.getbScaleCard
				return this.$store.getters.getbScaleCardStatus;
			},
			bLoadKpiCard() {
				this.load = this.$store.getters.getbLoadKpiCard
				return this.$store.getters.getbLoadKpiCardStatus;
			},
        },
		created() {
			/*this.$store.dispatch( 'loadBKpiCardStatus', {
				type: this.types,
				city: this.city,
				overview: this.overview
			})*/
	  		/*this.bus.$on('leftClick', overview => {
	  			this.overview = overview
	  			if (this.overview == 'indexoverview') {
	  				this.overviewCn = '网络概览';
	  			} else if ( this.overview == 'scaleoverview' ) {
	  				this.overviewCn = '规模概览';
	  			}
	  		})*/
	  		/*this.bus.$on('cityClick', city => {
	  			this.city = city
	  		})*/
	  		this.bus.$on('leftClickData', overview => {
            	this.overview = overview
            	if (this.overview == 'indexoverview') {
	  				this.overviewCn = '指标概览';
	  				this.$store.dispatch( 'loadBKpiCardStatus', {
						type: this.types,
						city: this.city,
						overview: this.overview
					})
	  			} else if ( this.overview == 'scaleoverview' ) {
	  				this.overviewCn = '规模概览';
	  				this.$store.dispatch( 'loadBScaleKpiCard', {
	  					city: this.city,
	  					overview: this.overview
	  				})
	  			} else if ( this.overview == 'loadoverview' ) {
	  				this.overviewCn = '负荷概览';
	  				this.$store.dispatch( 'loadBLoadKpiCard', {
	  					city: this.city,
	  					overview: this.overview
	  				} )
	  			}
	  		})

	  		this.bus.$on('cityClickData', city => {
            	this.city = city
            	if (this.overview == 'indexoverview') {
					this.$store.dispatch( 'loadBKpiCardStatus', {
						type: this.types,
						city: this.city,
						overview: this.overview
					})
            	} else if ( this.overview == 'scaleoverview' ) {
            		this.$store.dispatch( 'loadBScaleKpiCard', {
	  					city: this.city,
	  					overview: this.overview
	  				})
            	} else if ( this.overview == 'loadoverview' ) {
	  				this.$store.dispatch( 'loadBLoadKpiCard', {
	  					city: this.city,
	  					overview: this.overview
	  				} )
	  			}
	  		})
	  	},
	  	watch: {
	  		data: function(val) {
	  			if ( this.types == "LTE" ) {
					var obj = this.LTEs
				} else if( this.types == "VOLTE" ) {
					var obj = this.VOLTEs	
				} else if( this.types == "NBIOT" ) {
					var obj = this.NBIOTs
				} else if( this.types == "GSM" ) {
					var obj = this.GSMs
				}
	  			obj.splice(0, obj.length)
	  			for (var i = val.length - 1; i >= 0; i--) {
            		/*if ( val[i]['limit'] == "icon-ali-jiantoushangsheng-blue" ) {
            			val[i]['color'] = 'blue'
            		}else if ( val[i]['linit'] == "icon-ali-jiantouxiajiang-red" ) {
            			val[i]['color'] = 'red'
            		}*/
            		obj.push(val[i])
            	}
            	// console.log(obj)
	  		}
	  	},
		methods: {
			button: function(data) {
				this.bus.$emit('getTabsId', data)
			},
			show: function(data) {
				this.types = data
				this.bus.$emit('getTabsType', this.types)
				this.bus.$emit('getTabsData', this.types)

				this.bus.$on('leftClick', overview => {
					this.overview = overview
				})

				if (this.overview == 'indexoverview') {
					this.$store.dispatch( 'loadBKpiCardStatus', {
						type: this.types,
						city: this.city,
						overview: this.overview
					})
				}

				/*var params = {
					data: data,
					city: this.city,
					overview: this.overview
				}
				axios.get('getTabs' , {
                    params: params
                })
                .then(function(response) {
                	obj.splice(0, obj.length)
            		for (var i = response.data.length - 1; i >= 0; i--) {
                		obj.push(response.data[i])
                	}
                })
                .catch(function(error) {
                    console.log(error)
                })*/
			}
		}
	}
</script>