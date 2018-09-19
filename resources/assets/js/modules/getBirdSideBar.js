/*
 |-------------------------------------------------------------------------------
 | VUEX modules/getBirdSideBar.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the getBirdSideBar
 */

import BirdSideBarAPI from '../api/getBirdSideBar.js';

export const birdSideBar = {
    /**
     * Defines the state being monitored for the module.
     */
    state: {
        birdSideBar: {},
        birdSideBarStatus: 0
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {

        loadBirdSideBarStatus( { commit } ){
            commit( 'birdSideBarStatus', 1 );

            BirdSideBarAPI.getBirdSideBar(  )
                .then( function( response ){
                    if ( response.data != undefined ) {
                        commit( 'birdSideBar', response.data );
                        commit( 'birdSideBarStatus', 2 );
                    }else {
                        commit( 'birdSideBar', [ response ] );
                        commit( 'birdSideBarStatus', 3 ); 
                    }
                    
                })
                .catch( function(){
                    commit( 'birdSideBar', [ 'Connection failed' ] );
                    commit( 'birdSideBarStatus', 3 );
                });

        }
    },
    /**
     * Defines the mutations used
     */
    mutations: {

        birdSideBarStatus( state, status ){
            state.birdSideBarStatus = status;
        },

        birdSideBar( state, birdSideBar ){
            state.birdSideBar = birdSideBar;
        }
    },
    /**
     * Defines the getters used by the module
     */
    getters: {
        birdSideBarStatus( state ){
            return state.birdSideBarStatus;
        },

        birdSideBar( state ){
            return state.birdSideBar;
        }
    }
};