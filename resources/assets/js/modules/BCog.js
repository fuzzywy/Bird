import BCogAPI from '../api/BCog.js';
export const BCog = {
  state: {
    bCogStatus: 0,
    bCog: {},
    editCogStatus: 0,
    editCog: {},
    delCogStatus: 0,
    delCog: {}
  },
  actions: {
    loadBCog( {commit} ) {
      commit('bCogStatus', 1);
      BCogAPI.getBCog()
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'bCog', response.data );
          commit( 'bCogStatus', 2 );
        }else {
          commit( 'bCog', [ response ] );
          commit( 'bCogStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'bCog', [ 'Connection failed' ] );
        commit( 'bCogStatus', 3 );
      });
    },
    editCog( {commit}, data ) {
      commit('editCogStatus', 1);
      BCogAPI.getBECog(data.editedItem)
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'editCog', response.data );
          commit( 'editCogStatus', 2 );
        }else {
          commit( 'editCog', [ response ] );
          commit( 'editCogStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'editCog', [ 'Connection failed' ] );
        commit( 'editCogStatus', 3 );
      });
    },
    delCog( {commit}, data ) {
      commit('delCogStatus', 1);
      BCogAPI.getBDCog(data.item)
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'delCog', response.data );
          commit( 'delCogStatus', 2 );
        }else {
          commit( 'delCog', [ response ] );
          commit( 'delCogStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'delCog', [ 'Connection failed' ] );
        commit( 'delCogStatus', 3 );
      });
    }
  },
  mutations: {
    bCogStatus( state, status ){
      state.bCogStatus = status;
    },
    bCog( state, bCog ){
      state.bCog = bCog;
    },
    editCogStatus( state, status ){
      state.editCogStatus = status;
    },
    editCog( state, editCog ){
      state.editCog = editCog;
    },
    delCogStatus( state, status ){
      state.delCogStatus = status;
    },
    delCog( state, delCog ){
      state.delCog = delCog;
    },
  },
  getters: {
    bCogStatus( state ){
      return state.bCogStatus;
    },
    bCog( state ){
      return state.bCog;
    },
    editCogStatus( state ){
      return state.editCogStatus;
    },
    editCog( state ){
      return state.editCog;
    },
    delCogStatus( state ){
      return state.delCogStatus;
    },
    delCog( state ){
      return state.delCog;
    }
  }
}