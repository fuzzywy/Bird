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
			<b-card-footer>网络概览-{{ overview }}</b-card-footer>

		</b-tabs>
    </b-card>
</template>

<script>
	Vue.component('tabsdata-component', require('./TabsdataComponent.vue'));
	export default {
		name: 'tabs',
		data() {
			return {
				isActiveLTE: true,
				// isActiveVOLTE: true,
				city: '全省',
				overview: 'indexoverview',
				types: "LTE",
				LTEs: [
					// { id: 0, type: '无线接通率', data: '93%', class: 'icon-ali-jiantou_xiangxia' },
     //                { id: 1, type: '无线掉线率', data: '1%', class: 'icon-ali-jianhao' },
     //                { id: 2, type: '接入成功率', data: '99%', class: 'icon-ali-jiantou_xiangshang' }
				],
				VOLTEs: [
					// { id: 0, type: '无线接通率', data: '93%', class: 'icon-ali-jiantou_xiangxia' },
     //                { id: 1, type: '无线掉线率', data: '1%', class: 'icon-ali-jianhao' },
     //                { id: 2, type: '接入成功率', data: '99%', class: 'icon-ali-jiantou_xiangshang' }
				]
			}
		},
		created() {
	  		this.bus.$on('leftClick', overview => {
	  			this.overview = overview
	  		})
	  		this.bus.$on('cityClick', city => {
	  			this.city = city
	  		})
	  		this.bus.$on('leftClickData', overview => {
            	if ( this.types == "LTE" ) {
					var obj = this.LTEs
				} else if( this.types == "VOLTE" ) {
					var obj = this.VOLTEs	
				}
	  			// obj.splice(0, obj.length)
            	this.overview = overview
            	axios.get('getTabs'+this.types , {
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
				}
	  			// obj.splice(0, obj.length)
            	this.city = city
            	axios.get('getTabs'+this.types , {
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
				}
				this.types = data
				this.bus.$emit('getTabsType', this.types)
				this.bus.$emit('getTabsData', this.types)
				// obj.splice(0, obj.length)

				this.bus.$on('leftClick', overview => {
					this.overview = overview
				})
				var params = {
					data: data,
					city: this.city,
					overview: this.overview
				}
				axios.get('getTabs'+data , {
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