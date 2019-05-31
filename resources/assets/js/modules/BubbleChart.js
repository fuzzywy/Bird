import BubbleChartAPI from '../api/BubbleChart.js';
export const BubbleChart = {
    state: {
        bubbleChartStatus: 0,
        bubbleChart: {}
    },
    actions: {
        loadBubbleChart({
            commit
        }, data) {
            commit('bubbleChartStatus', 1);
            BubbleChartAPI.getBubbleChart(data.optionState)
                .then(function(response) {
                    if (response.data != undefined) {
                        commit('bubbleChart', response.data);
                        commit('bubbleChartStatus', 2);
                    } else {
                        commit('bubbleChart', [response]);
                        commit('bubbleChartStatus', 3);
                    }
                })
                .catch(function() {
                    commit('bubbleChart', ['Connection failed']);
                    commit('bubbleChartStatus', 3);
                });
        }
    },
    mutations: {
        bubbleChartStatus(state, status) {
            state.bubbleChartStatus = status;
        },
        bubbleChart(state, bubbleChart) {
            state.bubbleChart = bubbleChart;
        }
    },
    getters: {
        bubbleChartStatus(state) {
            return state.bubbleChartStatus;
        },
        bubbleChart(state) {
            return state.bubbleChart;
        }
    }
}