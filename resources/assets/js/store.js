window.Vue = require('vue');
window.Vuex = require('vuex');

Vue.use(Vuex);

import { BSideBar } from './modules/BSideBar.js';
import { BOperators } from './modules/BOperators.js';
import { BRegion } from './modules/BRegion.js';
import { BTypes } from './modules/BTypes.js';
import { BCards } from './modules/BCards.js';
import { BChart } from './modules/BChart.js';
import { BCog } from './modules/BCog.js';
import { BarChart } from './modules/BarChart.js';
import { BubbleChart } from './modules/BubbleChart.js';
import { PieChart } from './modules/PieChart.js';
import { TopCell } from './modules/TopCell.js';
export default new Vuex.Store({
    modules: {
        BSideBar,
        BOperators,
        BRegion,
        BTypes,
        BCards,
        BChart,
        BCog,
        BarChart,
        BubbleChart,
        PieChart,
        TopCell
    }
});