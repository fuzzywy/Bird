window._ = require('lodash');
try {
    window.$ = window.jQuery = require('jquery');
    // require('foundation-sites');
} catch (e) {}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');
import router from './routes.js';
// window.routes = require('./route.js');
// window.VueRouter = require('vue-router');
// Vue.use(VueRouter);

window.BootstrapVue  = require('bootstrap-vue');

window.VueHighcharts = require('vue-highcharts');
window.Highcharts = require('highcharts');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// Vue.component('example-component', require('./components/ExampleComponent.vue'));
// Vue.component('my-view', require('./components/view.vue'));

// Vue.component('left-component', require('./components/scaleOverview/LeftComponent.vue'));
// Vue.component('city-component', require('./components/scaleOverview/CityComponent.vue'));

// Vue.component('tab-component', require('./components/scaleOverview/TabsComponent.vue'));
// Vue.component('tabsdata-component', require('./components/scaleOverview/TabsdataComponent.vue'));

// Vue.component('highchartsline-component', require('./components/scaleOverview/HighchartsLineComponent.vue'));

// Vue.component('XChart', require('./components/Highcharts.vue'));
// Vue.component('test', require('./components/test.vue'));

//data transform
const bus = new Vue()
Vue.prototype.bus = bus

/*const app = new Vue({
    el: '#app'
});*/

// Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
const app = new Vue({
  router
}).$mount('#app')
