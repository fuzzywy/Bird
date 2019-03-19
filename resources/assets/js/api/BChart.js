export default {
  getBChart: function( bSideBar, operator, city, type, card, province ) {
    return axios.post( 'birdChart/show', {
      bSideBar: bSideBar,
      operator: operator,
      city: city,
      type: type,
      card: card,
      province: province
    } )
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