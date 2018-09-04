<template>
	<div>
		<b-card no-body v-show="type == 'indexoverview'">
	        <b-tabs card>
	        	<span v-show="bKpiCardStatus == 1">Loading Card</span>
				<span v-show="bKpiCardStatus == 3">Card loaded unsuccessfully!</span>
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
				<b-tab title='VOLTE' @click='show("VOLTE")' v-show="bKpiCardStatus == 2">
					<div class="row">
						<div
				            class="col-4" 
				            style="text-align: center;" 
				            v-for="post in VOLTEs"
				            :key="post.id"
				        >{{ post.type }}
				            <br />
				            <i :class="post.class"></i>
				            {{ post.data }}
				        </div>
					</div>
				</b-tab>
				<b-tab title='NBIOT' @click='show("NBIOT")' v-show="bKpiCardStatus == 2">
					<div class="row">
						<div
				            class="col-4" 
				            style="text-align: center;" 
				            v-for="post in NBIOTs"
				            :key="post.id"
				        >{{ post.type }}
				            <br />
				            <i :class="post.class"></i>
				            {{ post.data }}
				        </div>
					</div>
				</b-tab>
				<b-tab title='GSM' @click='show("GSM")' v-show="bKpiCardStatus == 2">
					<div class="row">
						<div
				            class="col-4" 
				            style="text-align: center;" 
				            v-for="post in GSMs"
				            :key="post.id"
				        >{{ post.type }}
				            <br />
				            <i :class="post.class"></i>
				            {{ post.data }}
				        </div>
					</div>
				</b-tab>
				<b-card-footer>网络概览-{{ overviewCn }}-{{city}}</b-card-footer>
			</b-tabs>
	    </b-card>

	    <b-card-group deck v-show="type == 'scaleoverview'">
	    	<span v-show="bScaleKpiCard == 1">Loading ScaleCard</span>
			<span v-show="bScaleKpiCard == 3">ScaleCard loaded unsuccessfully!</span>
	        <b-card header="GSM"
	                header-tag="header"
	                :footer="overviewCn + city"
	                footer-tag="footer"
	                v-show="bScaleKpiCard == 2"
	               	>
	            <div class="row">
					<div
			            style="text-align: center;"
			            v-for="post in scale.GSMs" 
			            :key="post.id"
			            :class="post.col" 
			        >{{post.name}}<br />{{post.data}}
			        </div>
				</div>
	        </b-card>
    	</b-card-group>
    	<b-card-group deck v-show="type == 'scaleoverview'">
    		<span v-show="bScaleKpiCard == 1">Loading ScaleCard</span>
			<span v-show="bScaleKpiCard == 3">ScaleCard loaded unsuccessfully!</span>
    		<b-card header="TDD_LTE"
	                header-tag="header"
	                :footer="overviewCn + city"
	                footer-tag="footer"
	                v-show="bScaleKpiCard == 2"
	               	>
	            <div class="row">
					<div
			            style="text-align: center;"
			            v-for="post in scale.TDDLTEs" 
			            :key="post.id"
			            :class="post.col" 
			        >{{post.name}}<br />{{post.data}}
			        </div>
				</div>
	        </b-card>
	    </b-card-group>
	   	<b-card-group deck v-show="type == 'scaleoverview'">
    		<span v-show="bScaleKpiCard == 1">Loading ScaleCard</span>
			<span v-show="bScaleKpiCard == 3">ScaleCard loaded unsuccessfully!</span>
    		<b-card header="TDD_LTE"
	                header-tag="header"
	                :footer="overviewCn + city"
	                footer-tag="footer"
	                v-show="bScaleKpiCard == 2"
	               	>
	            <div class="row">
					<div
			            style="text-align: center;"
			            v-for="post in scale.FDDLTEs" 
			            :key="post.id"
			            :class="post.col" 
			        >{{post.name}}<br />{{post.data}}
			        </div>
				</div>
	        </b-card>
    	</b-card-group>
    	<b-card-group deck v-show="type == 'scaleoverview'">
    		<span v-show="bScaleKpiCard == 1">Loading ScaleCard</span>
			<span v-show="bScaleKpiCard == 3">ScaleCard loaded unsuccessfully!</span>
    		<b-card header="NBIOT"
	                header-tag="header"
	                :footer="overviewCn + city"
	                footer-tag="footer"
	                v-show="bScaleKpiCard == 2"
	               	>
	            <div class="row">
					<div
			            style="text-align: center;"
			            v-for="post in scale.NBIOTs" 
			            :key="post.id"
			            :class="post.col" 
			        >{{post.name}}<br />{{post.data}}
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

<script>
	// Vue.component('tabsdata-component', require('./TabsdataComponent.vue'));
	export default {
		name: 'tabs',
		data() {
			return {
				isActiveLTE: true,
				// isActiveVOLTE: true,
				city: '全省',
				overview: 'indexoverview',
				overviewCn: '指标概览',
				types: "LTE",
				LTEs: [],
				VOLTEs: [],
				NBIOTs:[],
				GSMs:[],
				data:[],
				scale: []
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
			}
        },
		created() {
			this.$store.dispatch( 'loadBKpiCardStatus', {
				type: this.types,
				city: this.city,
				overview: this.overview
			})
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
	  				this.overviewCn = '网络概览';
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
            		obj.push(val[i])
            	}
	  		}
	  	},
		methods: {
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