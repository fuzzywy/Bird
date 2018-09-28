<template>
	<div>
		<li class="nav-item dropdown">
			<a id="locale" href="#" class="nav-link cog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
				<router-link to="cog" tag="div"><i class="icon-ali-cog"></i></router-link>
			</a>

			<!-- <ul class="dropdown-menu dropdown-menu-right cog-form" aria-labelledby="navbarDropdown">
				<div>
	                <b-form @submit="onSubmit" @reset="onReset" v-if="show">
				      	<b-form-group id="ipInputGroup"
		                    label="ip address:"
		                    label-for="ipInput"
		                    description="We'll never share your IP with anyone else.">
				        	<b-form-input id="ipInput"
		                      	v-model="form.ip"
		                      	required
		                      	placeholder="Enter ip">
				        	</b-form-input>
				      	</b-form-group>
				      	<b-form-group id="portInputGroup"
		                    label="port:"
		                    label-for="portInput"
		                    >
				        	<b-form-input id="portInput"
				        		type="number"
		                      	v-model="form.port"
		                      	required
		                      	placeholder="Enter port">
				        	</b-form-input>
				      	</b-form-group>
				      	<b-form-group id="databaseInputGroup"
		                    label="database:"
		                    label-for="databaseInput"
		                    >
				        	<b-form-input id="databaseInput"
		                      	v-model="form.database"
		                      	required
		                      	placeholder="Enter database">
				        	</b-form-input>
				      	</b-form-group>
				      	<b-form-group id="userInputGroup"
		                    label="user:"
		                    label-for="userInput"
		                    >
				        	<b-form-input id="userInput"
		                      	v-model="form.user"
		                      	required
		                      	placeholder="Enter user">
				        	</b-form-input>
				      	</b-form-group>
				      	<b-form-group id="pwdInputGroup"
		                    label="password:"
		                    label-for="pwdInput"
		                    >
				        	<b-form-input id="pwdInput"
				        		type="password"
		                      	v-model="form.pwd"
		                      	required
		                      	placeholder="Enter password">
				        	</b-form-input>
				      	</b-form-group>
				      	<b-form-group id="typeInputGroup"
		                    label="Type:"
		                    label-for="typeInput">
				        	<b-form-select id="typeInput"
		                      	:options="types"
		                      	required
		                      	v-model="form.type">
				        	</b-form-select>
				      	</b-form-group>
				      	<b-form-group id="checkGroup">
					        <b-form-checkbox-group v-model="form.checked" id="Checks">
					          	<b-form-checkbox value="check">Check me out</b-form-checkbox>
					        </b-form-checkbox-group>
				      	</b-form-group>
				      	<b-button type="submit" variant="primary">Submit</b-button>
				      	<b-button type="reset" variant="danger">Reset</b-button>
				    </b-form>
				</div>
            </ul> -->
            <!-- <pre class="mt-3">{{ text }}</pre> -->
		</li>
	</div>
  
</template>

<style>
	.cog {
		padding-top: 5px;
		padding-left: 0px;
		padding-bottom: 0px;
		padding-right: 0px;
	}

	.cog-form {
		width: 800px;
	}

	.cog-form > div {
		padding: 8px 8px 8px 8px;
	}

</style>

<script>
	export default {
		data () {
		    return {
		      	form: {
		        	ip: '',
		        	port: '',
		        	database: '',
		        	user: '',
		        	pwd: '',
		        	type: null,
		        	checked: []
		      	},
		      	types: [
		        	{ text: 'Select One', value: null },
		        	'LTE', 'GSM'
		      	],
		      	show: true,
		      	shows: true
		    }
		},
	  	methods: {
	    	onSubmit (evt) {
	      		evt.preventDefault();
	      		/*if ( this.form.checked != 'checked' ) {
	      			alert('请检查勾选框')
	      			return;
	      		}*/
	      		this.$store.dispatch('uploadCog', {
	      			ip: this.form.ip,
	      			port: this.form.port,
	      			database: this.form.database,
	      			user: this.form.user,
	      			pwd: this.form.pwd,
	      			type: this.form.type
	      		})
	      		alert('上传成功')
	      		// alert(this.form.checked)
	      		// alert(JSON.stringify(this.form));
	    	},
		    onReset (evt) {
		      	evt.preventDefault();
		      	/* Reset our form values */
		      	this.form.ip = '';
		      	this.form.port = '';
		      	this.form.database = '';
		      	this.form.user = '';
		      	this.form.pwd = '';
		      	this.form.type = null;
		      	this.form.checked = [];
		      	/* Trick to reset/clear native browser form validation state */
		      	this.show = false;
	      		this.$nextTick(() => { this.show = true });
	    	}
	 	}
	}
</script>