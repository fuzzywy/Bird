/**
 * Imports the uploadCog URL from the config.
 */
 export default {
 	uploadCog: function( ip, port, database, user, pwd, type ) {
 		let postData = qs.stringify({
		    ip: ip,
			port: port,
			database: database,
			user: user,
			pwd: pwd,
			type: type
		});
 		return axios.post('uploadCog', {
            data: postData
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
			    return 'Request failed'
			    // return error.request
			    // console.log(error.request);
		    } else {
		      	// 其他错误
		      	return 'Other errors'
		      	// return error.message
		      	// console.log('Error', error.message);
		    }
		    // console.log(error.config);
		});
 	},
 	showCog: function() {
 		return axios.post('showCog')
			.catch(function(error) {
			if (error.response) {
		      	// 发送请求后，服务端返回的响应码不是 2xx   
		      	if ( error.response.status == '404' ) {
		      		return error.response.status + ' Not Found';
		      	}else {
		      		return error.response.status;
		      	}
		    } else if (error.request) {
			    // 发送请求但是没有响应返回
			    return 'Request failed'
		    } else {
		      	// 其他错误
		      	return 'Other errors'
		    }
		    // console.log(error.config);
		});
 	},
 	deleteCog: function( ip, port, database, user, pwd, type ) {
 		let postData = qs.stringify({
		    ip: ip,
			port: port,
			database: database,
			user: user,
			pwd: pwd,
			type: type
		});
 		return axios.post( 'deleteCog', {
            data: postData
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
			    return 'Request failed'
			    // return error.request
			    // console.log(error.request);
		    } else {
		      	// 其他错误
		      	return 'Other errors'
		      	// return error.message
		      	// console.log('Error', error.message);
		    }
		    // console.log(error.config);
		});
 	}
 }