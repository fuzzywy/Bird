import PieChartAPI from '../api/PieChart.js';
export const PieChart = {
    state: {
        pieChartStatus: 0,
        pieChart: {}
    },
    actions: {
        loadPieChart({
            commit
        }, data) {
            commit('pieChartStatus', 1);
            PieChartAPI.getPieChart(data.optionState)
                .then(function(response) {
                    if (response.data != undefined) {
                        commit('pieChart', response.data);
                        commit('pieChartStatus', 2);
                    } else {
                        commit('pieChart', [response]);
                        commit('pieChartStatus', 3);
                    }
                })
                .catch(function() {
                    commit('pieChart', ['Connection failed']);
                    commit('pieChartStatus', 3);
                });
        }
    },
    mutations: {
        pieChartStatus(state, status) {
            state.pieChartStatus = status;
        },
        pieChart(state, pieChart) {
            state.pieChart = pieChart;
        }
    },
    getters: {
        pieChartStatus(state) {
            return state.pieChartStatus;
        },
        pieChart(state) {
            return state.pieChart;
        }
    }
}