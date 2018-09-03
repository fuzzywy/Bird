/*
 |-------------------------------------------------------------------------------
 | VUEX modules/getBLineChart.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the getBLineChart
 */
import BLineChartAPI from "../api/getBLineChart.js";

export const bLineChart = {
    state: {
        bLineChart: [],
        bLineChartStatus: 0
    },
    actions: {
        loadBLineChartStatus( { commit }, data ) {
            commit( 'setbLineChartStatus', 1 );
            BLineChartAPI.getBLineChart( data.type, data.city, data.overview )
            .then( (response) => {
                commit( 'setbLineChart', response.data );
                commit( 'setbLineChartStatus', 2 );
            } )
            .catch( function() {
                commit( 'setbLineChart', {} );
                commit( 'setbLineChartStatus', 3 );
            } )
        }
    },
    mutations: {
        setbLineChartStatus( state, status ) {
            state.bLineChartStatus = status;
        },
        setbLineChart( state, bLineChart ) {
            state.bLineChart = []
            state.bLineChart.splice(0, state.bLineChart.length)
            state.bLineChart = bLineChart
            // console.log(state.bLineChart)
        }
    },
    getters: {
        getbLineChartStatus( state ) {
            return state.bLineChartStatus;
        },
        getbLineChart:state=>{
            return state.bLineChart;
        }
    }
}