/*
 |-------------------------------------------------------------------------------
 | VUEX modules/uploadCog.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the getBLineChart
 */
import UploadCogAPI from "../api/uploadCog.js";

export const uploadCog = {
    state: {
        uploadCog: [],
        uploadCogStatus: 0
    },
    actions: {
        uploadCog( { commit }, data ) {
            commit( 'setuploadCogStatus', 1 );
            UploadCogAPI.uploadCog( data.ip, data.port, data.database, data.user, data.pwd, data.type )
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'setuploadCog', response.data );
                    commit( 'setuploadCogStatus', 2 );
                }else {
                    commit( 'setuploadCog', [ response ] );
                    commit( 'setuploadCogStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'setuploadCog', [ 'Connection failed' ] );
                commit( 'setuploadCogStatus', 3 );
            } )
        }
    },
    mutations: {
        setuploadCogStatus( state, status ) {
            state.uploadCogStatus = status;
        },
        setuploadCog( state, uploadCog ) {
            state.uploadCog = []
            state.uploadCog.splice(0, state.uploadCog.length)
            state.uploadCog = uploadCog
            // console.log(state.bLineChart)
        }
    },
    getters: {
        getuploadCogStatus( state ) {
            return state.bLineChartStatus;
        },
        getuploadCog:state=>{
            return state.uploadCog;
        }
    }
}