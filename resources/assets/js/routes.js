window.Vue = require('vue');
import VueRouter from 'vue-router';

Vue.use( VueRouter );

import home from './components/HomeComponent.vue';
import cog from './components/CogComponent.vue';
// import birdSideBar from './components/BirdSideBarComponent.vue';

export default new VueRouter({
  routes: [
    {
      path: '/',
      name: 'home',
      components: {
        HomeComponent: home,//Vue.component( 'HomeComponent', require( './components/HomeComponent.vue' ) )
        // OperatorComponent: Vue.component( 'OperatorComponent', require( './components/OperatorComponent.vue' ) ),
        // LoginComponent: Vue.component( 'LoginComponent', require( './components/LoginComponent.vue' ) )
      }
    }, {
      path: '/cog',
      name: 'cog',
      components: {
        //BirdSideBarComponent: birdSideBar//Vue.component( 'BirdSideBarComponent', require( './components/BirdSideBarComponent.vue' ) )
        CogComponent: cog
      }
    }
  ]
});