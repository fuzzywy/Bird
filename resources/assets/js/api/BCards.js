export default {
    getBCards: function(bSideBar, operator, province, city, type) {
        return axios.post('birdCards/show', {
                bSideBar: bSideBar,
                operator: operator,
                province: province,
                city: city,
                type: type
            })
            .catch(function(error) {
                if (error.response) {
                    if (error.response.status == '404') {
                        return error.response.status + ' Not Found';
                    } else {
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