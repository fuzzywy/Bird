window._ = require('lodash');
window.axios = require('axios');
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');
import Vuetify from 'vuetify';
// Vue.use(Vuetify);
// import colors from 'vuetify/es5/util/colors';
Vue.use(Vuetify, {
  iconfont: 'ali',
  icons: {
    'configuration': 'icon-ali-cog'
  }
});
// Vue.use(Vuetify, {
//   theme: {
//     primary: colors.red.darken1, // #E53935
//     secondary: colors.red.lighten4, // #FFCDD2
//     accent: colors.indigo.base, // #3F51B5
//     success: colors.indigo.base,
//     info: colors.indigo.base
//   }
// });
// import 'material-design-icons-iconfont/dist/material-design-icons.css';
import 'vuetify/dist/vuetify.min.css';
// import 'style-loader/useable.js';

import router from './routes.js';
import store from './store.js';

//https://www.npmjs.com/package/vue-highcharts 
window.VueHighcharts = require('vue-highcharts');
window.Highcharts = require('highcharts');

import loadDrilldown  from 'highcharts/modules/drilldown.js';
loadDrilldown(Highcharts);

const bus = new Vue();
Vue.prototype.bus = bus;
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

/*const app = new Vue({
    el: '#app'
});
*/
const app = new Vue({
    router,
    store
}).$mount('#app');