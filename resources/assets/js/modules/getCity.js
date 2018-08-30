/*
 |-------------------------------------------------------------------------------
 | VUEX modules/cafes.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the cafes
 */

import CityAPI from '../api/getCity.js';

export const citys = {
    /**
     * Defines the state being monitored for the module.
     */
    state: {
        citys: {},
        cityLoadStatus: 0
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {

        loadCitys( { commit } ){
            commit( 'cityLoadStatus', 1 );

            CityAPI.getCity(  )
                .then( function( response ){
                    commit( 'citys', response.data );
                    commit( 'cityLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'citys', {} );
                    commit( 'cityLoadStatus', 3 );
                });

        }
    },
    /**
     * Defines the mutations used
     */
    mutations: {

        cityLoadStatus( state, status ){
            state.cityLoadStatus = status;
        },

        citys( state, citys ){
            state.citys = citys;
        }
    },
    /**
     * Defines the getters used by the module
     */
    getters: {
        cityLoadStatus( state ){
            return state.cityLoadStatus;
        },

        citys( state ){
            return state.citys;
        }
    }
};