/**
 * Imports the BirdSideBar API URL from the config.
 */
window.Vue = require('vue');
window.Vuex = require('vuex');
Vue.use( Vuex );

export default {
    /**
     * GET /api/city
     */
    getBirdSideBar: function(){
        return axios.get( 'getBirdSideBar' );
    }
}