import BSideBarAPI from '../api/BSideBar.js';
export const BSideBar = {
  state: {
    bSideBarItemsStatus: 0,
    bSideBarItems: {}
  },
  actions: {
    loadBSideBarItems( {commit} ) {
      commit('bSideBarItemsStatus', 1);
      BSideBarAPI.getBSideBarItems()
      .then( function( response ){
        if ( response.data != undefined ) {
          commit( 'bSideBarItems', response.data );
          commit( 'bSideBarItemsStatus', 2 );
        }else {
          commit( 'bSideBarItems', [ response ] );
          commit( 'bSideBarItemsStatus' , 3 ); 
        }
      })
      .catch( function(){
        commit( 'bSideBarItems', [ 'Connection failed' ] );
        commit( 'bSideBarItemsStatus', 3 );
      });
    }
  },
  mutations: {
    bSideBarItemsStatus( state, status ){
      state.bSideBarItemsStatus = status;
    },
    bSideBarItems( state, bSideBarItems ){
      state.bSideBarItems = bSideBarItems;
    }
  },
  getters: {
    bSideBarItemsStatus( state ){
      return state.bSideBarItemsStatus;
    },
    bSideBarItems( state ){
      return state.bSideBarItems;
    }
  }
}