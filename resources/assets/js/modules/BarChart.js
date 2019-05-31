import BarChartAPI from '../api/BarChart.js';
export const BarChart = {
    state: {
        barChartStatus: 0,
        barChart: {}
    },
    actions: {
        loadBarChart({
            commit
        }, data) {
            commit('barChartStatus', 1);
            BarChartAPI.getBarChart(data.optionState)
                .then(function(response) {
                    if (response.data != undefined) {
                        commit('barChart', response.data);
                        commit('barChartStatus', 2);
                    } else {
                        commit('barChart', [response]);
                        commit('barChartStatus', 3);
                    }
                })
                .catch(function() {
                    commit('barChart', ['Connection failed']);
                    commit('barChartStatus', 3);
                });
        }
    },
    mutations: {
        barChartStatus(state, status) {
            state.barChartStatus = status;
        },
        barChart(state, barChart) {
            state.barChart = barChart;
        }
    },
    getters: {
        barChartStatus(state) {
            return state.barChartStatus;
        },
        barChart(state) {
            return state.barChart;
        }
    }
}