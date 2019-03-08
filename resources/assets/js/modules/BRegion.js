import BRegionAPI from '../api/BRegion.js';
export const BRegion = {
  state: {
    bRegionStatus: 0,
    bRegion: {}
  },
  actions: {
    loadBRegion( {commit} ) {
      commit('bRegionStatus', 1);
      BRegionAPI.getBRegion()
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'bRegion', response.data );
          commit( 'bRegionStatus', 2 );
        }else {
          commit( 'bRegion', [ response ] );
          commit( 'bRegionStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'bRegion', [ 'Connection failed' ] );
        commit( 'bRegionStatus', 3 );
      });
    }
  },
  mutations: {
    bRegionStatus( state, status ){
      state.bRegionStatus = status;
    },
    bRegion( state, bRegion ){
      state.bRegion = bRegion;
    }
  },
  getters: {
    bRegionStatus( state ){
      return state.bRegionStatus;
    },
    bRegion( state ){
      return state.bRegion;
    }
  }
}