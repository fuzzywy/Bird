/**
 * Imports the BKpiCardAPI URL from the config.
 */
 export default {
 	getBKpiCard: function( type, city, overview ) {
 		return axios.get('getTabs', {
            params: {
            	data: type,
				city: city,
				overview: overview
            }
        });
 	}
 }