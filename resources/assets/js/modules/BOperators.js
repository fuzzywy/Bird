import BOperatorsAPI from '../api/BOperators.js';
export const BOperators = {
  state: {
    bOperatorsStatus: 0,
    bOperators: {}
  },
  actions: {
    loadBOperators( {commit} ) {
      commit('bOperatorsStatus', 1);
      BOperatorsAPI.getBOperators()
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'bOperators', response.data );
          commit( 'bOperatorsStatus', 2 );
        }else {
          commit( 'bOperators', [ response ] );
          commit( 'bOperatorsStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'bOperators', [ 'Connection failed' ] );
        commit( 'bOperatorsStatus', 3 );
      });
    }
  },
  mutations: {
    bOperatorsStatus( state, status ){
      state.bOperatorsStatus = status;
    },
    bOperators( state, bOperators ){
      state.bOperators = bOperators;
    }
  },
  getters: {
    bOperatorsStatus( state ){
      return state.bOperatorsStatus;
    },
    bOperators( state ){
      return state.bOperators;
    }
  }
}