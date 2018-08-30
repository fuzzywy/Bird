/*
 |-------------------------------------------------------------------------------
 | VUEX modules/cafes.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the cafes
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
                    commit( 'birdSideBar', response.data );
                    commit( 'birdSideBarStatus', 2 );
                })
                .catch( function(){
                    commit( 'birdSideBar', {} );
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