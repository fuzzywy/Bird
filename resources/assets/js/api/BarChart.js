export default {
    getBarChart: function(optionState) {
        return axios.post('barChart/show', {
                card: optionState.card,
                city: optionState.city,
                operator: optionState.operator,
                province: optionState.province,
                timeDim: optionState.timeDim,
                type: optionState.type,
                clickTime: optionState.clickTime,
                clickLineName: optionState.clickLineName,
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