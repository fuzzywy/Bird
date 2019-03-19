import BChartAPI from '../api/BChart.js';
export const BChart = {
  state: {
    bChartStatus: 0,
    bChart: {}
  },
  actions: {
    loadBChart( {commit}, data ) {
      commit('bChartStatus', 1);
      BChartAPI.getBChart( data.bSideBar, data.operator, data.city, data.type, data.card, data.province )
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'bChart', response.data );
          commit( 'bChartStatus', 2 );
        }else {
          commit( 'bChart', [ response ] );
          commit( 'bChartStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'bChart', [ 'Connection failed' ] );
        commit( 'bChartStatus', 3 );
      });
    }
  },
  mutations: {
    bChartStatus( state, status ){
      state.bChartStatus = status;
    },
    bChart( state, bChart ){
      state.bChart = bChart;
    }
  },
  getters: {
    bChartStatus( state ){
      return state.bChartStatus;
    },
    bChart( state ){
      return state.bChart;
    }
  }
}
