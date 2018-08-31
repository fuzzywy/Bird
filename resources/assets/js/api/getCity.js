/**
 * Imports the City API URL from the config.
 */
// window.Vue = require('vue');
// window.Vuex = require('vuex');
// Vue.use( Vuex );

export default {
    /**
     * GET /api/city
     */
    getCity: function(){
        return axios.get( 'getCity' );
    }
}