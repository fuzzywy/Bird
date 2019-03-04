window.Vue = require('vue');
import VueRouter from 'vue-router';

Vue.use( VueRouter );

export default new VueRouter({
	routes: [
		{
			path: '/',
			name: 'home',
			components: {
                HomeComponent: Vue.component( 'HomeComponent', require( './components/HomeComponent.vue' ) ),
                OperatorComponent: Vue.component( 'OperatorComponent', require( './components/OperatorComponent.vue' ) ),
            	LoginComponent: Vue.component( 'LoginComponent', require( './components/LoginComponent.vue' ) )
            }
		}
	]
});