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
        })
        .catch(function(error) {
			if (error.response) {
		      	// 发送请求后，服务端返回的响应码不是 2xx   
		      	// console.log(error.response.data);
		      	// console.log(error.response.status);
		      	// console.log(error.response.headers);
		      	if ( error.response.status == '404' ) {
		      		return error.response.status + ' Not Found';
		      	}else {
		      		return error.response.status;
		      	}
		    } else if (error.request) {
			    // 发送请求但是没有响应返回
			    return error.request
			    // console.log(error.request);
		    } else {
		      	// 其他错误
		      	return error.message
		      	// console.log('Error', error.message);
		    }
		    // console.log(error.config);
		});
 	}
 }