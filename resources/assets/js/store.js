/**
 * Import Vue and Vuex
 */
window.Vue = require('vue');
window.Vuex = require('vuex');

/**
 * Initializes Vuex on Vue.
 */
Vue.use( Vuex );

/**
 * Imports all of the modules used in the application to build the data store.
 */
//import { test } from './modules/test.js'
import { citys } from './modules/getCity.js'
import { birdSideBar } from './modules/getBirdSideBar.js'
import { bKpiCard } from './modules/getBKpiCard.js'
import { bLineChart } from './modules/getBLineChart.js'
/**
 * Export the data store.
 */
export default new Vuex.Store({
    modules: {
    	citys,
    	birdSideBar,
    	bKpiCard,
    	bLineChart
    }
});