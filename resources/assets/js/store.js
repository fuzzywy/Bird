window.Vue = require('vue');
window.Vuex = require('vuex');

Vue.use( Vuex );

import { BSideBar } from './modules/BSideBar.js';
import { BOperators } from './modules/BOperators.js';
import { BRegion } from './modules/BRegion.js';
export default new Vuex.Store({
  modules: {
  	BSideBar,
  	BOperators,
  	BRegion
  }
});