
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
// Vue.component('my-view', require('./components/view.vue'));

Vue.component('left-component', require('./components/scaleOverview/LeftComponent.vue'));
Vue.component('city-component', require('./components/scaleOverview/CityComponent.vue'));

Vue.component('tab-component', require('./components/scaleOverview/TabsComponent.vue'));
// Vue.component('tabsdata-component', require('./components/scaleOverview/TabsdataComponent.vue'));

Vue.component('highchartsline-component', require('./components/scaleOverview/HighchartsLineComponent.vue'));

Vue.component('XChart', require('./components/Highcharts.vue'));
Vue.component('test', require('./components/test.vue'));

const bus = new Vue()
Vue.prototype.bus = bus

/*// 0. If using a module system (e.g. via vue-cli), import Vue and VueRouter
// and then call `Vue.use(VueRouter)`.

// 1. Define route components.
// These can be imported from other files
const Foo = { template: '<div>foo</div>' }
const Bar = { template: '<div>bar</div>' }

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
  { path: '/foo', component: Foo },
  { path: '/bar', component: Bar }
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
  routes // short for `routes: routes`
})

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
const app = new Vue({
  router
}).$mount('#app')*/




const app = new Vue({
    el: '#app'
});





