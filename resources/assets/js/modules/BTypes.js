import BTypesAPI from '../api/BTypes.js';
export const BTypes = {
  state: {
    bTypesStatus: 0,
    bTypes: {}
  },
  actions: {
    loadBTypes( {commit} ) {
      commit('bTypesStatus', 1);
      BTypesAPI.getBTypes()
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'bTypes', response.data );
          commit( 'bTypesStatus', 2 );
        }else {
          commit( 'bTypes', [ response ] );
          commit( 'bTypesStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'bTypes', [ 'Connection failed' ] );
        commit( 'bTypesStatus', 3 );
      });
    }
  },
  mutations: {
    bTypesStatus( state, status ){
      state.bTypesStatus = status;
    },
    bTypes( state, bTypes ){
      state.bTypes = bTypes;
    }
  },
  getters: {
    bTypesStatus( state ){
      return state.bTypesStatus;
    },
    bTypes( state ){
      return state.bTypes;
    }
  }
}
