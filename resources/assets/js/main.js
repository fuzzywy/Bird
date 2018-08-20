
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router');
Vue.use(VueRouter);

window.BootstrapVue  = require('bootstrap-vue');

window.VueHighcharts = require('vue-highcharts');
window.Highcharts = require('highcharts');

// Vue.config.productionTip = false

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('left-component', require('./components/scaleOverview/LeftComponent.vue'));
Vue.component('city-component', require('./components/scaleOverview/CityComponent.vue'));

Vue.component('tab-component', require('./components/scaleOverview/TabsComponent.vue'));
Vue.component('tabsdata-component', require('./components/scaleOverview/TabsdataComponent.vue'));

Vue.component('highchartsline-component', require('./components/scaleOverview/HighchartsLineComponent.vue'));

Vue.component('XChart', require('./components/Highcharts.vue'));
Vue.component('test', require('./components/test.vue'));

const app = new Vue({
    el: '#app'
});

Vue.component('App', require('./components/App.vue'));
Vue.component('example', require('./components/Example.vue'));
const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  routes: [
    { path: '/example', component: Example }
  ]
})
new Vue(Vue.util.extend({ router }, App)).$mount('#app')






