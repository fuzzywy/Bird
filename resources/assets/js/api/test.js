/**
 * Imports the Roast API URL from the config.
 */
// import { ROAST_CONFIG } from '../config.js';
// window.Vue = require('vue');
// window.Vuex = require('vuex');
// Vue.use( Vuex );

export default {
    /**
     * GET /api/v1/cafes
     */
    getCafe: function(){
        return axios.get( 'test' );
    }
}