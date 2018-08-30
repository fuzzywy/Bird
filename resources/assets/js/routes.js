/*
 |-------------------------------------------------------------------------------
 | routes.js
 |-------------------------------------------------------------------------------
 | Contains all of the routes for the application
 */

/**
 * Imports Vue and VueRouter to extend with the routes.
 */
window.Vue = require('vue');
window.VueRouter = require('vue-router');

/**
 * Extends Vue to use Vue Router
 */
Vue.use( VueRouter )
/**
 * Makes a new VueRouter that we will use to run all of the routes for the app.
 */
export default new VueRouter({
    routes: [
        { 
            path: '/',
            name: 'home', 
            components: {
                default: Vue.component('left-component', require('./components/scaleOverview/BirdSideBarComponent.vue')),
                BirdSideBar: Vue.component('left-component', require('./components/scaleOverview/BirdSideBarComponent.vue')),
                BLocationNav: Vue.component('city-component', require('./components/scaleOverview/BLocationNavComponent.vue')),
                BKpiCard: Vue.component('tab-component', require('./components/scaleOverview/BKpiCardComponent.vue')),
                BLineChart: Vue.component('highchartsline-component', require('./components/scaleOverview/BLineChartComponent.vue')),
                a: Vue.component('testVuex', require('./components/testVuex.vue'))
            }
        }
    ]
});
