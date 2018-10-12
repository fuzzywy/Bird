<template>
	<div style="background-color: white; padding: 15px 15px 15px 15px;">
		<b-container fluid>
	    <!-- User Interface controls -->
	    <b-row>
	      <b-col md="6" class="my-1">
	        <b-form-group horizontal label="Filter" class="mb-0">
	          <b-input-group>
	            <b-form-input v-model="filter" placeholder="Type to Search" />
	            <b-input-group-append>
	              <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
	            </b-input-group-append>
	          </b-input-group>
	        </b-form-group>
	      </b-col>
	      <b-col md="6" class="my-1">
	        <b-form-group horizontal label="Sort" class="mb-0">
	          <b-input-group>
	            <b-form-select v-model="sortBy" :options="sortOptions">
	              <option slot="first" :value="null">-- none --</option>
	            </b-form-select>
	            <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
	              <option :value="false">Asc</option>
	              <option :value="true">Desc</option>
	            </b-form-select>
	          </b-input-group>
	        </b-form-group>
	      </b-col>
	      <!-- <b-col md="6" class="my-1">
	        <b-form-group horizontal label="Sort direction" class="mb-0">
	          <b-input-group>
	            <b-form-select v-model="sortDirection" slot="append">
	              <option value="asc">Asc</option>
	              <option value="desc">Desc</option>
	              <option value="last">Last</option>
	            </b-form-select>
	          </b-input-group>
	        </b-form-group>
	      </b-col> -->
	      <b-col md="6" class="my-1">
	      	<b-form-group horizontal label="Add ip address" class="mb-0">
	          <b-button size="sm" @click.stop="addColumn">
		        Add
		      </b-button>
	        </b-form-group>
	      </b-col>
	      <b-col md="6" class="my-1">
	        <b-form-group horizontal label="Per page" class="mb-0">
	          <b-form-select :options="pageOptions" v-model="perPage" />
	        </b-form-group>
	      </b-col>
	    </b-row>

	    <!-- Main table element -->
	    <b-table striped 
	    		 show-empty
	             stacked="md"
	             :items="items"
	             :fields="fields"
	             :current-page="currentPage"
	             :per-page="perPage"
	             :filter="filter"
	             :sort-by.sync="sortBy"
	             :sort-desc.sync="sortDesc"
	             :sort-direction="sortDirection"
	             :fixed="fixed"
	             @filtered="onFiltered"
	             v-show="showCog==2"
	    >
	      <!-- <template slot="name" slot-scope="row">{{row.value.first}} {{row.value.last}}</template>
	      <template slot="isActive" slot-scope="row">{{row.value?'Yes :)':'No :('}}</template> -->
	      <template slot="actions" slot-scope="row">
	        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
	        <b-button size="sm" @click.stop="del(row.item, row.index, $event.target)" class="mr-1">
	          Delete
	        </b-button>
	        <!-- <b-button size="sm" @click.stop="modify(row.item, row.index, $event.target)" class="mr-1">
	          modify
	        </b-button> -->
	        <!-- <b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">
	          Info modal
	        </b-button> -->
	        <b-button size="sm" @click.stop="row.toggleDetails" @click.top="modifyData(row.item, row.index, $event.target)">
	          {{ row.detailsShowing ? 'Hide' : 'Modify' }} Details
	        </b-button>
	      </template>
	      <template slot="row-details" slot-scope="row">
	        <!-- <b-card>
	          <ul>
	            <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
	          </ul>
	        </b-card> -->
	        <!-- <div v-for="(value, key) in row.item" :key="key">
	        	<b-input-group :prepend="key">
	        		<b-form-input :value="value"></b-form-input>
	        	</b-input-group>
	        </div> -->
	        <b-card>
	        	<b-row class="mb-2" v-for="(value, key) in row.item" :key="key" v-if="key!== '_showDetails' && key!== 'id'">
	          		<b-col sm="4" class="text-sm-right"><b>{{ key }}:</b></b-col>
	          		<b-col md="5">
	          			<b-form-input v-model="modify[key]" v-if="key !== 'type'"/>
	          			<b-form-input placeholder="LTE or GSM" v-model="modify[key]" v-if="key === 'type'"/>
	          			<!-- <b-form-group id="typeInputGroup"
		                    label-for="typeInput">
				        	<b-form-select id="typeInput"
		                      	:options="types"
		                      	required
		                      	v-model="type"
		                      	v-if="key === 'type'">
				        	</b-form-select>
				      	</b-form-group> -->
	          		</b-col>
	          		<!-- <b-col md="5"><b-form-input :value='modify[key]'/></b-col> -->
	        	</b-row>
	        	<div style="text-align: center;">
	        		<b-button size="sm" @click="modifyOK">modify</b-button>
	        		<!-- <b-button size="sm">cancel</b-button> -->
	        	</div>
	      	</b-card>
	      </template>
	    </b-table>

	    <b-row>
	      <b-col md="6" class="my-1">
	        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
	      </b-col>
	    </b-row>

	    <!-- Info modal -->
	    <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
	      <pre>{{ modalInfo.content }}</pre>
	    </b-modal>

	    <b-modal id="confirm" :title="confirm.title" @ok="handleOk" @hide="resetModal">
	    	<pre> {{ confirm.content }} </pre>
	    </b-modal>

	    <b-modal id="confirmModify" :title="confirmModify.title" @ok="updateok">
	    	<pre> {{ confirmModify.content }} </pre>
	    </b-modal>

	  </b-container>
	</div>
</template>

<style>
	/*.test{
		background-color: red;
	}*/
	.line-limit-length {
		max-width: 200px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;

	}
</style>

<script>
	const items = [
	  /*{ isActive: true, age: 40, name: { first: 'Dickerson', last: 'Macdonald' } },
	  { isActive: false, age: 21, name: { first: 'Larsen', last: 'Shaw' } },
	  {
	    isActive: false,
	    age: 9,
	    name: { first: 'Mini', last: 'Navarro' },
	    _rowVariant: 'success'
	  },
	  { isActive: false, age: 89, name: { first: 'Geneva', last: 'Wilson' } },
	  { isActive: true, age: 38, name: { first: 'Jami', last: 'Carney' } },
	  { isActive: false, age: 27, name: { first: 'Essie', last: 'Dunlap' } },
	  { isActive: true, age: 40, name: { first: 'Thor', last: 'Macdonald' } },
	  {
	    isActive: true,
	    age: 87,
	    name: { first: 'Larsen', last: 'Shaw' },
	    _cellVariants: { age: 'danger', isActive: 'warning' }
	  },
	  { isActive: false, age: 26, name: { first: 'Mitzi', last: 'Navarro' } },
	  { isActive: false, age: 22, name: { first: 'Genevieve', last: 'Wilson' } },
	  { isActive: true, age: 38, name: { first: 'John', last: 'Carney' } },
	  { isActive: false, age: 29, name: { first: 'Dickqqqqq', last: 'Dunlap' } }*/
	  /*{ ip: '192.168.0.1', port: 3306, database: 't1', user: 'root', pwd: 'mongs', type: 'LTE' },
	  { isActive:false, ip: '192.168.0.1', port: 33060, database: 't2', user: 'root', pwd: 'mongs', type: 'GSM' },
	  { isActive:false, ip: '192.168.0.1', port: 3306, database: 't1', user: 'root', pwd: 'mongs', type: 'LTE' },
	  { isActive:false, ip: '192.168.0.1', port: 33060, database: 't2', user: 'root', pwd: 'mongs', type: 'GSM' },
	  { isActive:false, ip: '192.168.0.1', port: 3306, database: 't1', user: 'root', pwd: 'mongs', type: 'LTE' },
	  { isActive:false, ip: '192.168.0.1', port: 33060, database: 't2', user: 'root', pwd: 'mongs', type: 'GSM' },
	  { isActive:false, ip: '192.168.0.1', port: 3306, database: 't1', user: 'root', pwd: 'mongs', type: 'LTE' },
	  { isActive:false, ip: '192.168.0.1', port: 33060, database: 't2', user: 'root', pwd: 'mongs', type: 'GSM' },*/
	]

	export default {
		data () {
		    return {
		      items: [],
		      fields: [
		      	{ key: 'type', label: 'Type', sortable: true, class: 'text-center' },
		      	{ key: 'connname', label: 'ConnName', sortable: true, sortDirection: 'desc' },
		      	{ key: 'city', label: 'city', sortable: true, sortDirection: 'desc' },
		      	{ key: 'ip', label: 'IP address', sortable: true, sortDirection: 'desc' },
		      	{ key: 'port', label: 'Port', sortable: true, class: 'text-center' },
		      	{ key: 'database', label: 'Database' },
		      	{ key: 'user', label: 'User' },
		      	{ key: 'pwd', label: 'Password' },
		      	{ key: 'subnetwork', label: 'subNetwork', class: 'line-limit-length' },
		      	{ key: 'subnetworkfdd', label: 'subNetworkFdd', class: 'line-limit-length' },
		      	{ key: 'subnetworknbiot', label: 'subNetworkNbiot', class: 'line-limit-length' },
		        { key: 'actions', label: 'Actions' }
		        /*{ key: 'name', label: 'Person Full name', sortable: true, sortDirection: 'desc' },
		        { key: 'age', label: 'Person age', sortable: true, 'class': 'text-center' },
		        { key: 'isActive', label: 'is Active' },
		        { key: 'actions', label: 'Actions' }*/
		      ],
		      fixed: false,
		      currentPage: 1,
		      perPage: 5,
		      totalRows: items.length,
		      pageOptions: [ 5, 10, 15 ],
		      sortBy: null,
		      sortDesc: false,
		      sortDirection: 'asc',
		      filter: null,
		      modalInfo: { title: '', content: '' },
		      confirm: { title: '', content: '' },
		      confirmModify: { title: '', content: '' },
		      delete: [],
		      // newItems: [],
		      modify: []/*,
		      type: null,
		      types: [
	        	{ text: 'Select One', value: null },
	        	'LTE', 'GSM'
	      	  ]*/
		    }
		  },
		  computed: {
		    sortOptions () {
		      // Create an options list from our fields
		      return this.fields
		        .filter(f => f.sortable)
		        .map(f => { return { text: f.label, value: f.key } })
		    },
		    showCog () {
		    	this.items = this.$store.getters.getShowCog
		    	return this.$store.getters.getShowCogStatus;
		    }
		  },
		  created() {
		  	this.$store.dispatch('showCog')
		  },
		  methods: {
		  	addColumn () {
		  		// this.$store.dispatch('showCog')
		  		this.filter = null
		  		this.currentPage = 1
		  		this.modify = []
		  		this.modify.push({ id: '-1', _showDetails: true, ip: '', connname: '', city: '', port: '', database: '', user: '', pwd: '', type: '', subnetwork: '', subnetworkfdd: '', subnetworknbiot: '' })
		  		//限制add数量只能是一个
		  		for (var i = this.items.length - 1; i >= 0; i--) {
		  			if( this.items[i].id == -1 ) {
		  				this.items[i]._showDetails = true
			  			return;
			  		}
		  		}
		  		for (var i = this.items.length - 1; i >= 0; i--) {
		  			this.modify.push(this.items[i])
		  		}
		  		this.items = this.modify
		  		
		  	},
		  	modifyData ( item, index, button ) {
		  		this.modify = item
		  	},
		  	modifyOK () {
		  		this.$store.dispatch('uploadCog', {
	      			ip: this.modify.ip,
	      			connname: this.modify.connname,
	      			city: this.modify.city,
	      			port: this.modify.port,
	      			database: this.modify.database,
	      			user: this.modify.user,
	      			pwd: this.modify.pwd,
	      			type: this.modify.type,
	      			subnetwork: this.modify.subnetwork,
	      			subnetworkfdd: this.modify.subnetworkfdd,
	      			subnetworknbiot: this.modify.subnetworknbiot
	      		})
	      		this.confirmModify.title = ''
		  		this.confirmModify.content = '修改成功'
		  		this.$root.$emit('bv::show::modal', 'confirmModify', '#confirmModify')
		  		// this.$store.dispatch('showCog')
	      		// this.$store.dispatch('showCog')
		  	},
		  	del ( item, index, button ) {
		  		this.delete = item
		  		this.confirm.title = '确认删除'
		  		this.confirm.content = JSON.stringify(item, null, 2)
		  		this.$root.$emit('bv::show::modal', 'confirm', button)
		  	},
		  	handleOk ( evt ) {
		  		// evt.preventDefault()
		  		this.$store.dispatch('deleteCog', {
	      			ip: this.delete.ip,
	      			connname: this.delete.connname,
	      			city: this.delete.city,
	      			port: this.delete.port,
	      			database: this.delete.database,
	      			user: this.delete.user,
	      			pwd: this.delete.pwd,
	      			type: this.delete.type,
	      			subnetwork: this.delete.subnetwork,
	      			subnetworkfdd: this.delete.subnetworkfdd,
	      			subnetworknbiot: this.delete.subnetworknbiot
	      		})
		  		// this.$store.dispatch('showCog')
		  		this.modify = []
		  		for (var i = this.items.length - 1; i >= 0; i--) {
		  			if( this.items[i].id != this.delete.id ) {
		  				this.modify.push(this.items[i])
		  			}
		  		}
		  		this.items = this.modify.sort(function(a, b) {
		  			return a.id - b.id
		  		})
		  	},
		  	//更新table
		  	updateok () {
		  		this.$store.dispatch('showCog')
		  		this.modify = []
		  		// this.newItems = []
		  	},
		    info (item, index, button) {
		    	/*this.$store.dispatch('uploadCog', {
	      			ip: this.items.ip,
	      			port: this.items.port,
	      			database: this.items.database,
	      			user: this.items.user,
	      			pwd: this.items.pwd,
	      			type: this.items.type
	      		})*/
	      		// console.log(this.$store.commit('showCog'))
	      		// this.$store.dispatch('showCog')
	      			
	      		
		      this.modalInfo.title = `Row index: ${index}`
		      this.modalInfo.content = JSON.stringify(item, null, 2)
		      this.$root.$emit('bv::show::modal', 'modalInfo', button)
		    },
		    resetModal () {
		      this.modalInfo.title = ''
		      this.modalInfo.content = ''
		    },
		    onFiltered (filteredItems) {
		      // Trigger pagination to update the number of buttons/pages due to filtering
		      this.totalRows = filteredItems.length
		      this.currentPage = 1
		    }
		  }
	}
</script>