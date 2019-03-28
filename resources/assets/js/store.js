window.Vue = require('vue');
window.Vuex = require('vuex');

Vue.use( Vuex );

import { BSideBar } from './modules/BSideBar.js';
import { BOperators } from './modules/BOperators.js';
import { BRegion } from './modules/BRegion.js';
import { BTypes } from './modules/BTypes.js';
import { BCards } from './modules/BCards.js';
import { BChart } from './modules/BChart.js';
import { BCog } from './modules/BCog.js';
export default new Vuex.Store({
  modules: {
    BSideBar,
    BOperators,
    BRegion,
    BTypes,
    BCards,
    BChart,
    BCog
  }
});