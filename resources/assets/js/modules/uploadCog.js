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
        uploadCogStatus: 0,

        showCog: [],
        showCogStatus: 0,

        deleteCog: [],
        deleteCogStatus: 0
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
        },
        showCog( { commit } ) {
            commit( 'setCogStatus', 1 );
            UploadCogAPI.showCog()
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'setCog', response.data );
                    commit( 'setCogStatus', 2 );
                }else {
                    commit( 'setCog', [ response ] );
                    commit( 'setCogStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'setCog', [ 'Connection failed' ] );
                commit( 'setCogStatus', 3 );
            } )
        },
        deleteCog( { commit }, data ) {
            commit( 'setdeleteCogStatus', 1 );
            UploadCogAPI.deleteCog( data.ip, data.port, data.database, data.user, data.pwd, data.type )
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'setdeleteCog', response.data );
                    commit( 'setdeleteCogStatus', 2 );
                }else {
                    commit( 'setdeleteCog', [ response ] );
                    commit( 'setdeleteCogStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'setupdeleteCog', [ 'Connection failed' ] );
                commit( 'setupdeleteCogStatus', 3 );
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
        },

        setCogStatus( state, status ) {
            state.showCogStatus = status;
        },
        setCog( state, cog ) {
            state.showCog = []
            state.showCog.splice(0, state.showCog.length)
            state.showCog = cog
        },

        setdeleteCogStatus( state, status ) {
            state.deleteCogStatus = status;
        },
        setdeleteCog( state, deleteCog ) {
            state.deleteCog = []
            state.deleteCog.splice(0, state.deleteCog.length)
            state.deleteCog = deleteCog
        }

    },
    getters: {
        getuploadCogStatus( state ) {
            return state.uploadCogStatus;
        },
        getuploadCog:state=>{
            return state.uploadCog;
        },

        getShowCogStatus( state ) {
            return state.showCogStatus;
        },
        getShowCog:state=>{
            return state.showCog;
        },

        getdeleteCogStatus( state ) {
            return state.deleteCogStatus;
        },
        getdeleteCog:state=>{
            return state.uploadCog;
        },
    }
}