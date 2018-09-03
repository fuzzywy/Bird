<template>
	<b-card no-body>
        <b-tabs card>
			<b-tab :active="isActiveLTE" title='LTE' @click='show("LTE")'>
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
			<b-tab title='VOLTE' @click='show("VOLTE")'>
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
			<b-tab title='NBIOT' @click='show("NBIOT")'>
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
			<b-tab title='GSM' @click='show("GSM")'>
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
				GSMs:[]
			}
		},
		created() {
	  		this.bus.$on('leftClick', overview => {
	  			this.overview = overview
	  			if (this.overview == 'indexoverview') {
	  				this.overviewCn = '网络概览';
	  			} else if ( this.overview == 'scaleoverview' ) {
	  				this.overviewCn = '规模概览';
	  			}
	  		})
	  		this.bus.$on('cityClick', city => {
	  			this.city = city
	  		})
	  		this.bus.$on('leftClickData', overview => {
            	if ( this.types == "LTE" ) {
					var obj = this.LTEs
				} else if( this.types == "VOLTE" ) {
					var obj = this.VOLTEs	
				} else if( this.types == "NBIOT" ) {
					var obj = this.NBIOTs
				} else if( this.types == "GSM" ) {
					var obj = this.GSMs
				}
            	this.overview = overview
            	axios.get('getTabs' , {
                    params: {
                    	data: this.types,
						city: this.city,
						overview: this.overview
                    }
                })
                .then(function(response) {
                	obj.splice(0, obj.length)
                	for (var i = response.data.length - 1; i >= 0; i--) {
                		obj.push(response.data[i])
                	}
                })
                .catch(function(error) {
                    console.log(error)
                })
	  		})
	  		this.bus.$on('cityClickData', city => {
            	if ( this.types == "LTE" ) {
					var obj = this.LTEs
				} else if( this.types == "VOLTE" ) {
					var obj = this.VOLTEs	
				} else if( this.types == "NBIOT" ) {
					var obj = this.NBIOTs
				} else if( this.types == "GSM" ) {
					var obj = this.GSMs
				}
            	this.city = city
            	axios.get('getTabs', {
                    params: {
                    	data: this.types,
						city: city,
						overview: this.overview
                    }
                })
                .then(function(response) {
                	obj.splice(0, obj.length)
                	for (var i = response.data.length - 1; i >= 0; i--) {
                		obj.push(response.data[i])
                	}
                })
                .catch(function(error) {
                    console.log(error)
                })
	  		})
	  	},
		methods: {
			show: function(data) {
				if ( data == "LTE" ) {
					var obj = this.LTEs
				} else if( data == "VOLTE" ) {
					var obj = this.VOLTEs	
				} else if( data == "NBIOT" ) {
					var obj = this.NBIOTs
				} else if( data == "GSM" ) {
					var obj = this.GSMs
				}
				this.types = data
				this.bus.$emit('getTabsType', this.types)
				this.bus.$emit('getTabsData', this.types)

				this.bus.$on('leftClick', overview => {
					this.overview = overview
				})
				var params = {
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
                })
			}
		}
	}
</script>