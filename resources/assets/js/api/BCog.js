export default {
  getBCog: function() {
    return axios.post( 'birdCog/show')
    .catch(function(error) {
        if (error.response) {
            if ( error.response.status == '404' ) {
                return error.response.status + ' Not Found';
            }else {
                return error.response.status;
            }
        } else if (error.request) {
            return 'Request failed'
        } else {
            return 'Other errors'
        }
    });
  },
  getBECog: function(editedItem) {
    return axios.post( 'birdCog/edit', {
        editedItem: editedItem
    })
    .catch(function(error) {
        if (error.response) {
            if ( error.response.status == '404' ) {
                return error.response.status + ' Not Found';
            }else {
                return error.response.status;
            }
        } else if (error.request) {
            return 'Request failed'
        } else {
            return 'Other errors'
        }
    });
  },
  getBDCog: function(item) {
    return axios.post( 'birdCog/delete', {
        item: item
    })
    .catch(function(error) {
        if (error.response) {
            if ( error.response.status == '404' ) {
                return error.response.status + ' Not Found';
            }else {
                return error.response.status;
            }
        } else if (error.request) {
            return 'Request failed'
        } else {
            return 'Other errors'
        }
    });
  }
}