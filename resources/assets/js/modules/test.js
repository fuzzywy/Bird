/*
 |-------------------------------------------------------------------------------
 | VUEX modules/cafes.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the cafes
 */

import TestAPI from '../api/test.js';

export const test = {
    /**
     * Defines the state being monitored for the module.
     */
    state: {
        test: {},
        testLoadStatus: 0
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {

        loadTest( { commit } ){
            commit( 'testLoadStatus', 1 );

            TestAPI.getCafe(  )
                .then( function( response ){
                    commit( 'setCafe', response.data );
                    commit( 'testLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setCafe', {} );
                    commit( 'testLoadStatus', 3 );
                });

        }
    },
    /**
     * Defines the mutations used
     */
    mutations: {

        testLoadStatus( state, status ){
            state.testLoadStatus = status;
        },

        setCafe( state, cafe ){
            state.test = cafe;
        }
    },
    /**
     * Defines the getters used by the module
     */
    getters: {
        getCafeLoadStatus( state ){
            return state.testLoadStatus;
        },

        getCafe( state ){
            return state.test;
        }
    }
};