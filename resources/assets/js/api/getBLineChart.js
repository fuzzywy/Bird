/**
 * Imports the BLineChartAPI URL from the config.
 */
 export default {
 	getBLineChart: function( type, city, overview ) {
 		return axios.get('getcharts', {
            params: {
            	data: type,
				city: city,
				overview: overview
            }
        });
 	}
 }